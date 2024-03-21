<script setup>
import {onMounted, ref} from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    rows: String
})

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <textarea
        class="text-primary-content rounded-md shadow-sm"
        v-model="model"
        v-bind:rows="props.rows"
        ref="input"
    />
</template>
