import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../pages/HomePage.vue'),
        meta: { title: 'home' },
    },
    {
        path: '/rooms',
        name: 'rooms',
        component: () => import('../pages/RoomsPage.vue'),
        meta: { title: 'rooms' },
    },
    {
        path: '/rooms/:id',
        name: 'room-detail',
        component: () => import('../pages/RoomDetailPage.vue'),
        meta: { title: 'room_detail' },
        props: true,
    },
    {
        path: '/about',
        name: 'about',
        component: () => import('../pages/AboutPage.vue'),
        meta: { title: 'about' },
    },
    {
        path: '/services',
        name: 'services',
        component: () => import('../pages/ServicesPage.vue'),
        meta: { title: 'services' },
    },
    {
        path: '/gallery',
        name: 'gallery',
        component: () => import('../pages/GalleryPage.vue'),
        meta: { title: 'gallery' },
    },
    {
        path: '/contact',
        name: 'contact',
        component: () => import('../pages/ContactPage.vue'),
        meta: { title: 'contact' },
    },
    {
        path: '/booking',
        name: 'booking',
        component: () => import('../pages/BookingPage.vue'),
        meta: { title: 'book_now' },
    },
    {
        path: '/booking/success',
        name: 'booking-success',
        component: () => import('../pages/BookingSuccessPage.vue'),
        meta: { title: 'booking_success' },
    },
    {
        path: '/booking/status',
        name: 'booking-status',
        component: () => import('../pages/BookingStatusPage.vue'),
        meta: { title: 'booking_status' },
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/LoginPage.vue'),
        meta: { title: 'login' },
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('../pages/NotFoundPage.vue'),
        meta: { title: 'not_found' },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition;
        if (to.hash) return { el: to.hash, behavior: 'smooth' };
        return { top: 0, behavior: 'smooth' };
    },
});

router.afterEach((to) => {
    const baseTitle = 'Sokha Guest House';
    document.title = to.meta.title ? `${baseTitle} — ${to.meta.title}` : baseTitle;
});

export default router;
