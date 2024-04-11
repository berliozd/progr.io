import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export default async function userUsedCredits() {
    const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
    let usedAiCredits = userResponse.data.used_ai_credits
    await axios.patch(
        '/api/user/' + usePage().props.auth.user.id, {
            'field': 'used_ai_credits',
            'value': ++usedAiCredits
        }
    )
}