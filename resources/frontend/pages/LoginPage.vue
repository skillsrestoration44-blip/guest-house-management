<script setup>
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
</script>

<template>
    <section class="section">
        <div class="container-page max-w-md text-center">
            <h1 class="heading-2">{{ t('nav.login') }}</h1>
            <p class="mt-3 text-gray-600">
                Staff sign-in is handled by the secure admin portal.
            </p>
            <a href="/admin/login" class="btn-primary mt-6 inline-block">
                {{ t('nav.login') }} →
            </a>
        </div>
    </section>
</template>
