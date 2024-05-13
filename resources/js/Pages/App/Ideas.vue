<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import Box from "@/Components/Box.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import {Head, router} from '@inertiajs/vue3';
import {inject, nextTick, ref} from "vue";
import axios from "axios";
import {getActiveLanguage, trans} from "laravel-vue-i18n";
import {useStore} from "@/Composables/store.js";
import aiAvailable from "@/Composables/App/aiAvailable.js";
import reallyAskAi from "@/Composables/App/reallyAskAi.js";
import {idByCode} from "@/Composables/autoPopulations.js";

const ideas = ref([]);
const limitExceeded = ref(false)
const loading = ref(false)
const autoPopulationOn = ref(null)
const autoPopulationOff = ref(null)
const redirectAfterAdd = ref(true)
const addWithAutoPopulation = ref(false)
const addPublic = ref(false)
const context = ref('')
const ai = ref(true)
const errorElement = ref(null)
const smoothScroll = inject('smoothScroll')

const scrollToErrorDisplay = () => {
    nextTick(() => {
        smoothScroll({
            scrollTo: errorElement.value,
            hash: '#errordisplay'
        })
    })
}

const askAI = async () => {
    if (!context.value) {
        useStore().setToast(trans('app.ideas.no_context'), true)
        return
    }

    aiAvailable().then((aiAvailable) => {
        ai.value = false
        if (aiAvailable) {
            ai.value = true
            useStore().setIsLoading(true)
            loading.value = true
            reallyAskAi(getContext(), getQuestion()).then((response) => {
                const results = response.split(/\n/g);
                ideas.value = []
                for (let i = 0; i < results.length; i++) {
                    let result = results[i];
                    if (result !== '') {
                        let [title, description] = result.split('|');
                        if (title && description) {
                            ideas.value.push({title, description});
                        }
                    }
                }
            }).finally(() => {
                    useStore().setIsLoading(false)
                    loading.value = false
                }
            )
        }
    })
}

const getLanguage = () => {
    if (getActiveLanguage() === 'en')
        return 'english';
    if (getActiveLanguage() === 'fr')
        return 'french';
}

const getQuestion = () => {
    return 'Can you give me 5 ideas.' +
        'Each idea must be separated by a break line.' +
        'Each idea must have a title and a description.' +
        'Title and description must be separated by |.' +
        'Do not add bullets or numbers before each ideas.' +
        'Do not and introduction text before listing teh ideas.' +
        'Content should be in ' + getLanguage();
}

const getContext = () => {
    return 'As a indie hacker I would like you to give project ideas. The ideas should be related to ' + context.value;
}

idByCode('on').then(
    (response) => {
        autoPopulationOn.value = response
    }
);
idByCode('off').then(
    (response) => {
        autoPopulationOff.value = response
    }
);

const addProject = async (title, description) => {
    try {
        let project = {
            'title': title,
            'description': description,
            'status': 1,
            'visibility': (addPublic ? 2 : 1),
            'auto_population': (addWithAutoPopulation ? autoPopulationOn.value : autoPopulationOff.value)
        }
        await axios.post('/api/projects/', project).then((response) => {
            useStore().setToast(trans('app.ideas.project_added'));
            if (redirectAfterAdd.value) {
                router.visit('/project/' + response.data.id);
            }
        });
    } catch (error) {
        console.log(error)
        if (error.response?.data?.code === 'LIMIT_EXCEEDED') {
            limitExceeded.value = true
            scrollToErrorDisplay()
        }
    }
}

const gotTo = (url) => {
    window.location.href = url
}
</script>
<template>
    <Head v-bind:title="$t('app.ideas.ideas_generator')"/>
    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('app.ideas.ideas_generator')"/>
        </template>

        <Box>
            <details class="collapse collapse-arrow" open>
                <summary class="collapse-title text-xl font-medium">
                    {{ $t('app.ideas.generator_introduction') }}
                </summary>
                <div class="collapse-content space-y-4">
                    <div>{{ $t('app.ideas.generator_line1') }}</div>
                    <div>{{ $t('app.ideas.generator_line2') }}</div>
                    <div>{{ $t('app.ideas.generator_line3') }}</div>
                </div>
            </details>
        </Box>

        <Box v-if="!ai || limitExceeded">
            <div class="flex flex-col space-y-2 p-4" ref="errorElement">
                <div class="flex flex-row justify-between alert alert-error">
                    <template v-if="limitExceeded">
                        <div>{{ $t('app.nb_free_projects_reached') }}</div>
                    </template>
                    <template v-else>
                        <div>{{ $t('app.ai_not_available') }}</div>
                    </template>
                    <PrimaryButton @click="gotTo(route('subscribe.checkout'))">{{ $t('app.subscribe') }}</PrimaryButton>
                </div>
            </div>
        </Box>

        <Box class="relative">
            <label for="context" class=" inline-block">{{ $t('app.ideas.give_context') }}</label>
            <p class="mb-2 text-sm text-neutral-content/50">{{ $t('app.ideas.give_context_details') }}</p>
            <div class="flex flex-row justify-between space-x-4">
                <TextInput name="context" v-model="context" rows="8" class="w-2/3 sm:w-5/6"
                           max-length="100"></TextInput>
                <PrimaryButton @click="askAI" v-bind:disabled="loading"
                               class="px-0 sm:px-4 w-1/3 sm:w-1/6 justify-center">
                    {{ $t('app.project.ask_ai') }}
                </PrimaryButton>
            </div>

            <template v-if="ideas.length > 0">
                <div class="flex flex-row justify-start space-x-1">
                    <label class="label cursor-pointer flex space-x-1">
                        <span class="label-text text-xs">Redirect after add</span>
                        <input type="checkbox" class="toggle" :checked="redirectAfterAdd"
                               @click="redirectAfterAdd = !redirectAfterAdd"/>
                    </label>
                    <label class="label cursor-pointer flex space-x-2">
                        <span class="label-text text-xs">Add with auto-population</span>
                        <input type="checkbox" class="toggle focus-bg-black" :checked="addWithAutoPopulation"
                               @click="addWithAutoPopulation = !addWithAutoPopulation"/>
                    </label>
                    <label class="label cursor-pointer flex space-x-2">
                        <span class="label-text text-xs">Add public</span>
                        <input type="checkbox" class="toggle focus-bg-black" :checked="addPublic"
                               @click="addPublic = !addPublic"/>
                    </label>
                </div>

                <div v-for="idea in ideas" class="rounded border p-2 my-4 flex flex-col border-spacing-y-1">
                    <div class="mb-2">
                        <span class="underline font-bold">{{ $t('app.project.title') }}</span> : {{ idea.title }}
                    </div>
                    <div class="mb-2">
                        <span class="underline font-bold">{{ $t('app.project.description') }}</span> : {{
                            idea.description
                        }}
                    </div>
                    <div>
                        <PrimaryButton @click="addProject(idea.title, idea.description)">
                            {{ $t('app.ideas.add_as_project') }}
                        </PrimaryButton>
                    </div>
                </div>
            </template>
        </Box>
    </AuthenticatedLayout>
</template>
