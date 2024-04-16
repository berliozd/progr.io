import axios from "axios";

export default async function sendMail(subject, recipient, content) {
    return await axios.post('/api/mail/', {'subject': subject, 'recipient': recipient, 'content': content}).then(
        (response) => {
            return response.data.response
        }
    )
}
