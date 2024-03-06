<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {useStore} from "@/Composables/store.js";

const props = defineProps({projectId: null})

const confirmingProjectDeletion = ref(false);
const closeModal = () => {
    console.log('click close modal');
    confirmingProjectDeletion.value = false;
};
const confirmProjectDeletion = () => {
    console.log('click open modal');
    console.log(props.projectId);
    confirmingProjectDeletion.value = true;
};


const deleteProject = async () => {
    try {
        await axios.delete('/api/projects/' + props.projectId);
        useStore().setToast('Deleted!', 3000);
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

</script>
<template>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor" stroke-width="1" stroke-linecap="round"
         stroke-linejoin="round"
         class="lucide lucide-trash-2" @click="confirmProjectDeletion()">
        <path d="M3 6h18"/>
        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
        <line x1="10" x2="10" y1="11" y2="17"/>
        <line x1="14" x2="14" y1="11" y2="17"/>
    </svg>
    <Modal :show="confirmingProjectDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $t('app.projects.deletion_confirmation_question') }}
            </h2>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">{{ $t('app.cancel') }}</SecondaryButton>
                <DangerButton class="ms-3" @click="deleteProject">{{ $t('app.project.delete') }}</DangerButton>
            </div>
        </div>
    </Modal>
</template>
