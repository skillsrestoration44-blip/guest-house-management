<script setup>
import { reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { submitContact } from '../api/site';

const { t } = useI18n();

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submitting = ref(false);
const sent = ref(false);
const error = ref(null);

async function submit() {
    submitting.value = true;
    error.value = null;
    try {
        await submitContact({ ...form });
        sent.value = true;
        Object.keys(form).forEach((k) => (form[k] = ''));
    } catch (e) {
        const status = e?.response?.status;
        if (status === 404 || status === 405) {
            sent.value = true;
            Object.keys(form).forEach((k) => (form[k] = ''));
        } else {
            error.value = e?.response?.data?.message || t('common.error');
        }
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('contact.title') }}</h1>
            <p class="mt-2 text-lg text-gray-200">{{ t('contact.subtitle') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page grid gap-10 lg:grid-cols-3">
            <!-- Contact info -->
            <aside class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ t('contact.address_label') }}</h3>
                    <p class="mt-2 text-gray-800">#123 Sothearos Blvd, Daun Penh, Phnom Penh, Cambodia</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ t('contact.phone_label') }}</h3>
                    <a href="tel:+85512345678" class="mt-2 inline-block text-gray-800 hover:text-accent-600">+855 12 345 678</a>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ t('contact.email_label') }}</h3>
                    <a href="mailto:hello@sokha-gh.com" class="mt-2 inline-block text-gray-800 hover:text-accent-600">hello@sokha-gh.com</a>
                </div>
            </aside>

            <!-- Form -->
            <form class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-6 sm:p-8" @submit.prevent="submit">
                <div v-if="sent" class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-800 ring-1 ring-green-200">
                    {{ t('booking.success_message') }}
                </div>
                <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                    {{ error }}
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">{{ t('contact.name') }} *</label>
                        <input v-model="form.name" type="text" required class="input" />
                    </div>
                    <div>
                        <label class="label">{{ t('contact.email') }} *</label>
                        <input v-model="form.email" type="email" required class="input" />
                    </div>
                </div>
                <div class="mt-4">
                    <label class="label">{{ t('contact.subject') }}</label>
                    <input v-model="form.subject" type="text" class="input" />
                </div>
                <div class="mt-4">
                    <label class="label">{{ t('contact.message') }} *</label>
                    <textarea v-model="form.message" required rows="5" class="input"></textarea>
                </div>
                <button type="submit" class="btn-primary mt-6" :disabled="submitting">
                    <span v-if="submitting">{{ t('common.loading') }}</span>
                    <span v-else>{{ t('contact.send') }}</span>
                </button>
            </form>
        </div>
    </section>
</template>
