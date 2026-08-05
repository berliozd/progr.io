<script setup>

import axios from "axios";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage()
const userId = computed(() => page.props.auth.user.id);
const userSettings = ref(null);
const mode = ref(document.documentElement.classList.contains('light') ? 'light' : 'dark');

const getUser = async () => {
  await axios.get('/api/user/' + userId.value).then((response) => {
    userSettings.value = response.data.settings
  })
}

const applyMode = (newMode) => {
  document.documentElement.classList.remove('light', 'dark');
  document.documentElement.classList.add(newMode);
  document.documentElement.setAttribute('data-theme', newMode);
  mode.value = newMode;
}

const toggleDarkMode = async () => {
  await getUser()
  const settings = JSON.parse(userSettings.value);
  const newMode = mode.value === 'light' ? 'dark' : 'light';
  settings.mode = newMode
  axios.patch('/api/user/' + userId.value, {'field': 'settings', 'value': JSON.stringify(settings)})
      .then(response => {
        applyMode(newMode);
      })
      .catch(error => {
        console.error(error);
      });
}

const initMode = async () => {
  await getUser()
  applyMode(JSON.parse(userSettings.value).mode);
}
initMode()

</script>
<template>
  <button @click="toggleDarkMode()"
          type="button"
          :title="mode === 'light' ? 'Dark mode' : 'Light mode'"
          class="rounded-full p-2 transition-colors duration-150
            hover:bg-base-200 hover:text-primary
            focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
    <svg v-if="mode === 'light'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor"
         class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
    </svg>
    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor"
         class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
    </svg>
  </button>
</template>
