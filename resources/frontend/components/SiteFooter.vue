<script setup>
import { useI18n } from 'vue-i18n';
import { RouterLink } from 'vue-router';

const { t } = useI18n();
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-brand-500 text-gray-300">
        <div class="container-page py-12">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="flex items-center gap-2 text-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-accent-500">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                <path d="M3 11l9-7 9 7M5 10v10h4v-6h6v6h4V10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="font-serif text-lg font-bold">Sokha Guest House</span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed">{{ t('footer.about_short') }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-white">
                        {{ t('footer.quick_links') }}
                    </h4>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><RouterLink :to="{ name: 'rooms' }" class="hover:text-accent-400">{{ t('nav.rooms') }}</RouterLink></li>
                        <li><RouterLink :to="{ name: 'services' }" class="hover:text-accent-400">{{ t('nav.services') }}</RouterLink></li>
                        <li><RouterLink :to="{ name: 'gallery' }" class="hover:text-accent-400">{{ t('nav.gallery') }}</RouterLink></li>
                        <li><RouterLink :to="{ name: 'about' }" class="hover:text-accent-400">{{ t('nav.about') }}</RouterLink></li>
                        <li><RouterLink :to="{ name: 'contact' }" class="hover:text-accent-400">{{ t('nav.contact') }}</RouterLink></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-white">
                        {{ t('footer.contact_info') }}
                    </h4>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 flex-none text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>#123 Sothearos Blvd, Phnom Penh, Cambodia</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-none text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+85512345678" class="hover:text-accent-400">+855 12 345 678</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-none text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:hello@sokha-gh.com" class="hover:text-accent-400">hello@sokha-gh.com</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-white">
                        {{ t('footer.follow_us') }}
                    </h4>
                    <div class="mt-4 flex gap-3">
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-accent-500" aria-label="Facebook">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.378 14.192 5 15.115 5H18V0h-3.808C10.596 0 9 1.583 9 4.615V8z" />
                            </svg>
                        </a>
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-accent-500" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.42.56.21.96.47 1.38.89.42.42.68.82.89 1.38.17.42.37 1.06.42 2.23.06 1.27.07 1.65.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.42 2.23-.21.56-.47.96-.89 1.38-.42.42-.82.68-1.38.89-.42.17-1.06.37-2.23.42-1.27.06-1.65.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.42-.56-.21-.96-.47-1.38-.89-.42-.42-.68-.82-.89-1.38-.17-.42-.37-1.06-.42-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.42-2.23.21-.56.47-.96.89-1.38.42-.42.82-.68 1.38-.89.42-.17 1.06-.37 2.23-.42C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.51 0-4.76.07-.99.04-1.53.21-1.89.35-.48.18-.82.4-1.18.76-.36.36-.58.7-.76 1.18-.14.36-.31.9-.35 1.89-.07 1.25-.07 1.61-.07 4.76s0 3.51.07 4.76c.04.99.21 1.53.35 1.89.18.48.4.82.76 1.18.36.36.7.58 1.18.76.36.14.9.31 1.89.35 1.25.07 1.61.07 4.76.07s3.51 0 4.76-.07c.99-.04 1.53-.21 1.89-.35.48-.18.82-.4 1.18-.76.36-.36.58-.7.76-1.18.14-.36.31-.9.35-1.89.07-1.25.07-1.61.07-4.76s0-3.51-.07-4.76c-.04-.99-.21-1.53-.35-1.89-.18-.48-.4-.82-.76-1.18-.36-.36-.7-.58-1.18-.76-.36-.14-.9-.31-1.89-.35C15.51 4 15.15 4 12 4zm0 3.07a4.93 4.93 0 110 9.86 4.93 4.93 0 010-9.86zm0 1.8a3.13 3.13 0 100 6.26 3.13 3.13 0 000-6.26zm5.13-1.95a1.15 1.15 0 110 2.3 1.15 1.15 0 010-2.3z" />
                            </svg>
                        </a>
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-accent-500" aria-label="Twitter">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 5.8c-.74.33-1.53.55-2.36.65a4.12 4.12 0 001.8-2.27 8.22 8.22 0 01-2.6 1A4.1 4.1 0 0011.85 9a11.65 11.65 0 01-8.45-4.28 4.1 4.1 0 001.27 5.47 4.07 4.07 0 01-1.86-.51v.05a4.1 4.1 0 003.29 4.02 4.13 4.13 0 01-1.85.07 4.1 4.1 0 003.83 2.85A8.23 8.23 0 012 18.4a11.62 11.62 0 006.29 1.84c7.55 0 11.68-6.25 11.68-11.67l-.01-.53A8.36 8.36 0 0022 5.8z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-6 text-center text-sm text-gray-400">
                &copy; {{ year }} Sokha Guest House. {{ t('footer.rights') }}
            </div>
        </div>
    </footer>
</template>
