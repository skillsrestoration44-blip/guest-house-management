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
        const { data } = await fetchPublicRooms({ limit: 3 });
        featuredRooms.value = (data.data ?? data).slice(0, 3);
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
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900/80 via-brand-700/60 to-transparent"></div>
        </div>

        <div class="container-page py-24 sm:py-32 lg:py-40">
            <div class="max-w-2xl text-white">
                <p class="inline-block rounded-full bg-accent-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-accent-200">
                    {{ t('hero.tagline') }}
                </p>
                <h1 class="mt-4 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {{ t('hero.title') }}
                </h1>
                <p class="mt-6 max-w-xl text-lg text-gray-200">{{ t('hero.subtitle') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <RouterLink :to="{ name: 'booking' }" class="btn-primary">{{ t('hero.cta_book') }}</RouterLink>
                    <RouterLink :to="{ name: 'rooms' }" class="btn-outline bg-white/10 text-white hover:bg-white/20">
                        {{ t('hero.cta_explore') }}
                    </RouterLink>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="section bg-gray-50">
        <div class="container-page">
            <div class="text-center">
                <h2 class="heading-2">{{ t('home.features_title') }}</h2>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="f in features"
                    :key="f.key"
                    class="rounded-lg border border-gray-200 bg-white p-6 text-center transition-shadow hover:shadow-md"
                >
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent-100 text-2xl">
                        {{ f.icon }}
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-brand-500">
                        {{ t(`home.feature_${f.key}_title`) }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">{{ t(`home.feature_${f.key}_desc`) }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED ROOMS -->
    <section class="section">
        <div class="container-page">
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="heading-2">{{ t('home.rooms_title') }}</h2>
                    <p class="mt-2 text-gray-600">{{ t('home.rooms_subtitle') }}</p>
                </div>
                <RouterLink :to="{ name: 'rooms' }" class="hidden text-sm font-semibold text-brand-500 hover:text-accent-600 sm:inline-flex">
                    {{ t('home.view_all_rooms') }} →
                </RouterLink>
            </div>

            <div v-if="loading" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 3" :key="i" class="aspect-[4/3] animate-pulse rounded-lg bg-gray-100"></div>
            </div>

            <div v-else-if="featuredRooms.length" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RoomCard v-for="room in featuredRooms" :key="room.id" :room="room" />
            </div>

            <div v-else class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RoomCard v-for="i in 3" :key="i"
                          :room="{
                              id: i,
                              name: ['Deluxe Twin', 'Family Suite', 'Garden Bungalow'][i-1],
                              description: 'Spacious, naturally lit, and tastefully decorated room with modern amenities.',
                              price_per_night: [45, 75, 95][i-1],
                              capacity: [2, 4, 3][i-1],
                          }" />
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="section bg-brand-500 text-white">
        <div class="container-page">
            <h2 class="text-center font-serif text-3xl font-bold sm:text-4xl">{{ t('home.testimonials_title') }}</h2>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <figure v-for="t in testimonials" :key="t.name" class="rounded-lg bg-white/5 p-6 backdrop-blur-sm ring-1 ring-white/10">
                    <div class="flex gap-1 text-accent-400">
                        <svg v-for="n in t.rating" :key="n" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                        </svg>
                    </div>
                    <blockquote class="mt-3 text-base text-gray-100">"{{ t.quote }}"</blockquote>
                    <figcaption class="mt-4 text-sm text-gray-300">— {{ t.name }}, {{ t.country }}</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section">
        <div class="container-page">
            <div class="overflow-hidden rounded-2xl bg-gradient-to-r from-accent-500 to-accent-600 px-8 py-12 text-center text-white sm:px-12 sm:py-16">
                <h2 class="font-serif text-3xl font-bold sm:text-4xl">{{ t('home.cta_title') }}</h2>
                <p class="mt-2 text-lg text-white/90">{{ t('home.cta_subtitle') }}</p>
                <RouterLink :to="{ name: 'booking' }" class="mt-6 inline-block btn bg-white text-accent-600 hover:bg-gray-100">
                    {{ t('hero.cta_book') }}
                </RouterLink>
            </div>
        </div>
    </section>
</template>
