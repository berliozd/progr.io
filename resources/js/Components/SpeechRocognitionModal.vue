<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import TextArea from "@/Components/TextArea.vue";

const props = defineProps({content: null})

const isShowModal = ref(false)
const transcript = ref('');
const recognition = ref(null);

const showModal = () => {
  transcript.value = ''
  isShowModal.value = true
}

const hideModal = () => {
  isShowModal.value = false
}

const useText = () => {
  props.content.value = transcript.value
  hideModal();
};


const startRecording = () => {
  recognition.value = new webkitSpeechRecognition();
  recognition.value.continuous = true;
  recognition.value.interimResults = true;
  recognition.value.lang = 'fr-FR';
  recognition.value.start();
  recognition.value.onresult = (event) => {
    transcript.value = event.results[0][0].transcript;
  };
}
const stopRecording = () => {
  recognition.value.stop();
}
</script>

<template>
  <div class="p-4 m-2 rounded border items-center align-middle my-auto hover:cursor-pointer" @click="showModal">
    <svg @click="showModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic">
      <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
      <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
      <line x1="12" x2="12" y1="19" y2="22"/>
    </svg>
  </div>
  <Modal :show="isShowModal">
    <div class="p-4 w-full space-y-4 flex flex-col">
      <PrimaryButton @click="startRecording">Start</PrimaryButton>
      <PrimaryButton @click="stopRecording">Stop</PrimaryButton>
      <TextArea v-bind:model-value="transcript"></TextArea>
      {{transcript}}
      <PrimaryButton v-if="transcript" @click="useText">Use</PrimaryButton>
    </div>
  </Modal>
</template>
