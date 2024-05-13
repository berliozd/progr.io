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
    'title': null,
    'description': null,
    'notesParentIdFieldName': null,
    'notesParentId': null,
    'apiUrl': null,
    'isCompetitor': null,
    'readOnly': false
})

const endDrag = (event, item, notes) => {
    if (props.readonly) {
        return
    }
    const noteDroppedOverOrder = dropOverNote.value.order;
    dropOverNote.value.order = item.order
    item.order = noteDroppedOverOrder
    sortNotes(notes);
}

const dragOver = (event, item) => {
    if (props.readonly) {
        return
    }
    event.dataTransfer.dropEffect = 'link'
    dropOverNote.value = item
}

const sortNotes = (notes) => {
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

    <div v-for="note in notes"
         :class="'my-4 ' + (!readOnly?'hover:cursor-grab':'')" :key="note.id"
         :draggable="readOnly?'false':'true'"
         @dragend="endDrag($event, note, notes)" @dragover="dragOver($event, note)"
         @dragover.prevent>
        <div class="flex flex-row justify-between mb-2">
            <div class="flex flex-row w-fit">
                <label class="text-xs sm:text-base">{{ capitalize(note.type.label) }}:</label>
                <AskAiModal :title="title" :description="description" :note="note" @change="emit('change')"
                            :is-competitor="isCompetitor" v-if="!readOnly"/>
            </div>
            <div class="flex flex-row hover:cursor-pointer space-x-2" v-if="!readOnly">
                <UpAndDown :item="note" :items="notes"/>
                <DeleteModal :id="note.id" :api-url="props.apiUrl" @deleted="deleteNote(note)"
                             :question="$t('app.project.deletion_note_confirmation_question')"
                             :confirmation-button-text="$t('app.project.delete_note')"/>
            </div>
        </div>

        <div v-if="readOnly" class="text-xs">
           <pre class="text-wrap">{{ note.content }}</pre>
        </div>
        <TextArea v-else v-model="note.content" rows="6" class="w-full" @input="emit('change')"></TextArea>
    </div>
    <div class="flex flex-col" v-if="availableNotesTypes?.length > 0 && !readOnly">
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
