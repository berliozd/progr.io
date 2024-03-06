import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import axios from "axios";

export default function setMode() {
    const user = computed(() => usePage().props.auth.user)
    if (user) {
        const userId = user.value?.id;
        if (userId) {
            axios.get('/api/user_settings/' + userId)
                .then(response => {
                    document.documentElement.classList.remove('light', 'dark');
                    document.documentElement.classList.add(response.data.mode);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
}
