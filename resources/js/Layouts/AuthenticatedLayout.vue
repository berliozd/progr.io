<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Toast from "@/Components/Toast.vue";
import Footer from "@/Components/Footer.vue";
import LocaleChanger from "@/Components/LocaleChanger.vue";
import Loader from "@/Components/Loader.vue";

import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref} from 'vue';
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";

const subscription = computed(() => usePage().props.auth.subscription)
if (usePage().props.errors.msg) {
    useStore().setToast(trans(usePage().props.errors.msg), true);
}
const showingNavigationDropdown = ref(false);
</script>

<template>
    <Toast/>
    <div class="min-h-screen bg-base-300">
        <nav class="sticky top-0 z-30 border-b border-base-300 bg-base-100/90 backdrop-blur">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <ApplicationLogo class="block h-9 w-auto fill-current"/>
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('app.projects')" :active="route().current('app.projects')">
                                {{ $t('app.projects') }}
                            </NavLink>
                            <NavLink :href="route('app.ideas')" :active="route().current('app.ideas')">
                                {{ $t('app.ideas.ideas_generator') }}
                            </NavLink>
                            <a :href="route('app.ideas.catalog')"
                               class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-base-content/60 hover:text-base-content hover:border-base-300 transition duration-150 ease-in-out">
                                {{ $t('app.ideas.catalog.ideas_catalog') }}
                            </a>
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                {{ $t('layout.dashboard') }}
                            </NavLink>
                        </div>
                    </div>
                    <div class="flex flex-row items-center gap-1">
                        <Link :href="route('dashboard')"
                              class="hidden sm:inline-flex items-center rounded-btn bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 hover:bg-primary/20 transition-colors duration-150">
                            {{ $page.props.auth.user.nb_credits }} credits
                        </Link>
                        <div class="hidden sm:flex sm:items-center sm:ms-4">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                    <span class="inline-flex rounded-btn">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-btn
                                text-base-content/80
                                hover:text-base-content hover:bg-base-200
                                focus:outline-none transition ease-in-out duration-150">
                            {{ $page.props.auth.user.name }}
                            <svg
                                class="ms-2 -me-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor">
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
                                            {{ $t('auth.log_out') }}
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
                                    class="inline-flex items-center justify-center p-2 rounded-btn
                                            text-base-content/70
                                            focus:outline-none
                                            hover:bg-base-200
                                            transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            :class="{hidden: showingNavigationDropdown,'inline-flex': !showingNavigationDropdown}"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{hidden: !showingNavigationDropdown,'inline-flex': showingNavigationDropdown}"
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
                 class="sm:hidden smallscreen border-t border-base-300">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('app.projects')" :active="route().current('app.projects')">
                        {{ $t('app.projects') }}
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('app.ideas')" :active="route().current('app.ideas')">
                        {{ $t('app.ideas.ideas_generator') }}
                    </ResponsiveNavLink>
                    <a :href="route('app.ideas.catalog')" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start
                                       font-medium text-base-content/70 hover:text-base-content hover:bg-base-200 hover:border-base-300
                                       transition duration-150 ease-in-out">
                        {{ $t('app.ideas.catalog.ideas_catalog') }}
                    </a>
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        {{ $t('layout.dashboard') }}
                    </ResponsiveNavLink>
                </div>
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-base-300">
                    <div class="px-4">
                        <div class="font-medium text-base-content">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-base-content/50">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            {{ $t('auth.log_out') }}
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-base-100 border-b border-base-300" v-if="$slots.header">
            <slot name="header"/>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <Loader/>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-base-100 border border-base-300 overflow-hidden shadow-soft sm:rounded-box">
                        <div class="p-6 space-y-5 layout">
                            <slot/>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer/>
    </div>
</template>
