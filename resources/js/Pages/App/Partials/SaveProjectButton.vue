<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    onClick: null
})
</script>
<template>
  <div class="flex justify-end">
    <PrimaryButton @click="props.onClick()" class="flex justify-around">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-save mx-auto ">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
        </svg>
    </PrimaryButton>
  </div>
</template>
