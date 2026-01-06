import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],          // 購物車項目列表
        loading: false      // 載入狀態
    }),
    getters: {
        // 計算總項目數量 (Total Items)
        totalItems: (state) => state.items.reduce((sum, item) => sum + item.quantity, 0),
        // 計算總金額 (Total Price)
        totalPrice: (state) => state.items.reduce((sum, item) => sum + (item.article?.price * item.quantity), 0)
    },
    actions: {
        // 取得購物車內容 (Fetch Cart)
        async fetchCart() {
            this.loading = true
            try {
                const response = await axios.get('/api/cart')
                this.items = response.data.items || []
            } catch (e) {
                console.error('Failed to fetch cart', e)
            } finally {
                this.loading = false
            }
        },
        // 加入購物車 (Add Item)
        async addItem(articleId, quantity = 1) {
            try {
                const response = await axios.post('/api/cart/items', {
                    article_id: articleId,
                    quantity: quantity
                })
                // 加入成功後，重新取得購物車以確保資料同步
                await this.fetchCart()
                return true
            } catch (e) {
                console.error('Failed to add item', e)
                return false
            }
        },
        // 更新項目數量 (Update Quantity)
        // 採取樂觀更新策略 (Optimistic UI Update)
        async updateItem(cartItemId, quantity) {
            if (quantity < 1) return;

            // 1. 先在本地更新 UI，提供即時回饋
            const item = this.items.find(i => i.id === cartItemId);
            if (!item) return;

            const originalQuantity = item.quantity; // 備份原始數量以便回滾
            item.quantity = quantity;

            try {
                // 2. 背景發送 API 請求
                await axios.put(`/api/cart/items/${cartItemId}`, { quantity });

                // 成功則無需額外操作 (已信任本地更新)
            } catch (e) {
                console.error('Failed to update item', e);
                item.quantity = originalQuantity; // 3. 失敗時回滾狀態 (Revert)
            }
        },
        // 移除項目 (Remove Item)
        async removeItem(cartItemId) {
            try {
                await axios.delete(`/api/cart/items/${cartItemId}`)
                await this.fetchCart() // 重新同步
            } catch (e) {
                console.error('Failed to remove item', e)
            }
        }
    }
})
