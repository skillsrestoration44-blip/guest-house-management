import { defineStore } from 'pinia';
import { ref } from 'vue';
import { fetchPublicBranches, fetchPublicRoomTypes, switchLocale as apiSwitchLocale } from '../api/site';

export const useSiteStore = defineStore('site', () => {
    const branches = ref([]);
    const roomTypes = ref([]);
    const loading = ref(false);
    const locale = ref(document.documentElement.lang || 'en');

    async function loadBranches() {
        if (branches.value.length) return;
        try {
            const { data } = await fetchPublicBranches();
            branches.value = data.data ?? data;
        } catch (e) {
            console.warn('loadBranches failed', e);
        }
    }

    async function loadRoomTypes() {
        if (roomTypes.value.length) return;
        try {
            const { data } = await fetchPublicRoomTypes();
            roomTypes.value = data.data ?? data;
        } catch (e) {
            console.warn('loadRoomTypes failed', e);
        }
    }

    async function setLocale(newLocale, i18n) {
        if (locale.value === newLocale) return;
        try {
            await apiSwitchLocale(newLocale);
        } catch (e) {
            console.warn('switchLocale API failed (continuing client-only)', e);
        }
        locale.value = newLocale;
        document.documentElement.lang = newLocale;
        document.cookie = `locale=${newLocale};path=/;max-age=31536000`;
        if (i18n?.global) {
            i18n.global.locale.value = newLocale;
        }
    }

    return { branches, roomTypes, loading, locale, loadBranches, loadRoomTypes, setLocale };
});
