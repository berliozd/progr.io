<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import DeleteButton from "@/Components/DeleteButton.vue";

import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {useStore} from "@/Composables/store.js";

const props = defineProps({projectId: null})

const confirmingProjectDeletion = ref(false);
const closeModal = () => {
    confirmingProjectDeletion.value = false;
};
const confirmProjectDeletion = () => {
    confirmingProjectDeletion.value = true;
};


const deleteProject = async () => {
    try {
        await axios.delete('/api/projects/' + props.projectId);
        useStore().setToast('Deleted!');
        router.visit('/projects')
    } catch (error) {
        console.log(error)
    }
}

</script>
<template>
    <DeleteButton :on-click="confirmProjectDeletion"></DeleteButton>
    <Modal :show="confirmingProjectDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                {{ $t('app.project.deletion_confirmation_question') }}
            </h2>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">{{ $t('app.cancel') }}</SecondaryButton>
                <DangerButton class="ms-3" @click="deleteProject">{{ $t('app.project.delete') }}</DangerButton>
            </div>
        </div>
    </Modal>
</template>
