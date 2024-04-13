<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import DeleteButton from "@/Components/DeleteButton.vue";

import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {useStore} from "@/Composables/store.js";

const props = defineProps({id: null, apiUrl: null, redirectRoute: null, question: null, confirmationButtonText: null})
const emits = defineEmits(['deleted'])
const confirmingDeletion = ref(false);

const closeModal = () => {
  confirmingDeletion.value = false;
};
const confirmProjectDeletion = () => {
  confirmingDeletion.value = true;
};


const deleteItem = async () => {
  try {
    await axios.delete(props.apiUrl + props.id);
    emits('deleted')
    useStore().setToast('Deleted!');
    if (props.redirectRoute) {
      router.visit(props.redirectRoute)
    } else {
      closeModal()
    }
  } catch (error) {
    console.log(error)
  }
}

</script>
<template>
  <DeleteButton :on-click="confirmProjectDeletion"></DeleteButton>
  <Modal :show="confirmingDeletion" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-medium">{{ question }}</h2>
      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal">{{ $t('app.cancel') }}</SecondaryButton>
        <DangerButton class="ms-3" @click="deleteItem">{{ confirmationButtonText }}</DangerButton>
      </div>
    </div>
  </Modal>
</template>
