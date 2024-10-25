<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AILogo from "@/Components/AILogo.vue";

import {capitalize, ref} from "vue";
import {Clipboard} from 'v-clipboard'
import {useStore} from "@/Composables/store.js";
import aiAvailable from "@/Composables/App/aiAvailable.js";
import {askNote} from "@/Composables/App/reallyAskAi.js";

const props = defineProps({
    title: null,
    description: null,
    note: null,
    isCompetitor: {
        type: Boolean,
        default: false
    }
})
const emits = defineEmits(['change'])
const isShowModal = ref(false)
const aiResponse = ref('');
const loading = ref(false)
const ai = ref(true);

const askAI = async () => {
    aiAvailable(1).then((aiAvailable) => {
        if (aiAvailable) {
            ai.value = true
            loading.value = true
            askNote(props.title, props.description, props.note.type.code, props.isCompetitor).then((response) => {
                aiResponse.value = response
                loading.value = false
            })
        } else {
            ai.value = false
        }
    })
}

const showModal = () => {
    aiResponse.value = ''
    isShowModal.value = true
    askAI()
}

const hideModal = () => {
    isShowModal.value = false
}

const useForNote = () => {
    props.note.content = aiResponse
    useStore().setToast('Field filled with AI response.')
    emits('change')
    hideModal()
}

const copy = () => {
    Clipboard.copy(aiResponse.value)
    useStore().setToast('Copied to clipboard.')
}
const gotTo = (url) => {
    window.location.href = url
}
</script>

<template>

    <div class="flex flex-row sm:ml-10 space-x-2 text-xs items-center hover:cursor-pointer" @click="showModal">
        <span class="underline hidden sm:block">{{ $t('app.project.ask_ai_help') }}</span>
        <AILogo/>
    </div>

    <Modal :show="isShowModal">

        <div class="p-4 w-full space-y-4 flex flex-col" v-if="ai">
            <div class="flex flex-row w-full justify-between">
                <div>
                    {{ $t('app.project.note', {'label': capitalize(note.type.label)}) }}
                </div>
                <div class="space-x-2 items-center">
                    <span class="loading loading-spinner loading-s" v-show="loading"></span>
                    <PrimaryButton @click="askAI" v-bind:disabled="loading">
                        {{ $t('app.project.ask_ai') }}
                    </PrimaryButton>
                </div>
            </div>
            <TextArea v-model="aiResponse" rows="8" class="w-full"></TextArea>
            <div class="flex flex-row justify-between">
                <SecondaryButton @click="hideModal">{{ $t('app.cancel') }}</SecondaryButton>
                <div class="flex flex-row justify-between space-x-5">
                    <SecondaryButton v-if="aiResponse" @click="copy" :title="$t('app.project.copy')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-clipboard-copy">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v4"/>
                            <path d="M21 14H11"/>
                            <path d="m15 10-4 4 4 4"/>
                        </svg>
                    </SecondaryButton>
                    <SecondaryButton v-if="aiResponse" @click="useForNote" :title="$t('app.project.use_for_note') ">
                        {{ $t('app.project.use_for_note') }}
                    </SecondaryButton>
                </div>
            </div>
        </div>

        <div class="flex flex-col space-y-2 p-4" v-else>
            <div class="flex flex-row justify-between alert alert-error">
                {{ $t('app.ai_not_available') }}
                <PrimaryButton @click="gotTo(route('dashboard'))">
                    Buy more credits
                </PrimaryButton>
            </div>
            <SecondaryButton @click="hideModal" class="w-fit">{{ $t('app.cancel') }}</SecondaryButton>
        </div>

    </Modal>
</template>
