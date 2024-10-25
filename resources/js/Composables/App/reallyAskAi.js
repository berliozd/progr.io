import axios from "axios";
import userUsedCredits from "@/Composables/App/userUsedCredits.js";

export async function askNote(title, description, noteTypeCode, isCompetitor) {
    return await axios.post(
        '/api/ai/note/',
        {
            'title': title,
            'description': description,
            'noteTypeCode': noteTypeCode,
            'competitor': isCompetitor
        }
    ).then(
        (response) => {
            userUsedCredits(1)
            return response.data.response
        }
    )
}

export async function askIdeas(context) {
    return await axios.post('/api/ai/ideas/', {'context': context}).then(
        (response) => {
            userUsedCredits(8)
            return response.data.response
        }
    )
}

export async function askSharingEmailContent(id, title, description) {
    return await axios.post(
        '/api/ai/sharing_email/',
        {'id': id, 'title': title, 'description': description}
    ).then(
        (response) => {
            userUsedCredits(1)
            return response.data.response
        }
    )
}
