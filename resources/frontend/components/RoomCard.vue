<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { RouterLink } from 'vue-router';

const props = defineProps({
    room: { type: Object, required: true },
});

const { t } = useI18n();

const imageUrl = computed(() => {
    return props.room.image_url || `https://picsum.photos/seed/room-${props.room.id || 1}/640/420`;
});

const price = computed(() => {
    const p = props.room.price_per_night ?? props.room.room_type?.base_price ?? 0;
    return Number(p).toFixed(2);
});

const capacity = computed(() => props.room.capacity ?? props.room.room_type?.capacity ?? 2);
const name = computed(() => props.room.name ?? props.room.room_type?.name ?? `Room ${props.room.room_no}`);
const desc = computed(() => props.room.description ?? props.room.room_type?.description ?? '');
</script>

<template>
    <article class="card group">
        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
            <img
                :src="imageUrl"
                :alt="name"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <span class="absolute right-3 top-3 badge bg-accent-500 text-white">
                ${{ price }} <span class="opacity-80">{{ t('rooms.per_night') }}</span>
            </span>
        </div>
        <div class="p-5">
            <h3 class="font-serif text-xl font-bold text-brand-500">{{ name }}</h3>
            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ desc }}</p>
            <div class="mt-3 flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                    <span>{{ capacity }} {{ t('rooms.guests') }}</span>
                </div>
                <div class="flex gap-2">
                    <RouterLink :to="{ name: 'room-detail', params: { id: room.id } }" class="text-sm font-semibold text-brand-500 hover:text-accent-600">
                        {{ t('rooms.view_details') }} →
                    </RouterLink>
                </div>
            </div>
        </div>
    </article>
</template>
