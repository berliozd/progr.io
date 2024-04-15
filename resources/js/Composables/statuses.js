import axios from "axios";

export default async function statuses() {
    const response = await axios.get('/api/projects_status')
    return response.data
}
