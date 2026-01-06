<template>
  <div class="container mt-xl" style="max-width: 800px;">
    <h1 class="mb-xl text-center">Your Shopping Cart</h1>

    <div v-if="cartStore.loading" class="flex justify-center p-xl"><div class="loader"></div></div>
    
    <div v-else-if="cartStore.items.length === 0" class="card text-center p-xl">
      <div class="mb-lg text-secondary" style="font-size: 4rem;">🛒</div>
      <h2 class="mb-md">Your cart is empty</h2>
      <p class="mb-xl text-secondary">Looks like you haven't added anything yet.</p>
      <router-link to="/" class="btn">Discover Articles</router-link>
    </div>

    <div v-else>
      <div class="card mb-lg overflow-hidden">
        <!-- Cart Items List -->
        <div v-for="(item, index) in cartStore.items" :key="item.id" 
             class="cart-item flex flex-col md:flex-row justify-between items-center p-lg"
             :style="index !== cartStore.items.length - 1 ? 'border-bottom: 1px solid var(--color-border)' : ''">
             
          <div class="flex items-center gap-md w-full md:w-auto">
             <div class="item-icon bg-body flex justify-center items-center rounded-md" style="width: 60px; height: 60px; font-size: 1.5rem;">
                📄
             </div>
             <div>
                <h3 class="text-lg font-bold">{{ item.article?.title }}</h3>
                <div class="text-secondary text-sm">Unit Price: ${{ item.article?.price }}</div>
             </div>
          </div>
          
          <div class="flex items-center gap-xl mt-md md:mt-0 w-full md:w-auto justify-between md:justify-end">
             <div class="flex items-center gap-sm">
                 <button @click="updateQuantity(item, item.quantity - 1)" class="btn-qty" :disabled="item.quantity <= 1">-</button>
                 <input type="number" :value="item.quantity" @change="updateQuantity(item, $event.target.value)" class="input-qty" min="1">
                 <button @click="updateQuantity(item, item.quantity + 1)" class="btn-qty">+</button>
             </div>
             <div class="text-right" style="min-width: 100px;">
                 <div class="font-bold text-lg mb-xs text-primary">${{ (item.article?.price * item.quantity).toFixed(2) }}</div>
                 <button @click="removeItem(item.id)" class="text-danger text-sm hover:underline" style="color: var(--color-danger); background: none; border: none; cursor: pointer;">
                    <i class="fas fa-trash-alt mr-xs"></i> Remove
                 </button>
             </div>
          </div>
        </div>
        
        <!-- Cart Summary Footer -->
        <div class="p-lg bg-body" style="background: #f8fafc; border-top: 1px solid var(--color-border);">
           <div class="flex justify-between items-center mb-sm">
               <span class="text-secondary">Subtotal</span>
               <span class="font-bold">${{ cartStore.totalPrice }}</span>
           </div>
           <div class="flex justify-between items-center mb-lg">
               <span class="text-secondary">Shipping</span>
               <span class="text-success">Free</span>
           </div>
           <div class="flex justify-between items-center pt-md" style="border-top: 1px solid var(--color-border);">
               <span class="text-xl font-bold">Total</span>
               <span class="text-2xl font-bold text-primary" style="color: var(--color-primary);">${{ cartStore.totalPrice }}</span>
           </div>
        </div>
      </div>

      <div class="flex justify-between items-center mt-xl">
        <router-link to="/" class="btn-link text-secondary">&larr; Continue Shopping</router-link>
        <router-link to="/checkout" class="btn btn-lg shadow-lg">Proceed to Checkout</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCartStore } from '../stores/cart'

// 使用 Pinia Cart Store 進行狀態管理
const cartStore = useCartStore()

// 移除購物車項目 (Remove Item)
const removeItem = async (id) => {
    await cartStore.removeItem(id)
}

// 更新數量 (Update Quantity)
// 防呆機制：數量至少為 1
const updateQuantity = async (item, newQty) => {
  const qty = parseInt(newQty);
  if (qty > 0) {
    await cartStore.updateItem(item.id, qty);
  }
}

// 元件掛載時，同步購物車資料
onMounted(() => {
  cartStore.fetchCart()
})
</script>

<style scoped>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid var(--color-primary);
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.text-danger:hover {
    text-decoration: underline;
}

.btn-qty {
  width: 30px;
  height: 30px;
  border: 1px solid var(--color-border);
  background: white;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: var(--color-text-main);
}
.btn-qty:hover:not(:disabled) {
  background: var(--color-bg-body);
}
.btn-qty:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.input-qty {
  width: 50px;
  height: 30px;
  text-align: center;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  -moz-appearance: textfield;
}
.input-qty::-webkit-outer-spin-button,
.input-qty::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
