<script setup>

import axios from "axios";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage()
const userId = computed(() => page.props.auth.user.id);
const userSettings = ref(null);


const getUser = async () => {
  await axios.get('/api/user/' + userId.value).then((response) => {
    userSettings.value = response.data.settings
  })
}

const toggleDarkMode = async () => {
  await getUser()
  const settings = JSON.parse(userSettings.value);
  const currentMode = document.documentElement.classList.contains('light') ? 'light' : 'dark';
  const newMode = document.documentElement.classList.contains('light') ? 'dark' : 'light';
  settings.mode = newMode
  axios.patch('/api/user/' + userId.value, {'field': 'settings', 'value': JSON.stringify(settings)})
      .then(response => {
        document.documentElement.classList.remove(currentMode);
        document.documentElement.classList.add(newMode)
      })
      .catch(error => {
        console.error(error);
      });
}

const initMode = async () => {
  await getUser()
  document.documentElement.classList.remove('light', 'dark');
  document.documentElement.classList.add(JSON.parse(userSettings.value).mode);
}
initMode()

</script>
<template>
  <button @click="toggleDarkMode()"
          class="focus:outline-none
            hover:text-neutral-content">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor"
         class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
    </svg>
  </button>
</template>
