<script setup>
import CatalogLayout from '@/Layouts/CatalogLayout.vue';
import PageHeader from "@/Components/PageHeader.vue";
import SimpleLink from "@/Components/SimpleLink.vue";
import Collapsable from "@/Components/Collapsable.vue";
import Ad from "@/Pages/Catalog/Partials/Ad.vue";

import {computed, ref} from "vue";
import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {_} from 'lodash';
import {useStore} from "@/Composables/store.js";
import {trans} from "laravel-vue-i18n";
import {genericDescription, genericKeywords} from "@/Composables/seo.js";

const projects = ref(null)
const categories = ref(null)
const props = defineProps({'categoryCode': null})
const title = computed(() => {
    return trans('app.ideas.catalog.ideas_catalog') + ' - '
        + (props.categoryCode ?
            trans('app.ideas.catalog.category.' + props.categoryCode)
            : trans('app.ideas.catalog.categories.all_categories'))
})

const getProjects = async () => {
    try {
        useStore().setIsLoading(true)
        const response = await axios.get(
            '/api/projects/public/list', {
                params: {'category_code': props.categoryCode}
            }
        );
        projects.value = response.data
        useStore().setIsLoading(false)
    } catch (error) {
        console.log(error)
    }
}
const getCategories = async () => {
    try {
        useStore().setIsLoading(true)
        const response = await axios.get('/api/categories');
        categories.value = response.data
        useStore().setIsLoading(false)
    } catch (error) {
        console.log(error)
    }
}

const metaDescription = computed(() => {
    let description = genericDescription()
    if (props.categoryCode !== undefined) {
        description += ' Project ideas for category : ' + trans('app.ideas.catalog.category.' + props.categoryCode)
        return description
    }
    if (categories.value !== null) {
        const concatenatedCats = categories.value.map((category) => {
            return category.code
        }).join(', ')
        description += ' Project ideas for all the following categories : ' + concatenatedCats
        return description
    }
    return description
})

const metaKeywords = computed(() => {
    let keywords = genericKeywords()
    if (props.categoryCode !== undefined) {
        keywords += ', ' + trans('app.ideas.catalog.category.' + props.categoryCode)
    }
    return keywords;
})

getProjects();
getCategories();
</script>
<template>
    <Head>
        <title>{{title}}</title>
        <meta name="description" :content="metaDescription">
        <meta name="keywords" :content="metaKeywords">
    </Head>
    <CatalogLayout>
        <template #header>
            <PageHeader :title="title"/>
        </template>
        <Collapsable :title="$t('app.ideas.catalog.categories.title')">
            <div class="grid sm:grid-cols-4 grid-cols-3 text-xs sm:text-base grid-flow-row gap-4">
                <div v-for="category in categories">
                    <div class="border h-8 align-middle flex items-center justify-around text-center px-2">
                        <SimpleLink :href="route('app.ideas.catalog.category', { category:category.code})">
                            {{ $t('app.ideas.catalog.category.' + category.code) }}
                        </SimpleLink>
                    </div>
                </div>
                <div class="h-8">
                    <SimpleLink :href="route('app.ideas.catalog')" :class="'underline'">
                        {{ $t('app.ideas.catalog.categories.all_categories') }}
                    </SimpleLink>
                </div>
            </div>
        </Collapsable>
        <h1 class="text-3xl font-extrabold pb-6">
            {{
                props.categoryCode
                    ? $t(
                        'app.ideas.catalog.categories.category_projects',
                        {'category': $t('app.ideas.catalog.category.' + props.categoryCode)}
                    )
                    : $t('app.ideas.catalog.categories.all_categories_projects')
            }}
        </h1>
        <Ad/>
        <div v-if="projects?.length" class="text-info">{{
                $t(
                    'app.ideas.catalog.categories.nb_projects',
                    {'nb': projects?.length}
                )
            }}
        </div>
        <div v-if="projects?.length === 0">
            {{ $t('app.ideas.catalog.categories.no_projects') }}
        </div>
        <template v-else>
            <div class="grid sm:grid-cols-4 grid-cols-3 text-xs sm:text-base grid-flow-row gap-4">
                <div v-for="project in projects" class="">
                    <div class="border h-20 align-middle flex items-center justify-around text-center px-2">
                        <SimpleLink
                            :href="route('app.ideas.catalog.idea', {id:project.id, title:_.toLower(_.kebabCase(project.title)), category:project.category?.code??''})">
                            {{ project.title }}
                        </SimpleLink>
                    </div>
                </div>
            </div>
        </template>
    </CatalogLayout>
</template>
