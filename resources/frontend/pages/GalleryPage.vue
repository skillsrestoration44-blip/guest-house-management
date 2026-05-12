<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const images = Array.from({ length: 12 }, (_, i) => ({
    id: i,
    src: `https://picsum.photos/seed/gallery-${i}/600/400`,
    alt: `Gallery ${i + 1}`,
}));

const lightbox = ref(null);
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('nav.gallery') }}</h1>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                <button
                    v-for="img in images"
                    :key="img.id"
                    type="button"
                    class="group relative aspect-square overflow-hidden rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-accent-500"
                    @click="lightbox = img.src"
                >
                    <img :src="img.src" :alt="img.alt" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                </button>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="lightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4" @click="lightbox = null">
                    <img :src="lightbox" class="max-h-full max-w-full rounded-lg shadow-2xl" alt="" @click.stop />
                    <button type="button" class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" @click="lightbox = null">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
