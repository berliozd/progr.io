<script setup>
import CatalogLayout from '@/Layouts/CatalogLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import SimpleLink from "@/Components/SimpleLink.vue";

import {ref} from "vue";
import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {_} from 'lodash';
import {trans} from "laravel-vue-i18n";
import Collapsable from "@/Components/Collapsable.vue";

const projects = ref(null)
const categories = ref(null)
const title = ref(null)
const props = defineProps({'categoryCode': null})

const getProjects = async () => {
    try {
        const response = await axios.get(
            '/api/projects/public/list', {
                params: {'category_code': props.categoryCode}
            }
        );
        projects.value = response.data
    } catch (error) {
        console.log(error)
    }
}
const getCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data
    } catch (error) {
        console.log(error)
    }
}
getProjects();
getCategories();
</script>
<template>
    <Head :title="title"/>
    <CatalogLayout>
        <template #header>
            <PageHeader :title="title"/>
        </template>
        <Collapsable :title="$t('app.project.categories.title')">
            <div class="grid sm:grid-cols-5 grid-cols-4 text-xs sm:text-base grid-flow-row gap-4">
                <div v-for="category in categories">
                    <div class="border h-10 align-middle flex items-center justify-around text-center">
                        <SimpleLink :href="route('app.projects.ideas.category', { category:category.code})">
                            {{ trans('app.project.category.' + category.code) }}
                        </SimpleLink>
                    </div>
                </div>
                <div class="border h-10 align-middle flex items-center justify-around text-center">
                    <SimpleLink :href="route('app.projects.ideas')">
                        {{ trans('app.project.categories.all_categories') }}
                    </SimpleLink>
                </div>
            </div>
        </Collapsable>
        <h1 class="text-3xl font-extrabold pb-6">
            {{
                props.categoryCode
                    ? trans(
                        'app.project.categories.category_projects',
                        {'category': trans('app.project.category.' + props.categoryCode)}
                    )
                    : 'All categories projects'
            }}
        </h1>
        <div v-if="projects?.length" class="text-info">{{ trans('app.project.categories.nb_projects', {'nb': projects?.length})}}</div>
        <div v-if="projects?.length === 0">
            {{ $t('app.project.categories.no_projects') }}
        </div>
        <template v-else>
            <div class="grid sm:grid-cols-4 grid-cols-3 text-xs sm:text-base grid-flow-row gap-4">
                <div v-for="project in projects" class="">
                    <div class="border h-20 align-middle flex items-center justify-around text-center px-2">
                        <SimpleLink
                            :href="route('app.projects.idea', {id:project.id, title:_.toLower(_.kebabCase(project.title)), category:project.category?.code??''})">
                            {{ project.title }}
                        </SimpleLink>
                    </div>
                </div>
            </div>
        </template>
    </CatalogLayout>
</template>
