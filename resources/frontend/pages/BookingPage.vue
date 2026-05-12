<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter, useRoute } from 'vue-router';
import { useSiteStore } from '../stores/site';
import { submitOnlineBooking } from '../api/site';

const { t } = useI18n();
const router = useRouter();
const route = useRoute();
const site = useSiteStore();

const today = new Date().toISOString().slice(0, 10);
const tomorrow = new Date(Date.now() + 86400000).toISOString().slice(0, 10);

const form = reactive({
    guest_name: '',
    email: '',
    phone: '',
    branch_id: '',
    room_type_id: '',
    check_in_date: today,
    check_out_date: tomorrow,
    adults: 2,
    children: 0,
    special_requests: '',
});

const submitting = ref(false);
const error = ref(null);
const fieldErrors = ref({});

onMounted(async () => {
    await Promise.all([site.loadBranches(), site.loadRoomTypes()]);
    if (route.query.room) {
        form.room_type_id = route.query.room;
    }
});

const nights = computed(() => {
    const a = new Date(form.check_in_date);
    const b = new Date(form.check_out_date);
    const diff = Math.round((b - a) / 86400000);
    return diff > 0 ? diff : 0;
});

const minCheckOut = computed(() => {
    if (!form.check_in_date) return today;
    const d = new Date(form.check_in_date);
    d.setDate(d.getDate() + 1);
    return d.toISOString().slice(0, 10);
});

async function submit() {
    if (nights.value < 1) {
        error.value = 'Check-out must be after check-in.';
        return;
    }
    submitting.value = true;
    error.value = null;
    fieldErrors.value = {};
    try {
        await submitOnlineBooking({ ...form });
        router.push({ name: 'booking-success' });
    } catch (e) {
        const status = e?.response?.status;
        if (status === 422) {
            fieldErrors.value = e.response.data.errors ?? {};
            error.value = e.response.data.message ?? t('common.error');
        } else {
            error.value = e?.response?.data?.message ?? t('common.error');
        }
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <section class="bg-gradient-to-b from-brand-500 to-brand-700 py-16 text-white">
        <div class="container-page">
            <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ t('booking.title') }}</h1>
            <p class="mt-2 text-lg text-gray-200">{{ t('booking.subtitle') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-page grid gap-10 lg:grid-cols-3">
            <form class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-6 sm:p-8" @submit.prevent="submit">
                <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                    {{ error }}
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">{{ t('booking.guest_name') }} *</label>
                        <input v-model="form.guest_name" type="text" required class="input" />
                        <p v-if="fieldErrors.guest_name" class="mt-1 text-xs text-red-600">{{ fieldErrors.guest_name[0] }}</p>
                    </div>
                    <div>
                        <label class="label">{{ t('booking.email') }} *</label>
                        <input v-model="form.email" type="email" required class="input" />
                        <p v-if="fieldErrors.email" class="mt-1 text-xs text-red-600">{{ fieldErrors.email[0] }}</p>
                    </div>
                    <div>
                        <label class="label">{{ t('booking.phone') }}</label>
                        <input v-model="form.phone" type="tel" class="input" />
                    </div>
                    <div>
                        <label class="label">{{ t('booking.branch') }}</label>
                        <select v-model="form.branch_id" class="input">
                            <option value="">—</option>
                            <option v-for="b in site.branches" :key="b.id" :value="b.id">{{ b.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">{{ t('booking.check_in') }} *</label>
                        <input v-model="form.check_in_date" type="date" :min="today" required class="input" />
                    </div>
                    <div>
                        <label class="label">{{ t('booking.check_out') }} *</label>
                        <input v-model="form.check_out_date" type="date" :min="minCheckOut" required class="input" />
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="label">{{ t('booking.adults') }} *</label>
                        <input v-model.number="form.adults" type="number" min="1" max="10" required class="input" />
                    </div>
                    <div>
                        <label class="label">{{ t('booking.children') }}</label>
                        <input v-model.number="form.children" type="number" min="0" max="10" class="input" />
                    </div>
                    <div>
                        <label class="label">{{ t('booking.room_type') }}</label>
                        <select v-model="form.room_type_id" class="input">
                            <option value="">—</option>
                            <option v-for="rt in site.roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="label">{{ t('booking.special_requests') }}</label>
                    <textarea v-model="form.special_requests" rows="3" class="input"></textarea>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full sm:w-auto" :disabled="submitting">
                    <span v-if="submitting">{{ t('common.loading') }}</span>
                    <span v-else>{{ t('booking.submit') }}</span>
                </button>
            </form>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-lg border border-gray-200 bg-brand-500 p-6 text-white">
                    <h3 class="font-serif text-xl font-bold">{{ t('booking.summary') }}</h3>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-300">{{ t('booking.check_in') }}</dt>
                            <dd>{{ form.check_in_date }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-300">{{ t('booking.check_out') }}</dt>
                            <dd>{{ form.check_out_date }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-white/10 pt-2">
                            <dt class="text-gray-300">{{ t('booking.nights') }}</dt>
                            <dd class="font-bold">{{ nights }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-300">{{ t('booking.adults') }}</dt>
                            <dd>{{ form.adults }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-300">{{ t('booking.children') }}</dt>
                            <dd>{{ form.children }}</dd>
                        </div>
                    </dl>
                </div>

                <RouterLink :to="{ name: 'booking-status' }" class="mt-4 block rounded-lg border border-gray-200 bg-white p-4 text-center text-sm text-gray-700 hover:bg-gray-50">
                    {{ t('booking.check_status') }} →
                </RouterLink>
            </aside>
        </div>
    </section>
</template>
