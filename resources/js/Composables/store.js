import {defineStore} from 'pinia'

export const useStore = defineStore('test', {
    state: () => {
        return {
            projectId: null,
            toastVisible: false,
            toastText: null,
            toastError: null,
            loading: false
        }
    },
    actions: {
        setProjectId(projectId) {
            this.projectId = projectId
        },
        setToast(text, error = false, delayBeforeHiding = 5000) {
            this.toastVisible = true;
            this.toastText = text;
            this.toastError = error;
            setTimeout(() => {
                this.toastVisible = false
            }, delayBeforeHiding);
        },
        setIsLoading(isLoading) {
            this.loading = isLoading
        }
    }
})
