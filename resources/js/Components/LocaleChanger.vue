<script setup>
import {getActiveLanguage, loadLanguageAsync} from 'laravel-vue-i18n';
import {usePage} from '@inertiajs/vue3';
import {computed, ref} from 'vue';
import axios from "axios";

const languages = computed(() => usePage().props.app.locales);
const userId = computed(() => usePage().props.auth.user.id);
const settings = ref(null);
let lang = ref(getActiveLanguage())

axios.get('/api/user/' + userId.value)
    .then(response => {
        settings.value = JSON.parse(response.data.settings);
        if (settings.value?.lang) {
            loadLanguageAsync(settings.value.lang)
            lang.value = settings.value.lang
        }
    })
    .catch(error => {
        console.error(error);
    });


const changeLanguage = (value) => {
    settings.value.lang = value;
    axios.patch('/api/user/' + userId.value, {'field': 'settings', 'value': settings.value})
        .then(response => {
            loadLanguageAsync(response.data.settings.lang);
        })
        .catch(error => {
            console.error(error);
        });
}

</script>
<template>
    <select v-model="lang" @change="changeLanguage(lang)"
            class="bg-base-100
            dark:focus:ring-0 focus:ring-0 focus:ring-white
            border-0
            hover:text-neutral-content">
        <option v-for="language in languages" v-bind:value="language">
            {{ language.toUpperCase() }}
        </option>
    </select>
</template>
