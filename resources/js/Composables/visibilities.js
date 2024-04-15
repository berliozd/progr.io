import axios from "axios";

export default async function visibilities() {
    const response = await axios.get('/api/projects_visibilities')
    return response.data
}
