<script setup>
import Card from 'primevue/card';
import Button from 'primevue/button';
import { useRouter } from 'vue-router';

const router = useRouter();

const goHome = () => {
    router.push({ name: 'home' });
};
</script>

<template>
    <div class="w-full min-h-[60vh] flex flex-col items-center justify-center p-4">
        <Card class="w-full max-w-md shadow-xl glass-effect border-round-xl">
            <template #title>
                <div class="text-center font-bold text-7xl mb-1 gradient-text">404</div>
            </template>
            <template #content>
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-3 text-surface-900 dark:text-surface-0">找不到頁面</h2>
                    <p class="text-lg text-surface-600 dark:text-surface-300 mb-5 line-height-3">
                        抱歉，您要找的頁面不存在、<br>已被移除或暫時無法存取。
                    </p>
                    <div class="w-full flex justify-center gap-3">
                        <Button label="回到首頁" icon="pi pi-home" @click="goHome" 
                            class="p-button-rounded p-button-lg border-none hover-effect" 
                            :style="{ background: 'var(--gradient-primary)', boxShadow: 'var(--shadow-md)' }" />
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<style scoped>
/* Replaced content-height with Tailwind utility classes in template */

.gradient-text {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg) !important;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.6);
}

/* Dark mode adjustment for glass effect */
:global(.dark) .glass-effect {
    background: rgba(30, 30, 30, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

:deep(.p-card-body) {
    padding: 2.5rem;
}
</style>
