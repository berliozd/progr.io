<script setup>
import {capitalize, ref} from "vue";
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";

import AskAiModal from "@/Pages/App/Partials/AskAiModal.vue";
import TextArea from "@/Components/TextArea.vue";
import UpAndDown from "@/Components/UpAndDown.vue";
import DeleteModal from "@/Components/DeleteModal.vue";

const dropOverNote = ref(null);
const noteTypeToAdd = ref(null)
const emit = defineEmits(['change', 'startAddNote'])
const props = defineProps({
  'notes': null,
  'availableNotesTypes': null,
  'allNotesTypes': null,
  'context': null,
  'notesParentIdFieldName': null,
  'notesParentId': null,
  'apiUrl': null
})

const endDrag = (event, item, notes) => {
  const noteDroppedOverOrder = dropOverNote.value.order;
  dropOverNote.value.order = item.order
  item.order = noteDroppedOverOrder
  sortNotes(notes);
}

const dragOver = (event, item) => {
  event.dataTransfer.dropEffect = 'link'
  dropOverNote.value = item
}

const sortNotes = (notes) => {
  console.log('sort notes');
  notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
}

const deleteNote = (note) => {
  const index = props.notes.indexOf(note);
  props.notes.splice(index, 1);
  props.availableNotesTypes.push(props.allNotesTypes.find(type => type.id === note.type.id))
}

const addEmptyNote = (noteType, availableNotesTypes) => {
  if (!noteType) {
    useStore().setToast(trans('app.project.select_note_type'), true)
    return
  }
  emit('startAddNote')
  let emptyNote = {
    'type': {'id': noteType.id, 'label': noteType.label, 'code': noteType.code},
    'content': '',
    'order': maxOrderNotes(props.notes) + 1
  }
  emptyNote[props.notesParentIdFieldName] = props.notesParentId
  props.notes.push(emptyNote);
  sortNotes(props.notes)
  noteTypeToAdd.value = null
  availableNotesTypes = availableNotesTypes.filter(type => {
    return noteType.id !== type.id
  });
  return availableNotesTypes;
}

const maxOrderNotes = (notes) => {
  let maxOrder = 0
  notes.forEach(note => {
    if (note.order > maxOrder) {
      maxOrder = note.order
    }
  })
  return maxOrder
}
</script>

<template>

  <div v-for="note in notes" class="my-4 hover:cursor-grab" :key="note.id" draggable="true"
       @dragend="endDrag($event, note, notes)" @dragover="dragOver($event, note)"
       @dragover.prevent>
    <div class="flex flex-row justify-between mb-2">
      <div class="flex flex-row w-fit">
        <label class="text-xs sm:text-base">{{ capitalize(note.type.label) }}:</label>
        <AskAiModal :context="context" :note="note" @change="emit('change')"/>
      </div>
      <div class="flex flex-row hover:cursor-pointer space-x-2">
        <UpAndDown :item="note" :items="notes"/>
        <DeleteModal :id="note.id" :api-url="props.apiUrl" @deleted="deleteNote(note)"
                     :question="$t('app.project.deletion_note_confirmation_question')"
                     :confirmation-button-text="$t('app.project.delete_note')"/>
      </div>
    </div>
    <TextArea v-model="note.content" rows="6" class="w-full" @input="emit('change')"></TextArea>
  </div>
  <div class="flex flex-col" v-if="availableNotesTypes?.length > 0">
    <div>
      {{ $t('app.project.select_note_type') }}
    </div>
    <div class="items-center">
      <select class="select bg-white mr-2"
              @change="availableNotesTypes = addEmptyNote(noteTypeToAdd, availableNotesTypes)"
              v-model="noteTypeToAdd">
        <option v-for="notesType in availableNotesTypes" v-bind:value="notesType">
          {{ capitalize(notesType.label) }}
        </option>
      </select>
    </div>
  </div>

</template>
