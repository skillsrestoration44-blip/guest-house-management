<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouterLink, useRoute } from 'vue-router';
import { useSiteStore } from '../stores/site';
import LocaleSwitcher from './LocaleSwitcher.vue';

const { t } = useI18n();
const route = useRoute();
const site = useSiteStore();

const mobileOpen = ref(false);
const scrolled = ref(false);

const navLinks = computed(() => [
    { to: { name: 'home' }, label: t('nav.home') },
    { to: { name: 'rooms' }, label: t('nav.rooms') },
    { to: { name: 'services' }, label: t('nav.services') },
    { to: { name: 'gallery' }, label: t('nav.gallery') },
    { to: { name: 'about' }, label: t('nav.about') },
    { to: { name: 'contact' }, label: t('nav.contact') },
]);

function onScroll() {
    scrolled.value = window.scrollY > 8;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <header
        :class="[
            'sticky top-0 z-50 w-full transition-all duration-200',
            scrolled || mobileOpen
                ? 'bg-white/95 shadow-sm backdrop-blur'
                : 'bg-white/80 backdrop-blur',
        ]"
    >
        <div class="container-page flex h-16 items-center justify-between gap-4">
            <RouterLink :to="{ name: 'home' }" class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-md bg-brand-500 text-white">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                        <path
                            d="M3 11l9-7 9 7M5 10v10h4v-6h6v6h4V10"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
                <span class="font-serif text-lg font-bold text-brand-500">Sokha Guest House</span>
            </RouterLink>

            <nav class="hidden items-center gap-1 lg:flex">
                <RouterLink
                    v-for="link in navLinks"
                    :key="link.label"
                    :to="link.to"
                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-brand-500"
                    active-class="text-accent-600"
                >
                    {{ link.label }}
                </RouterLink>
            </nav>

            <div class="flex items-center gap-2">
                <LocaleSwitcher />
                <RouterLink :to="{ name: 'booking' }" class="btn-primary hidden sm:inline-flex">
                    {{ t('nav.book_now') }}
                </RouterLink>
                <a href="/admin/login" class="hidden text-sm font-medium text-gray-600 hover:text-brand-500 sm:inline">
                    {{ t('nav.login') }}
                </a>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 lg:hidden"
                    aria-label="Toggle menu"
                    @click="mobileOpen = !mobileOpen"
                >
                    <svg v-if="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <Transition name="slide-down">
            <div v-if="mobileOpen" class="border-t border-gray-200 bg-white lg:hidden">
                <nav class="container-page flex flex-col py-3">
                    <RouterLink
                        v-for="link in navLinks"
                        :key="link.label"
                        :to="link.to"
                        class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                        active-class="bg-gray-50 text-accent-600"
                        @click="mobileOpen = false"
                    >
                        {{ link.label }}
                    </RouterLink>
                    <RouterLink
                        :to="{ name: 'booking' }"
                        class="btn-primary mt-2 w-full"
                        @click="mobileOpen = false"
                    >
                        {{ t('nav.book_now') }}
                    </RouterLink>
                    <a
                        href="/admin/login"
                        class="mt-2 rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
                    >
                        {{ t('nav.login') }}
                    </a>
                </nav>
            </div>
        </Transition>
    </header>
</template>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.18s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
