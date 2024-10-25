import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export default async function aiAvailable(neededCredits) {
    const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
    return userResponse.data.nb_credits - neededCredits >= 0
}
