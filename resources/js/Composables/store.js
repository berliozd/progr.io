import {defineStore} from 'pinia'

export const useStore = defineStore('test', {
    state: () => {
        return {
            projectId: null,
            toastVisible: false,
            toastText: null
        }
    },
    actions: {
        setProjectId(projectId) {
            this.projectId = projectId
        },
        setToast(text, delayBeforeHiding = 3000) {
            this.toastVisible = true;
            this.toastText = text;
            setTimeout(() => {
                this.toastVisible = false
            }, delayBeforeHiding);
        }
    }
})
