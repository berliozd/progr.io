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
            userUsedCredits()
            return response.data.response
        }
    )
}

export async function askCompetitor(title, description) {
    return await axios.post(
        '/api/ai/competitors/',
        {'title': title, 'description': description}
    ).then(
        (response) => {
            userUsedCredits()
            return response.data.response
        }
    )
}

export async function askIdeas(context) {
    return await axios.post('/api/ai/ideas/', {'context': context}).then(
        (response) => {
            userUsedCredits()
            return response.data.response
        }
    )
}
