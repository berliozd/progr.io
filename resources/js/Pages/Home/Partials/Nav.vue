<script setup>
import SimpleLink from "@/Components/SimpleLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Link, router} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});

const showingNavigationDropdown = ref(false);
const alt = 'Progr.io - innovation platform to track your business ideas and get inspired'

</script>

<template>

    <!-- NON RESPONSIVE MENU -->
    <div class="sm:grid grid-cols-3 px-12 py-4 hidden sticky top-0 z-30 bg-base-100/90 backdrop-blur border-b border-base-300">

        <SimpleLink v-bind:href="route('home')" class="flex items-center gap-2 text-lg">
            <img src="/img/icon.png" width="36" :alt="alt">
            Progr.io
        </SimpleLink>

        <div class="space-x-8 text-center flex items-center justify-center text-sm font-medium text-base-content/70">
            <a href="#pricing" class="hover:text-primary transition-colors duration-150" v-smooth-scroll>Pricing</a>
            <a href="#faq" class="hover:text-primary transition-colors duration-150" v-smooth-scroll>FAQ</a>
            <a href="/project-ideas" class="hover:text-primary transition-colors duration-150">Project ideas</a>
        </div>

        <div v-if="props.canLogin" class="relative">
            <div class="right-0 absolute flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                <PrimaryButton v-if="$page.props.auth.user" @click="router.visit('dashboard')">
                    {{ $t('Get in') }}
                </PrimaryButton>
                <template v-else>
                    <PrimaryButton @click="router.visit('register')">{{ $t('Register') }}</PrimaryButton>
                    <PrimaryButton @click="router.visit('login')">{{ $t('Login') }}</PrimaryButton>
                </template>
            </div>
        </div>
    </div>


    <!-- RESPONSIVE MENU -->
    <div class="grid grid-cols-3 sm:hidden py-2 justify-between sticky top-0 z-30 bg-base-100/90 backdrop-blur border-b border-base-300">

        <div class="">
            <SimpleLink v-bind:href="route('home')" class="">
                <img src="/img/icon.png" width="40" :alt="alt">
            </SimpleLink>
        </div>

        <div class="space-x-5 text-center text-xs flex items-center justify-center text-base-content/70">
            <a href="#pricing" class="hover:text-primary transition-colors duration-150" v-smooth-scroll>Pricing</a>
            <a href="#faq" class="hover:text-primary transition-colors duration-150" v-smooth-scroll>FAQ</a>
            <a href="/project-ideas" class="hover:text-primary transition-colors duration-150">Ideas</a>
        </div>

        <!-- Hamburger -->
        <div class="flex items-center relative">
            <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="absolute right-0 p-2 rounded-btn
          focus:outline-none
          text-base-content/70
          hover:bg-base-200
          transition duration-150 ease-in-out"
            >
                <svg class="h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{hidden: showingNavigationDropdown,'inline-flex': !showingNavigationDropdown}"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path :class="{hidden: !showingNavigationDropdown,'inline-flex': showingNavigationDropdown}"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
         class="sm:hidden w-full flex bg-base-100 border-b border-base-300">
        <div v-if="props.canLogin" class="p-4 relative w-full flex flex-col space-y-2 items-center">
            <hr class="border-base-300 w-full"/>
            <PrimaryButton v-if="$page.props.auth.user" @click="router.visit('dashboard')">
                {{ $t('Get in') }}
            </PrimaryButton>
            <template v-else>
                <PrimaryButton @click="router.visit('register')">{{ $t('Register') }}</PrimaryButton>
                <PrimaryButton @click="router.visit('login')">{{ $t('Login') }}</PrimaryButton>
            </template>
            <hr class="border-base-300 w-full"/>
        </div>
    </div>

</template>
