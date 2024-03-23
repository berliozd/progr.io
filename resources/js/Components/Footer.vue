<script setup>
import {router, usePage} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const isActive = (route) => {
  return location.href === route;
}

const appHomeRoute = route(usePage().props.app.home_route);
</script>
<template>
  <div class="btm-nav">
    <ApplicationLogo v-bind:simple="true"></ApplicationLogo>
    <button @click="router.visit(appHomeRoute)"
            v-bind:class="isActive(appHomeRoute)?'active':''">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
           class="lucide lucide-briefcase">
        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
      </svg>
    </button>
    <button @click="router.visit(route('dashboard'))" v-bind:class="isActive(route('dashboard'))?'active':''">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
           class="lucide lucide-layout-dashboard">
        <rect width="7" height="9" x="3" y="3" rx="1"/>
        <rect width="7" height="5" x="14" y="3" rx="1"/>
        <rect width="7" height="9" x="14" y="12" rx="1"/>
        <rect width="7" height="5" x="3" y="16" rx="1"/>
      </svg>
    </button>
    <button @click="router.visit(route('profile.edit'))" v-bind:class="isActive(route('profile.edit'))?'active':''">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
           class="lucide lucide-circle-user-round">
        <path d="M18 20a6 6 0 0 0-12 0"/>
        <circle cx="12" cy="10" r="4"/>
        <circle cx="12" cy="12" r="10"/>
      </svg>
    </button>
  </div>
</template>
