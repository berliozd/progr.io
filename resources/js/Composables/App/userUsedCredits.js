import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export default async function userUsedCredits(increment) {
    const userResponse = await axios.get('/api/user/' + usePage().props.auth.user.id)
    let usedCredits = userResponse.data.used_credits
    let nbCredits = userResponse.data.nb_credits
    await axios.patch(
        '/api/user/' + usePage().props.auth.user.id, {
            'field': 'used_credits',
            'value': usedCredits + increment
        }
    )
    await axios.patch(
        '/api/user/' + usePage().props.auth.user.id, {
            'field': 'nb_credits',
            'value': nbCredits > 0 ? nbCredits - increment : 0
        }
    )
}