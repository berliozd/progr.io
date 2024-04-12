<script setup>
import AskAiModal from "@/Pages/App/Partials/AskAiModal.vue";
import {capitalize, ref} from "vue";
import axios from "axios";
import TextArea from "@/Components/TextArea.vue";
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";

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
  notes.sort((noteA, noteB) => noteA.order > noteB.order ? 1 : -1);
}

const moveUp = (event, note, notes) => {
  const currentOrder = note.order;
  note.order = previousNote(note, notes).order
  previousNote(note, notes).order = currentOrder
  sortNotes(notes);
}

const moveDown = (event, note, notes) => {
  const currentOrder = note.order;
  note.order = nextNote(note, notes).order
  nextNote(note, notes).order = currentOrder
  sortNotes(notes);
}

const getNoteIndexInArray = (note, notes) => {
  let idx = 0;
  for (let item in notes) {
    if (notes[item].content === note.content && notes[item].type.code === note.type.code) {
      return idx;
    }
    idx++;
  }
  return idx;
}

const previousNote = (note, notes) => {
  const currentNoteIndex = getNoteIndexInArray(note, notes);
  return notes[currentNoteIndex - 1]
}

const nextNote = (note, notes) => {
  const currentNoteIndex = getNoteIndexInArray(note, notes);
  return notes[currentNoteIndex + 1]
}

const deleteNote = async (event, note, notes, availableNotesTypes, allNotesTypes) => {
  const index = notes.indexOf(note);
  notes.splice(index, 1);
  availableNotesTypes.push(allNotesTypes.find(type => type.id === note.type.id))
  if (note.id) {
    console.log(props);
    await axios.delete(props.apiUrl + note.id);
  }
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
        <div class="flex flex-row w-12 justify-end">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="lucide lucide-arrow-up-from-line" @click="moveUp($event, note, notes)"
               v-if="previousNote(note, notes)">
            <path d="m18 9-6-6-6 6"/>
            <path d="M12 3v14"/>
            <path d="M5 21h14"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="lucide lucide-arrow-down-from-line" @click="moveDown($event, note, notes)"
               v-if="nextNote(note, notes)">
            <path d="M19 3H5"/>
            <path d="M12 21V7"/>
            <path d="m6 15 6 6 6-6"/>
          </svg>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-trash-2 hover:cursor-pointer"
             @click="deleteNote($event, note, notes, availableNotesTypes, allNotesTypes)">
          <path d="M3 6h18"/>
          <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
          <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
          <line x1="10" x2="10" y1="11" y2="17"/>
          <line x1="14" x2="14" y1="11" y2="17"/>
        </svg>
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
