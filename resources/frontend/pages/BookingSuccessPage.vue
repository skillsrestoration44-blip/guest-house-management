<script setup>
import { useI18n } from 'vue-i18n';
import { RouterLink } from 'vue-router';
const { t } = useI18n();
</script>

<template>
    <section class="section">
        <div class="container-page max-w-2xl text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="mt-6 heading-2">{{ t('booking.success_title') }}</h1>
            <p class="mt-3 text-lg text-gray-600">{{ t('booking.success_message') }}</p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <RouterLink :to="{ name: 'home' }" class="btn-outline">{{ t('nav.home') }}</RouterLink>
                <RouterLink :to="{ name: 'booking-status' }" class="btn-primary">{{ t('booking.check_status') }}</RouterLink>
            </div>
        </div>
    </section>
</template>
