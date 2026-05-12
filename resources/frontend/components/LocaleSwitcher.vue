<script setup>
import { useI18n } from 'vue-i18n';
import { useSiteStore, } from '../stores/site';
import { getCurrentInstance } from 'vue';

const { locale } = useI18n();
const site = useSiteStore();
const inst = getCurrentInstance();

async function setLang(l) {
    if (locale.value === l) return;
    await site.setLocale(l, inst?.appContext?.config?.globalProperties?.$i18n ? { global: { locale } } : { global: { locale } });
    locale.value = l;
}
</script>

<template>
    <div class="inline-flex overflow-hidden rounded-md border border-gray-200 bg-white text-xs font-semibold">
        <button
            type="button"
            :class="['px-2.5 py-1.5 transition-colors', locale === 'en' ? 'bg-brand-500 text-white' : 'text-gray-700 hover:bg-gray-50']"
            @click="setLang('en')"
        >
            EN
        </button>
        <button
            type="button"
            :class="['px-2.5 py-1.5 transition-colors', locale === 'km' ? 'bg-brand-500 text-white' : 'text-gray-700 hover:bg-gray-50']"
            @click="setLang('km')"
        >
            ខ្មែរ
        </button>
    </div>
</template>
