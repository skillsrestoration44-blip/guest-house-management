import { createI18n } from 'vue-i18n';
import en from './en.js';
import km from './km.js';

function detectLocale() {
    const cookieLocale = document.cookie
        .split('; ')
        .find((row) => row.startsWith('locale='))
        ?.split('=')[1];
    if (cookieLocale === 'km' || cookieLocale === 'en') return cookieLocale;

    const meta = document.querySelector('meta[name="locale"]');
    if (meta?.content === 'km' || meta?.content === 'en') return meta.content;

    return 'en';
}

const i18n = createI18n({
    legacy: false,
    locale: detectLocale(),
    fallbackLocale: 'en',
    messages: { en, km },
});

export default i18n;
