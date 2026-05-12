<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import RoomCard from '../components/RoomCard.vue';
import { fetchPublicRooms } from '../api/site';

const { t } = useI18n();
const rooms = ref([]);
const loading = ref(true);
const search = ref('');
const capacityFilter = ref('all');

onMounted(async () => {
    try {
        const { data } = await fetchPublicRooms();
        rooms.value = data.data ?? data;
    } catch (e) {
        rooms.value = [];
    } finally {
        loading.value = false;
    }
});

const filtered = computed(() => {
    return rooms.value.filter((r) => {
        const name = (r.name || r.room_type?.name || `Room ${r.room_no}`).toLowerCase();
        const matchesSearch = !search.value || name.includes(search.value.toLowerCase());
        const cap = r.capacity ?? r.room_type?.capacity ?? 2;
        const matchesCap =
            capacityFilter.value === 'all' ||
            (capacityFilter.value === '1' && cap <= 1) ||
            (capacityFilter.value === '2' && cap === 2) ||
            (capacityFilter.value === '3' && cap === 3) ||
            (capacityFilter.value === '4+' && cap >= 4);
        return matchesSearch && matchesCap;
    });
});

const placeholderRooms = [
    { id: 1, name: 'Deluxe Twin', description: 'Comfort and style, with natural light and modern bathroom amenities.', price_per_night: 45, capacity: 2 },
    { id: 2, name: 'Family Suite', description: 'Spacious suite for the whole family with separate living area.', price_per_night: 75, capacity: 4 },
    { id: 3, name: 'Garden Bungalow', description: 'Private bungalow overlooking the garden with a quiet outdoor terrace.', price_per_night: 95, capacity: 3 },
    { id: 4, name: 'Standard Single', description: 'Cozy single room with everything you need for a comfortable stay.', price_per_night: 28, capacity: 1 },
    { id: 5, name: 'Double Superior', description: 'Roomy double bed with city view, premium linens and tea/coffee maker.', price_per_night: 55, capacity: 2 },
    { id: 6, name: 'Royal Suite', description: 'Top-tier suite with king bed, lounge area, and bathroom of marble finish.', price_per_night: 150, capacity: 2 },
];

const displayRooms = computed(() => (rooms.value.length ? filtered.value : placeholderRooms));
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('rooms.title') }}</h1>
            <p class="mt-2 text-lg text-gray-200">{{ t('rooms.subtitle') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <!-- Filters -->
            <div class="mb-8 grid gap-3 rounded-lg border border-gray-200 bg-white p-4 sm:grid-cols-3 sm:p-6">
                <div>
                    <label class="label">Search</label>
                    <input v-model="search" type="search" placeholder="Room name..." class="input" />
                </div>
                <div>
                    <label class="label">{{ t('rooms.capacity') }}</label>
                    <select v-model="capacityFilter" class="input">
                        <option value="all">All</option>
                        <option value="1">1 {{ t('rooms.guests') }}</option>
                        <option value="2">2 {{ t('rooms.guests') }}</option>
                        <option value="3">3 {{ t('rooms.guests') }}</option>
                        <option value="4+">4+ {{ t('rooms.guests') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <p class="text-sm text-gray-500">
                        {{ displayRooms.length }} rooms
                    </p>
                </div>
            </div>

            <div v-if="loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 6" :key="i" class="aspect-[4/3] animate-pulse rounded-lg bg-gray-100"></div>
            </div>

            <div v-else-if="displayRooms.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RoomCard v-for="r in displayRooms" :key="r.id" :room="r" />
            </div>

            <div v-else class="py-16 text-center text-gray-500">
                {{ t('rooms.no_rooms') }}
            </div>
        </div>
    </section>
</template>
