import {defineStore} from 'pinia'

export const useStore = defineStore('store', {
    state: () => {
        return {
            toastVisible: false,
            toastText: null,
            toastError: null,
            loading: false,
            history: [],
            saved: false,
            savedText: null,
        }
    },
    actions: {
        setToast(text, error = false, delayBeforeHiding = 5000) {
            this.toastVisible = true;
            this.toastText = text;
            this.toastError = error;
            setTimeout(() => {
                this.toastVisible = false
            }, delayBeforeHiding);
        },
        setSaved(text, delayBeforeHiding = 2000) {
            this.saved = true
            this.savedText = text
            setTimeout(() => {
                this.saved = false
                this.savedText = null
            }, delayBeforeHiding);
        },
        setIsLoading(isLoading) {
            this.loading = isLoading
        },
        addHistory(url) {
            this.history.push(url)
        },
    }
})
