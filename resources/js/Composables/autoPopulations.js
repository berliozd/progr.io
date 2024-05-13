import axios from "axios";

export default async function autoPopulations(all = false) {
    const response = await axios.get('/api/auto_populations', {
        params: {
            'all': all ? 1 : 0
        }
    })
    return response.data
}

export async function idByCode(code) {
    const allAutoPopulations = await autoPopulations(true)
    for (let index in allAutoPopulations) {
        if (allAutoPopulations[index].code === code) {
            return allAutoPopulations[index].id
        }
    }
}