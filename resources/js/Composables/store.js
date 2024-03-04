import {defineStore} from 'pinia'

export const useStore = defineStore('test', {
    state: () => {
        return {
            projectId: null
        }
    },
    actions: {
        setProjectId(projectId) {
            this.projectId = projectId
        }
    }
})
