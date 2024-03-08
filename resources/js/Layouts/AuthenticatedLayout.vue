<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Toast from "@/Components/Toast.vue";
import ToggleMode from "@/Components/ToggleMode.vue";
import Footer from "@/Components/Footer.vue";
import LocaleChanger from "@/Components/LocaleChanger.vue";
import Loader from "@/Components/Loader.vue";

import {router, usePage} from '@inertiajs/vue3'
import {computed, ref} from 'vue';
import setMode from "@/Composables/setMode.js";
import {useStore} from "@/Composables/store.js";

const subscription = computed(() => usePage().props.auth.subscription)
if (usePage().props.auth.just_logged) {
    useStore().setToast('Just logged!');
}
const showingNavigationDropdown = ref(false);
setMode();

router.on('start', (event) => {
    useStore().setIsLoading(true)
})
router.on('success', (event) => {
    useStore().setIsLoading(false)
})
</script>

<template>
    <Toast/>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 largescreen">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                        />
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('subscribe.create')" :active="route().current('subscribe.create')"
                                     v-if="!subscription?.is_subscribed">
                                {{ $t('layout.subscription') }}
                            </NavLink>
                            <NavLink :href="route('app.projects')" :active="route().current('app.projects')"
                                     v-if="subscription?.is_subscribed">
                                {{ $t('app.projects') }}
                            </NavLink>
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                {{ $t('layout.dashboard') }}
                            </NavLink>
                        </div>
                    </div>

                    <div class="flex flex-row">
                        <LocaleChanger/>
                        <ToggleMode/>
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ms-2 -me-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <div class=" flex flex-row sm:hidden">
                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center ">
                                <button
                                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                                >
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            :class="{hidden: showingNavigationDropdown,
                                          'inline-flex': !showingNavigationDropdown,
                                          }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{hidden: !showingNavigationDropdown,
                                          'inline-flex': showingNavigationDropdown,
                                          }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                 class="sm:hidden smallscreen">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('subscribe.create')"
                                       :active="route().current('subscribe.create')"
                                       v-if="!(subscription?.is_subscribed)">
                        {{ $t('layout.subscription') }}
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('app.projects')" :active="route().current('app.projects')"
                                       v-if="subscription?.is_subscribed">
                        {{ $t('app.projects') }}
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        {{ $t('layout.dashboard') }}
                    </ResponsiveNavLink>
                </div>
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>

        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <slot name="header"/>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <Loader/>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg ">
                        <div class="p-6 space-y-5 text-gray-800 dark:text-gray-200 layout">
                            <slot/>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer/>
    </div>
</template>
