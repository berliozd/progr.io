<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  nextTick(() => passwordInput.value.focus());
};

const cancelSubscription = () => {
  form.delete(route('subscribe.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
  form.reset();
};
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Cancel your subscription</h2>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Cancel your subscription...
      </p>
    </header>

    <DangerButton @click="confirmUserDeletion">Cancel Subscription</DangerButton>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Are you sure you want to cancel your subscription?
        </h2>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

          <DangerButton
              class="ms-3"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click="cancelSubscription"
          >
            Cancel subscription
          </DangerButton>
        </div>
      </div>
    </Modal>
  </section>
</template>
