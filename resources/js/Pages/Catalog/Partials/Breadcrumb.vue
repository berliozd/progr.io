<script setup>
import {trans} from "laravel-vue-i18n";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    category: null
})
</script>
<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li>
                <Link :href="route('app.ideas.catalog')">
                    {{ trans('app.ideas.catalog.ideas_catalog') }}
                </Link>
            </li>
            <li>
                <Link :href="route('app.ideas.catalog.category', {category:props?.category?.code??''})">
                    {{ trans('app.ideas.catalog.category.' + props.category?.code) }}
                </Link>
            </li>
        </ul>
    </div>
</template>
