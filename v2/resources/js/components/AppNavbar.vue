<script setup>
/**
 * AppNavbar.vue
 * 
 * 應用程式主導覽列元件
 * 
 * 功能：
 * 1. 響應式導覽 (RWD)：在小螢幕上自動切換為漢堡選單。
 * 2. 使用者狀態管理：根據登入狀態顯示不同選項 (購物車、會員中心、後台)。
 * 3. 視覺效果：採用 Glassmorphism (玻璃擬態) 設計。
 */

import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { useCartStore } from "../stores/cart";
import Menubar from 'primevue/menubar';
import Menu from 'primevue/menu';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Button from 'primevue/button';

const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const menu = ref();

// 導覽項目 (PrimeVue Menubar Model)
// 定義左側的主要導覽連結
const items = computed(() => [
    {
        label: 'Home',
        icon: 'pi pi-home',
        command: () => router.push('/')
    },
    {
        label: 'Ranklist',
        icon: 'pi pi-list',
        command: () => router.push('/ranklist')
    }
]);

// 使用者下拉選單項目
// 根據使用者權限 (是否為管理員) 動態產生選單內容
const userMenuItems = computed(() => {
    const baseItems = [
        {
            label: 'Member Center', // 會員中心
            icon: 'pi pi-user',
            command: () => router.push('/member/dashboard')
        },
        {
            separator: true // 分隔線
        },
        {
            label: 'Logout', // 登出
            icon: 'pi pi-sign-out',
            command: async () => {
                await authStore.logout();
                router.push('/login');
            }
        }
    ];

    // 若為管理員，加入後台儀表板選項
    if (authStore.isAdmin) {
        baseItems.unshift({
            label: 'Admin Dashboard',
            icon: 'pi pi-cog',
            command: () => router.push('/admin')
        });
    }

    return baseItems;
});

// 切換使用者選單顯示狀態
const toggleUserMenu = (event) => {
    menu.value.toggle(event);
};
</script>

<template>
    <div class="modern-navbar-wrapper">
        <Menubar :model="items" class="glass-navbar border-none">
            <!-- Brand (Start) -->
            <template #start>
                <router-link to="/" class="flex items-center gap-2 mr-4 no-underline">
                   <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-80 transition-opacity">
                       BlogMix
                   </span>
                </router-link>
            </template>

            <!-- 右側功能區 (購物車、使用者選單、登入) -->
            <template #end>
                <div class="flex items-center gap-4">
                    <!-- 已登入狀態 -->
                    <template v-if="authStore.user">
                        <!-- 購物車按鈕 -->
                        <router-link to="/cart" class="relative text-gray-600 hover:text-blue-600 transition w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-100">
                            <i class="pi pi-shopping-cart text-2xl" />
                            <Badge v-if="cartStore.totalItems > 0" :value="cartStore.totalItems" severity="danger" class="absolute top-0 right-0" style="font-size: 0.75rem" />
                        </router-link>

                        <!-- 使用者頭像與下拉選單 -->
                        <div class="relative">
                           <Avatar 
                                :label="authStore.user.name?.[0]?.toUpperCase() || 'U'" 
                                shape="circle" 
                                size="large"
                                class="cursor-pointer bg-indigo-100 text-indigo-700 font-bold hover:ring-2 hover:ring-indigo-300 hover:ring-offset-2 transition"
                                @click="toggleUserMenu"
                                aria-haspopup="true" 
                                aria-controls="overlay_menu"
                           />
                           <Menu ref="menu" id="overlay_menu" :model="userMenuItems" :popup="true" />
                        </div>
                    </template>

                    <!-- 未登入狀態 (訪客) -->
                    <template v-else>
                         <div class="flex gap-2">
                             <Button label="Login" text severity="secondary" @click="router.push('/login')" />
                             <Button label="Get Started" severity="primary" rounded @click="router.push('/register')" />
                         </div>
                    </template>
                </div>
            </template>
        </Menubar>
    </div>
</template>

<style scoped>
/* Glassmorphism Navbar */
.modern-navbar-wrapper {
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 1rem 2rem;
    pointer-events: none; /* Let clicks pass through empty space */
}

.glass-navbar {
    pointer-events: auto; /* Re-enable clicks on the navbar itself */
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 0.75rem 1.5rem;
}

/* Responsive tweaks if needed */
@media (max-width: 768px) {
    .modern-navbar-wrapper {
        padding: 0.5rem;
    }
}
</style>
