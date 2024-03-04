<script setup>
import {getActiveLanguage, loadLanguageAsync} from 'laravel-vue-i18n';
import {usePage} from '@inertiajs/vue3';
import {computed, ref} from 'vue';
import axios from "axios";

const languages = computed(() => usePage().props.app.locales);
const userId = computed(() => usePage().props.auth.user.id);
let lang = ref(getActiveLanguage())

axios.get('/api/user_settings/' + userId.value)
    .then(response => {
        if (response.data.lang) {
            loadLanguageAsync(response.data.lang)
            lang.value = response.data.lang
        }
    })
    .catch(error => {
        console.error(error);
    });


const changeLanguage = (value) => {
    axios.patch('/api/user_settings/' + userId.value, {'field': 'lang', 'value': value})
        .then(response => {
            loadLanguageAsync(value);
            lang.value = value;
        })
        .catch(error => {
            console.error(error);
        });
}

</script>
<template>
    <select v-model="lang" @change="changeLanguage(lang)"
            class="bg-white dark:bg-gray-800 dark:focus:ring-0 focus:ring-0 focus:ring-white border-white border-0 dark:border-gray-800 dark:text-gray-400 text-gray-500">
        <option v-for="language in languages" v-bind:value="language">
            {{ language.toUpperCase() }}
        </option>
    </select>
</template>
