<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouterLink } from 'vue-router';
import { fetchPublicRooms } from '../api/site';
import RoomCard from '../components/RoomCard.vue';

const { t } = useI18n();
const featuredRooms = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const { data } = await fetchPublicRooms({ limit: 9 });
        featuredRooms.value = (data.data ?? data).slice(0, 9);
    } catch (e) {
        featuredRooms.value = [];
    } finally {
        loading.value = false;
    }
});

const features = [
    { key: 'clean', icon: '✨' },
    { key: 'local', icon: '🏛️' },
    { key: 'safe', icon: '🛡️' },
    { key: 'branches', icon: '📍' },
];

const stats = [
    { value: '3', labelKey: 'home.stat_branches' },
    { value: '40+', labelKey: 'home.stat_rooms' },
    { value: '15K+', labelKey: 'home.stat_guests' },
    { value: '4.8', labelKey: 'home.stat_rating' },
];

const testimonials = [
    {
        name: 'Sarah J.',
        country: 'Australia',
        rating: 5,
        quote: 'Spotlessly clean, the staff were so welcoming, and the breakfast was incredible. Will definitely return!',
    },
    {
        name: 'Khemra S.',
        country: 'Cambodia',
        rating: 5,
        quote: 'A real boutique experience. The Siem Reap branch is perfectly located for Angkor temple tours.',
    },
    {
        name: 'David L.',
        country: 'UK',
        rating: 4,
        quote: 'Excellent value for money. The online booking process was smooth and got confirmation in minutes.',
    },
];

const placeholderRooms = Array.from({ length: 9 }, (_, i) => ({
    id: i + 1,
    name: ['Deluxe Twin', 'Family Suite', 'Garden Bungalow', 'Royal King', 'Lotus Studio', 'Riverside Suite', 'Heritage Loft', 'Bamboo Cabin', 'Sunset Villa'][i],
    description: 'Spacious, naturally lit, and tastefully decorated room with modern amenities.',
    price_per_night: [45, 75, 95, 120, 60, 110, 85, 55, 140][i],
    capacity: [2, 4, 3, 2, 2, 3, 4, 2, 4][i],
}));
</script>

