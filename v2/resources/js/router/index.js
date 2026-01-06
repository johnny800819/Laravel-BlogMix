import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import RegisterView from '../views/RegisterView.vue';
import HomeView from '../views/HomeView.vue';
import NotFoundView from '../views/NotFoundView.vue';

const router = createRouter({
    history: createWebHistory(),
    // Force Hash Change 123
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
            meta: { title: 'Home' }
        },
        {
            path: '/articles/:id',
            name: 'article-detail',
            component: () => import('../views/ArticleView.vue'),
            meta: { title: 'Article Detail' }
        },
        {
            path: '/ranklist',
            name: 'ranklist',
            component: () => import('../views/RanklistView.vue'),
            meta: { title: 'Top Articles' }
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('../views/CartView.vue'),
            meta: { title: 'Shopping Cart' }
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: () => import('../views/CheckoutView.vue'),
            meta: { requiresAuth: true, title: 'Checkout' }
        },
        {
            path: '/member',
            component: () => import('../layouts/MemberLayout.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    redirect: '/member/dashboard'
                },
                {
                    path: 'dashboard',
                    name: 'member-dashboard',
                    component: () => import('../views/Member/DashboardView.vue'),
                    meta: { title: 'Member Dashboard' }
                },
                {
                    path: 'orders',
                    name: 'member-orders',
                    component: () => import('../views/Member/Orders/OrderListView.vue'),
                    meta: { title: 'My Orders' }
                },
                {
                    path: 'orders/:id',
                    name: 'member-orders.show',
                    component: () => import('../views/Member/Orders/OrderDetailView.vue'),
                    meta: { title: 'Order Detail' }
                },
                {
                    path: 'tickets',
                    name: 'member-tickets',
                    component: () => import('../views/Member/Service/TicketListView.vue'),
                    meta: { title: 'Customer Service' }
                },
                {
                    path: 'tickets/create',
                    name: 'member-tickets.create',
                    component: () => import('../views/Member/Service/TicketCreateView.vue'),
                    meta: { title: 'New Ticket' }
                },
                {
                    path: 'tickets/:id',
                    name: 'member-tickets.show',
                    component: () => import('../views/Member/Service/TicketDetailView.vue'),
                    meta: { title: 'Ticket Detail' }
                },
            ]
        },
        {
            path: '/login',
            name: 'login',
            component: LoginView,
            meta: { title: 'Login' }
        },
        {
            path: '/admin',
            component: () => import('../layouts/AdminLayout.vue'),
            children: [
                {
                    path: '',
                    redirect: '/admin/dashboard'
                },
                {
                    path: 'dashboard',
                    name: 'admin-dashboard',
                    component: () => import('../views/admin/AdminDashboard.vue'),
                    meta: { title: 'Admin Dashboard' }
                },
                {
                    path: 'articles',
                    name: 'admin-articles',
                    component: () => import('../views/admin/AdminArticles.vue'),
                    meta: { title: 'Manage Articles' }
                },
                {
                    path: 'articles/create',
                    name: 'admin-articles.create',
                    component: () => import('../views/admin/AdminArticleForm.vue'),
                    meta: { title: 'Create Article' }
                },
                {
                    path: 'articles/:id/edit',
                    name: 'admin-articles.edit',
                    component: () => import('../views/admin/AdminArticleForm.vue'),
                    meta: { title: 'Edit Article' }
                },
                {
                    path: 'orders',
                    name: 'admin-orders',
                    component: () => import('../views/admin/AdminOrders.vue'),
                    meta: { title: 'Manage Orders' }
                },
                {
                    path: 'orders/:id',
                    name: 'admin-orders.show',
                    component: () => import('../views/admin/AdminOrderDetail.vue'),
                    meta: { title: 'Order Detail' }
                },
                {
                    path: 'tickets',
                    name: 'admin-tickets',
                    component: () => import('../views/admin/AdminTickets.vue'),
                    meta: { title: 'Manage Tickets' }
                },
                {
                    path: 'tickets/:id',
                    name: 'admin-tickets.show',
                    component: () => import('../views/admin/AdminTicketDetailFinal.vue'),
                    meta: { title: 'Ticket Detail' }
                },
            ]
        },
        {
            path: '/forgot-password',
            name: 'forgot-password',
            component: () => import('../views/Auth/ForgotPasswordView.vue'),
            meta: { title: 'Forgot Password' }
        },
        {
            path: '/reset-password',
            name: 'reset-password',
            component: () => import('../views/Auth/ResetPasswordView.vue'),
            meta: { title: 'Reset Password' }
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: { title: 'Register' }
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'not-found',
            component: NotFoundView,
            meta: { title: '404 Page Not Found' }
        }
    ]
});

import { useAuthStore } from '../stores/auth';

router.beforeEach(async (to, from, next) => {
    // Dynamic Page Title
    document.title = to.meta.title ? `${to.meta.title} - BlogMix` : 'BlogMix';

    const authStore = useAuthStore();

    // Generic Auth Protection
    if (to.meta.requiresAuth) {
        if (!authStore.isAuthenticated && !localStorage.getItem('token')) {
            return next({ name: 'login', query: { redirect: to.fullPath } });
        }
    }

    // Admin Route Protection
    if (to.path.startsWith('/admin')) {
        if (!authStore.isAuthenticated) {
            // Try to restore session if token exists
            if (localStorage.getItem('token')) {
                try {
                    await authStore.fetchUser();
                } catch (e) {
                    return next('/login');
                }
            } else {
                return next('/login');
            }
        }

        // Double check after potential fetch
        if (!authStore.user) {
            try { await authStore.fetchUser(); } catch (e) { return next('/login'); }
        }

        if (!authStore.isAdmin) {
            return next('/');
        }
    }

    next();
});

export default router;
