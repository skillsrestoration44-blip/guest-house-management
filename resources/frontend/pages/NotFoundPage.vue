<script setup>
import { RouterLink } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
</script>

<template>
    <section class="section">
        <div class="container-page text-center">
            <p class="font-serif text-9xl font-bold text-accent-500">404</p>
            <h1 class="mt-4 heading-2">Page not found</h1>
            <p class="mt-3 text-gray-600">The page you're looking for doesn't exist or has been moved.</p>
            <RouterLink :to="{ name: 'home' }" class="btn-primary mt-8 inline-block">
                {{ t('nav.home') }}
            </RouterLink>
        </div>
    </section>
</template>
