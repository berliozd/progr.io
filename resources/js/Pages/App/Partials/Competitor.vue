<script setup>
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";

const props = defineProps({'competitor': null})
</script>

<template>
  <div class="flex flex-col sm:flex-row sm:space-x-4 ">
    <div class="flex flex-col w-full">
      <div>Name :</div>
      <TextInput v-model="competitor.name" class="w-full"/>
      <div>Url : <a :href="competitor.url" target="_blank"
                    class="text-xs sm:text-base underline">{{ competitor.url }}</a></div>
      <TextInput v-model="competitor.url" class="w-full"/>
    </div>
    <div class="flex flex-col w-full h-full">
      <div>Description :</div>
      <TextArea model-value="" v-model="competitor.description" rows="3" class="w-full h-full"/>
    </div>
  </div>
</template>
