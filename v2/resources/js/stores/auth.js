import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,                             // 使用者物件 (User object)
        token: localStorage.getItem('token') || null, // API Token (JWT)
        isAuthenticated: !!localStorage.getItem('token') // 是否已登入
    }),
    getters: {
        // 判斷是否為管理員 (Check Admin Role)
        isAdmin: (state) => state.user?.role === 'admin'
    },
    actions: {
        // 初始化 Auth 狀態 (Initialize)
        // 應用程式啟動時呼叫，設定 Axios Header 並嘗試取得使用者資料
        initialize() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
                this.fetchUser()
            }
        },
        // 取得使用者資料 (Fetch User Profile)
        async fetchUser() {
            try {
                if (!this.token) return
                const response = await axios.get('/api/me')
                this.user = response.data
                this.isAuthenticated = true
            } catch (error) {
                // Token 無效或過期，執行登出清理
                this.user = null
                this.token = null
                this.isAuthenticated = false
                localStorage.removeItem('token')
                delete axios.defaults.headers.common['Authorization']
            }
        },
        // 登入 (Login)
        async login(credentials) {
            // 發送登入請求
            console.log('Sending login request...', { ...credentials, device_name: 'web' });
            const response = await axios.post('/api/login', { ...credentials, device_name: 'web' })
            console.log('Login response received:', response.data);

            // 更新狀態與 LocalStorage
            this.token = response.data.token
            this.user = response.data.user
            this.isAuthenticated = true

            console.log('Storing token:', this.token);
            localStorage.setItem('token', this.token)

            // 設定全域 Axios Authorization Header
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        },
        // 登出 (Logout)
        async logout() {
            try {
                await axios.post('/api/logout')
            } catch (e) {
                // 忽略登出 API 錯誤 (可能是 Token 已失效)
            } finally {
                // 清除前端狀態與暫存
                this.user = null
                this.token = null
                this.isAuthenticated = false
                localStorage.removeItem('token')
                delete axios.defaults.headers.common['Authorization']
            }
        }
    }
})