<template>
    <!-- HERO -->
    <section class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <img
                src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80"
                alt="Guest house hero"
                class="h-full w-full object-cover"
            />
            <div class="absolute inset-0 bg-deep-hero"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-brand-900/90 via-transparent to-transparent"></div>
        </div>

        <div class="container-page py-28 sm:py-36 lg:py-44">
            <div class="max-w-2xl text-white">
                <p class="inline-flex items-center gap-2 rounded-full border border-accent-400/40 bg-accent-500/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-accent-200 backdrop-blur-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                    {{ t('hero.tagline') }}
                </p>
                <h1 class="mt-5 font-serif text-4xl font-bold leading-tight drop-shadow-sm sm:text-5xl lg:text-6xl">
                    {{ t('hero.title') }}
                </h1>
                <p class="mt-6 max-w-xl text-lg text-gray-100/95 sm:text-xl">{{ t('hero.subtitle') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <RouterLink :to="{ name: 'booking' }" class="btn-primary text-base px-6 py-3">
                        {{ t('hero.cta_book') }}
                    </RouterLink>
                    <RouterLink :to="{ name: 'rooms' }" class="btn-ghost text-base px-6 py-3">
                        {{ t('hero.cta_explore') }}
                    </RouterLink>
                </div>

                <!-- Stats strip -->
                <dl class="mt-14 grid max-w-xl grid-cols-2 gap-x-8 gap-y-6 border-t border-white/20 pt-8 sm:grid-cols-4">
                    <div v-for="s in stats" :key="s.labelKey">
                        <dt class="font-serif text-3xl font-bold text-accent-300 sm:text-4xl">{{ s.value }}</dt>
                        <dd class="mt-1 text-xs uppercase tracking-wider text-gray-300">{{ t(s.labelKey) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="section relative overflow-hidden bg-warm-radial">
        <div class="container-page">
            <div class="mx-auto max-w-2xl text-center">
                <span class="eyebrow">{{ t('home.features_eyebrow') }}</span>
                <h2 class="mt-4 heading-2">{{ t('home.features_title') }}</h2>
                <p class="mt-3 text-gray-600">{{ t('home.features_subtitle') }}</p>
            </div>
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="f in features"
                    :key="f.key"
                    class="group relative rounded-2xl border border-gray-100 bg-white p-7 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-accent-300 hover:shadow-lg"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-accent-100 to-accent-200 text-3xl shadow-inner transition-transform duration-300 group-hover:scale-110">
                        {{ f.icon }}
                    </div>
                    <h3 class="mt-5 font-serif text-lg font-bold text-brand-500">
                        {{ t(`home.feature_${f.key}_title`) }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ t(`home.feature_${f.key}_desc`) }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED ROOMS -->
    <section class="section bg-white">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-end">
                <div>
                    <span class="eyebrow">{{ t('home.rooms_eyebrow') }}</span>
                    <h2 class="mt-4 heading-2">{{ t('home.rooms_title') }}</h2>
                    <p class="mt-2 max-w-2xl text-gray-600">{{ t('home.rooms_subtitle') }}</p>
                </div>
                <RouterLink
                    :to="{ name: 'rooms' }"
                    class="inline-flex items-center gap-1 rounded-full border border-brand-200 bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-300 hover:bg-brand-100"
                >
                    {{ t('home.view_all_rooms') }} <span aria-hidden="true">→</span>
                </RouterLink>
            </div>

            <div v-if="loading" class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 9" :key="i" class="aspect-[4/3] animate-pulse rounded-2xl bg-gray-100"></div>
            </div>

            <div v-else-if="featuredRooms.length" class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RoomCard v-for="room in featuredRooms" :key="room.id" :room="room" />
            </div>

            <div v-else class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RoomCard v-for="room in placeholderRooms" :key="room.id" :room="room" />
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="section relative overflow-hidden bg-brand-500 text-white">
        <div class="absolute inset-0 -z-0 opacity-30" style="background-image: radial-gradient(at 30% 20%, rgba(212,151,42,0.25) 0px, transparent 50%), radial-gradient(at 80% 80%, rgba(76,162,156,0.25) 0px, transparent 55%);"></div>
        <div class="container-page relative">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-accent-300">
                    {{ t('home.testimonials_eyebrow') }}
                </span>
                <h2 class="mt-4 font-serif text-3xl font-bold sm:text-4xl">{{ t('home.testimonials_title') }}</h2>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <figure
                    v-for="t in testimonials"
                    :key="t.name"
                    class="rounded-2xl bg-white/[0.06] p-7 ring-1 ring-white/10 backdrop-blur-sm transition-all duration-300 hover:bg-white/[0.1] hover:ring-accent-400/40"
                >
                    <svg class="h-7 w-7 text-accent-400/80" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z" />
                    </svg>
                    <blockquote class="mt-4 text-base leading-relaxed text-gray-100">"{{ t.quote }}"</blockquote>
                    <div class="mt-5 flex items-center justify-between">
                        <figcaption class="text-sm font-semibold text-white">
                            {{ t.name }}
                            <span class="font-normal text-gray-300">— {{ t.country }}</span>
                        </figcaption>
                        <div class="flex gap-0.5 text-accent-400">
                            <svg v-for="n in t.rating" :key="n" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                        </div>
                    </div>
                </figure>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section">
        <div class="container-page">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-accent-500 via-accent-600 to-accent-700 px-8 py-14 text-center text-white shadow-xl sm:px-12 sm:py-20">
                <div class="absolute inset-0 -z-0 opacity-20" style="background-image: radial-gradient(at 20% 30%, rgba(255,255,255,0.5) 0px, transparent 50%), radial-gradient(at 80% 70%, rgba(15,58,57,0.5) 0px, transparent 50%);"></div>
                <div class="relative">
                    <h2 class="font-serif text-3xl font-bold sm:text-4xl lg:text-5xl">{{ t('home.cta_title') }}</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-lg text-white/95">{{ t('home.cta_subtitle') }}</p>
                    <RouterLink
                        :to="{ name: 'booking' }"
                        class="mt-8 inline-flex items-center justify-center rounded-lg bg-white px-7 py-3 text-base font-bold text-accent-700 shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-gray-50 hover:shadow-lg"
                    >
                        {{ t('hero.cta_book') }}
                    </RouterLink>
                </div>
            </div>
        </div>
    </section>
</template>
