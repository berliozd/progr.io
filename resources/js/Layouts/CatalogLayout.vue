<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Footer from "@/Components/Footer.vue";

import {usePage} from '@inertiajs/vue3'
import {ref} from 'vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-base-300">
        <nav class="border-b border-base-200 bg-base-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <ApplicationLogo class="block h-9 w-auto fill-current"/>
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" v-if="usePage().props.auth?.user">
                            <NavLink :href="route('app.projects')" :active="route().current('app.projects')">
                                {{ $t('app.projects') }}
                            </NavLink>
                            <NavLink :href="route('app.ideas')" :active="route().current('app.ideas')">
                                {{ $t('app.ideas.ideas_generator') }}
                            </NavLink>
                            <a :href="route('subscribe.checkout')" v-if="!usePage().props.auth?.subscription"
                               :class="'inline-flex items-center px-1 pt-1 text-sm hover:text-neutral-content/70'">
                                {{ $t('layout.subscription') }}
                            </a>
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                {{ $t('layout.dashboard') }}
                            </NavLink>
                        </div>
                    </div>

                    <div class="flex flex-row" v-if="usePage().props.auth?.user">
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md
                                                    hover:text-neutral-content
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
                                    class="inline-flex items-center justify-center p-2 rounded-md
                                            focus:outline-none
                                            focus:bg-base-300
                                            focus:text-neutral-content/50
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
                 class="sm:hidden smallscreen" v-if="usePage().props.auth?.user">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('app.projects')" :active="route().current('app.projects')">
                        {{ $t('app.projects') }}
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('app.ideas')" :active="route().current('app.ideas')">
                        {{ $t('app.ideas.ideas_generator') }}
                    </ResponsiveNavLink>
                    <a :href="route('subscribe.checkout')" v-if="!usePage().props.auth?.subscription"
                       :class="'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start'">
                        {{ $t('layout.subscription') }}
                    </a>
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        {{ $t('layout.dashboard') }}
                    </ResponsiveNavLink>
                </div>
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-base-300">
                    <div class="px-4">
                        <div class="font-medium text-neutral-content">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-neutral-content/50">{{ $page.props.auth.user.email }}</div>
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
        <header class="bg-base-100 shadow" v-if="$slots.header">
            <slot name="header"/>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg ">
                        <div class="p-6 space-y-5 layout">
                            <slot/>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer v-if="usePage().props.auth?.user"/>
    </div>
</template>
