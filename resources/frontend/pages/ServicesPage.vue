<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { fetchPublicServices } from '../api/site';

const { t } = useI18n();
const services = ref([]);
const loading = ref(true);

const fallback = [
    { id: 1, name: 'Airport Pickup', description: 'Comfortable transfer from PNH/REP airport. 24/7 service.', price: 15, icon: '🚐' },
    { id: 2, name: 'Laundry', description: 'Same-day laundry service with eco-friendly detergents.', price: 5, icon: '🧺' },
    { id: 3, name: 'Breakfast Buffet', description: 'Authentic Khmer + Continental options served 6:30–10:00 AM.', price: 8, icon: '🍳' },
    { id: 4, name: 'Spa & Massage', description: 'Traditional Khmer massage by certified therapists.', price: 25, icon: '💆' },
    { id: 5, name: 'City Tour', description: 'Half-day guided tour with our local cultural experts.', price: 40, icon: '🗺️' },
    { id: 6, name: 'Bicycle Rental', description: 'Explore the neighborhood at your own pace.', price: 3, icon: '🚲' },
];

const icons = ['🛎️', '🧹', '🍽️', '🚐', '💆', '🗺️', '🛏️', '🧺'];

onMounted(async () => {
    try {
        const { data } = await fetchPublicServices();
        services.value = (data.data ?? data).map((s, i) => ({ ...s, icon: icons[i % icons.length] }));
    } catch {
        services.value = [];
    } finally {
        loading.value = false;
    }
});

const display = () => (services.value.length ? services.value : fallback);
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('services.title') }}</h1>
            <p class="mt-2 text-lg text-gray-200">{{ t('services.subtitle') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div v-if="loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 6" :key="i" class="h-44 animate-pulse rounded-lg bg-gray-100"></div>
            </div>

            <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="s in display()"
                    :key="s.id"
                    class="rounded-lg border border-gray-200 bg-white p-6 transition-shadow hover:shadow-md"
                >
                    <div class="text-4xl">{{ s.icon }}</div>
                    <h3 class="mt-4 font-serif text-xl font-bold text-brand-500">{{ s.name }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ s.description }}</p>
                    <p class="mt-3 text-base font-semibold text-accent-500">${{ Number(s.price ?? 0).toFixed(2) }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
