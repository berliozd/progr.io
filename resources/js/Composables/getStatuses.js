import axios from "axios";

export default async function getStatuses() {
    const response = await axios.get('/api/projects_status')
    return response.data
}
