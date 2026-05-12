<script setup>
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const values = ['quality', 'hospitality', 'integrity', 'community'];
const stats = [
    { value: '15+', label: 'years of service' },
    { value: '3', label: 'branches' },
    { value: '120+', label: 'rooms' },
    { value: 'ISO 9001', label: 'aligned' },
];
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('about.title') }}</h1>
            <p class="mt-2 max-w-2xl text-lg text-gray-200">{{ t('about.intro') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:items-center">
            <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=80"
                 alt="Lobby"
                 class="aspect-[4/3] w-full rounded-lg object-cover shadow-lg" />
            <div>
                <h2 class="heading-2">{{ t('about.mission_title') }}</h2>
                <p class="mt-4 text-gray-700">{{ t('about.mission') }}</p>

                <h2 class="heading-2 mt-10">{{ t('about.vision_title') }}</h2>
                <p class="mt-4 text-gray-700">{{ t('about.vision') }}</p>
            </div>
        </div>
    </section>

    <section class="section bg-gray-50">
        <div class="container-page">
            <h2 class="heading-2 text-center">{{ t('about.values_title') }}</h2>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="v in values" :key="v" class="rounded-lg border border-gray-200 bg-white p-6 text-center">
                    <h3 class="font-serif text-xl font-bold text-accent-500">{{ t(`about.value_${v}`) }}</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-brand-500 text-white">
        <div class="container-page grid gap-8 text-center sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="s in stats" :key="s.label">
                <p class="font-serif text-4xl font-bold text-accent-400">{{ s.value }}</p>
                <p class="mt-1 text-sm uppercase tracking-wider text-gray-300">{{ s.label }}</p>
            </div>
        </div>
    </section>
</template>
