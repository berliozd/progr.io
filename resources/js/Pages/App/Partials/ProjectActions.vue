<script setup>

import SavedLabel from "@/Components/SavedLabel.vue";
import SendModal from "@/Pages/App/Partials/SendModal.vue";

const props = defineProps({
  project: null
})

const exportProject = (id, routeValue) => {
  window.open(route(routeValue, id));
}
</script>

<template>
  <div class="flex flex-row justify-end pr-6 sm:pr-8 py-3" v-if="project && project.id">
    <SavedLabel/>
    <div class="flex flex-row space-x-3 h-fit">
      <div class="tooltip" data-tip="Export as CSV">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-sheet hover:cursor-pointer" @click="exportProject( project.id, 'app.projects.csv')">
          <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
          <line x1="3" x2="21" y1="9" y2="9"/>
          <line x1="3" x2="21" y1="15" y2="15"/>
          <line x1="9" x2="9" y1="9" y2="21"/>
          <line x1="15" x2="15" y1="9" y2="21"/>
        </svg>
      </div>
      <div class="border"></div>
      <div class="tooltip" data-tip="Export as PDF">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-file-text hover:cursor-pointer"
             @click="exportProject( project.id, 'app.projects.pdf')">
          <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
          <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
          <path d="M10 9H8"/>
          <path d="M16 13H8"/>
          <path d="M16 17H8"/>
        </svg>
      </div>
      <div class="border"></div>
      <SendModal :title="project.title" :description="project.description" :project-id="project.id"/>
      <div class="border"></div>
      <div class="tooltip" data-tip="Presentation view">
        <a :href="route('app.projects.presentation', {id: project.id})" target="_blank">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
               stroke="currentColor"
               stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-presentation">
            <path d="M2 3h20"/>
            <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/>
            <path d="m7 21 5-5 5 5"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</template>
