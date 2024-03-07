<script setup>
import axios from "axios";
import {ref} from "vue";
import TextArea from "@/Components/TextArea.vue";
import {getActiveLanguage, trans} from "laravel-vue-i18n";

const props = defineProps({
    questionType: null,
    title: null,
    description: null
})
const aiResponse = ref(null);

const askAI = async () => {
    let context = props.title + ' - ' + props.description;
    if (typeof props.title === "object") {
        context = props.title.value + ' - ' + props.description.value;
    }
    let question = ' By using bullets points, carriage return and '
        + getActiveLanguage() + ' language in your answer';
    question += ' - ' + baseQuestion();
    console.log(question);
    const response = await axios.post(
        '/api/ai/', {
            'context': context,
            'question': question
        }
    )
    aiResponse.value = response.data
}


const baseQuestion = () => {
    if (props.questionType === 'benefits') {
        return 'give me benefits for future users'
    }
    if (props.questionType === 'money') {
        return 'give me how I can monetize it'
    }
}

const componentTitle = () => {
    if (props.questionType === 'benefits') {
        return trans('app.benefits_for_future_users')
    }
    if (props.questionType === 'money') {
        return trans('app.monetization')
    }
}

</script>
<template>
    <div class="flex flex-col">
        {{ componentTitle() }}
        <button class="btn btn-neutral" @click="askAI">Ask to AI</button>
        <text-area v-if="aiResponse" v-model="aiResponse.response" rows="8" class="w-full"></text-area>
    </div>
</template>
