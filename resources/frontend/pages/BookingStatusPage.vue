<script setup>
import { reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { lookupOnlineBooking } from '../api/site';

const { t } = useI18n();

const form = reactive({ booking_no: '', email: '' });
const result = ref(null);
const submitting = ref(false);
const error = ref(null);

const statusMap = {
    pending: { key: 'status_pending', cls: 'bg-yellow-100 text-yellow-800' },
    confirmed: { key: 'status_confirmed', cls: 'bg-green-100 text-green-800' },
    rejected: { key: 'status_rejected', cls: 'bg-red-100 text-red-800' },
    converted: { key: 'status_converted', cls: 'bg-blue-100 text-blue-800' },
};

async function lookup() {
    submitting.value = true;
    error.value = null;
    result.value = null;
    try {
        const { data } = await lookupOnlineBooking({ ...form });
        result.value = data.data ?? data;
    } catch (e) {
        const status = e?.response?.status;
        if (status === 404) error.value = 'No matching booking found.';
        else error.value = e?.response?.data?.message ?? t('common.error');
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('booking.check_status') }}</h1>
            <p class="mt-2 text-lg text-gray-200">{{ t('booking.check_status_subtitle') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page max-w-2xl">
            <form class="rounded-lg border border-gray-200 bg-white p-6 sm:p-8" @submit.prevent="lookup">
                <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                    {{ error }}
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">{{ t('booking.booking_no') }} *</label>
                        <input v-model="form.booking_no" type="text" required class="input" placeholder="OB-000001" />
                    </div>
                    <div>
                        <label class="label">{{ t('booking.email') }} *</label>
                        <input v-model="form.email" type="email" required class="input" />
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-6" :disabled="submitting">
                    <span v-if="submitting">{{ t('common.loading') }}</span>
                    <span v-else>{{ t('booking.find') }}</span>
                </button>
            </form>

            <div v-if="result" class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
                <h3 class="font-serif text-2xl font-bold text-brand-500">{{ result.booking_no || form.booking_no }}</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Guest</span>
                        <span class="font-medium">{{ result.guest_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ t('booking.check_in') }}</span>
                        <span>{{ result.check_in_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ t('booking.check_out') }}</span>
                        <span>{{ result.check_out_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span :class="['badge', statusMap[result.status]?.cls || 'bg-gray-100 text-gray-800']">
                            {{ t(`booking.${statusMap[result.status]?.key || 'status_pending'}`) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
