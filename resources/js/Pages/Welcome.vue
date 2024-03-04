<script setup>
import {Head} from '@inertiajs/vue3';
import {trans} from "laravel-vue-i18n";
import OutLayout from "@/Layouts/OutLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head v-bind:title="trans('Welcome')"/>
    <OutLayout v-bind:laravel-version="laravelVersion"
               v-bind:php-version="phpVersion"
               v-bind:can-login="canLogin"
               v-bind:can-register="canRegister"
    >

        <div class="flex justify-center">
            <img src="/img/icon.svg" width="100" alt="">
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <a href="https://laravel.com/docs"
                   class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
                    <div class="w-11/12">
                        <div
                            class="h-16 w-16 bg-gray-800 dark:bg-gray-100 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-ice-cream-2 stroke-cyan-600">
                                <path
                                    d="M12 17c5 0 8-2.69 8-6H4c0 3.31 3 6 8 6Zm-4 4h8m-4-3v3M5.14 11a3.5 3.5 0 1 1 6.71 0"/>
                                <path d="M12.14 11a3.5 3.5 0 1 1 6.71 0"/>
                                <path d="M15.5 6.5a3.5 3.5 0 1 0-7 0"/>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Feature benefit
                            1</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Explain your feature benefit here. Explain your feature benefit here. Explain your
                            feature
                            benefit here. Explain your feature benefit here. Explain your feature benefit here.
                        </p>
                    </div>
                    <div class="w-1/12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path
                                d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.155.75.75 0 0 0 0-1.114A28.897 28.897 0 0 0 3.105 2.288Z"/>
                        </svg>
                    </div>
                </a>

                <a href="https://laracasts.com"
                   class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
                    <div class="w-11/12">
                        <div
                            class="h-16 w-16 bg-gray-800 dark:bg-gray-100 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-file-cog stroke-cyan-600">
                                <path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2"/>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                                <circle cx="6" cy="14" r="3"/>
                                <path d="M6 10v1"/>
                                <path d="M6 17v1"/>
                                <path d="M10 14H9"/>
                                <path d="M3 14H2"/>
                                <path d="m9 11-.88.88"/>
                                <path d="M3.88 16.12 3 17"/>
                                <path d="m9 17-.88-.88"/>
                                <path d="M3.88 11.88 3 11"/>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Feature benefit
                            2</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Explain your feature benefit here. Explain your feature benefit here. Explain your
                            feature
                            benefit here. Explain your feature benefit here. Explain your feature benefit here.
                        </p>
                    </div>
                    <div class="w-1/12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path
                                d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.155.75.75 0 0 0 0-1.114A28.897 28.897 0 0 0 3.105 2.288Z"/>
                        </svg>
                    </div>
                </a>

                <a href="https://laravel-news.com"
                   class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
                    <div class="w-11/12">
                        <div
                            class="h-16 w-16 bg-gray-800 dark:bg-gray-100 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-globe-2 stroke-cyan-600">
                                <path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"/>
                                <path
                                    d="M7 3.34V5a3 3 0 0 0 3 3v0a2 2 0 0 1 2 2v0c0 1.1.9 2 2 2v0a2 2 0 0 0 2-2v0c0-1.1.9-2 2-2h3.17"/>
                                <path d="M11 21.95V18a2 2 0 0 0-2-2v0a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Feature benefit
                            3</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Explain your feature benefit here. Explain your feature benefit here. Explain your
                            feature
                            benefit here. Explain your feature benefit here. Explain your feature benefit here.
                        </p>
                    </div>
                    <div class="w-1/12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path
                                d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.155.75.75 0 0 0 0-1.114A28.897 28.897 0 0 0 3.105 2.288Z"/>
                        </svg>
                    </div>
                </a>

                <div
                    class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
                    <div>
                        <div
                            class="h-16 w-16 bg-gray-800 dark:bg-gray-100 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-flame-kindling stroke-cyan-600">
                                <path
                                    d="M12 2c1 3 2.5 3.5 3.5 4.5A5 5 0 0 1 17 10a5 5 0 1 1-10 0c0-.3 0-.6.1-.9a2 2 0 1 0 3.3-2C8 4.5 11 2 12 2Z"/>
                                <path d="m5 22 14-4"/>
                                <path d="m5 18 14 4"/>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Feature benefit
                            4</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Explain your feature benefit here. Explain your feature benefit here. Explain your
                            feature
                            benefit here. Explain your feature benefit here. Explain your feature benefit here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </OutLayout>
</template>
