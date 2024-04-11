import axios from "axios";
import userUsedCredits from "@/Composables/App/userUsedCredits.js";

export default async function reallyAskAi(context, question) {
    return await axios.post('/api/ai/', {'context': context, 'question': question}).then(
        (response) => {
            userUsedCredits()
            return response.data.response
        }
    )
}
