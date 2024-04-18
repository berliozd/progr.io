import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export default async function aiAvailable() {
    if (usePage().props.auth.subscription && usePage().props.auth.subscription.is_valid) {
        return true
    }
    const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
    return userResponse.data.used_ai_credits < usePage().props.app.free_ai_credits
}
