<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, RouterLink } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { fetchPublicRoom } from '../api/site';

const { t } = useI18n();
const route = useRoute();
const room = ref(null);
const loading = ref(true);
const error = ref(null);

async function load() {
    loading.value = true;
    error.value = null;
    try {
        const { data } = await fetchPublicRoom(route.params.id);
        room.value = data.data ?? data;
    } catch (e) {
        error.value = e?.response?.status === 404 ? 'Room not found' : 'Failed to load room.';
    } finally {
        loading.value = false;
    }
}

onMounted(load);
watch(() => route.params.id, load);

const gallery = computed(() => {
    if (!room.value) return [];
    const id = room.value.id;
    return [
        `https://picsum.photos/seed/room-${id}-a/1200/700`,
        `https://picsum.photos/seed/room-${id}-b/600/400`,
        `https://picsum.photos/seed/room-${id}-c/600/400`,
        `https://picsum.photos/seed/room-${id}-d/600/400`,
    ];
});

const fallback = computed(() => ({
    id: route.params.id,
    name: `Deluxe Room ${route.params.id}`,
    description: 'A beautifully appointed room with thoughtful design and modern amenities. Spacious layout, premium linens, ensuite bathroom and free Wi-Fi. Perfect for a relaxing stay.',
    price_per_night: 65,
    capacity: 2,
    amenities: ['Free Wi-Fi', 'Air Conditioning', 'Hot Shower', 'TV', 'Mini Fridge', 'Daily Housekeeping', 'In-room Safe', 'Tea/Coffee'],
}));

const r = computed(() => room.value || fallback.value);
</script>

<template>
    <section v-if="loading" class="section">
        <div class="container-page animate-pulse">
            <div class="h-96 rounded-lg bg-gray-100"></div>
            <div class="mt-6 h-8 w-1/3 rounded bg-gray-100"></div>
        </div>
    </section>

    <section v-else class="section">
        <div class="container-page">
            <RouterLink :to="{ name: 'rooms' }" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-500">
                ← {{ t('common.back') }}
            </RouterLink>

            <div class="grid gap-4 sm:grid-cols-4 sm:grid-rows-2">
                <img :src="gallery[0]" class="col-span-4 row-span-2 aspect-[16/9] w-full rounded-lg object-cover sm:col-span-2 sm:aspect-auto sm:h-full" alt="" />
                <img :src="gallery[1]" class="hidden h-full w-full rounded-lg object-cover sm:block" alt="" />
                <img :src="gallery[2]" class="hidden h-full w-full rounded-lg object-cover sm:block" alt="" />
                <img :src="gallery[3]" class="hidden h-full w-full rounded-lg object-cover sm:col-span-2 sm:block" alt="" />
            </div>

            <div class="mt-10 grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h1 class="heading-1">{{ r.name || r.room_type?.name }}</h1>
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87a4 4 0 110-8 4 4 0 010 8z" />
                            </svg>
                            {{ r.capacity ?? r.room_type?.capacity ?? 2 }} {{ t('rooms.guests') }}
                        </span>
                    </div>

                    <h2 class="mt-8 font-serif text-xl font-bold text-brand-500">{{ t('rooms.description') }}</h2>
                    <p class="mt-2 leading-relaxed text-gray-700">{{ r.description || r.room_type?.description }}</p>

                    <h2 class="mt-8 font-serif text-xl font-bold text-brand-500">{{ t('rooms.amenities') }}</h2>
                    <ul class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-700 sm:grid-cols-3">
                        <li v-for="a in (r.amenities || fallback.amenities)" :key="a" class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                            {{ a }}
                        </li>
                    </ul>
                </div>

                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-accent-500">
                                ${{ Number(r.price_per_night ?? r.room_type?.base_price ?? 0).toFixed(2) }}
                            </span>
                            <span class="text-gray-500">{{ t('rooms.per_night') }}</span>
                        </div>
                        <RouterLink :to="{ name: 'booking', query: { room: r.id } }" class="btn-primary mt-4 w-full">
                            {{ t('rooms.book') }}
                        </RouterLink>
                        <RouterLink :to="{ name: 'contact' }" class="btn-outline mt-2 w-full">
                            {{ t('nav.contact') }}
                        </RouterLink>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</template>
