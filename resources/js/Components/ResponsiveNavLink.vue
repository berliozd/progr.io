<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
  href: {
    type: String,
    required: true,
  },
  active: {
    type: Boolean,
  },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 font-medium ' +
        'border-primary text-secondary ' +
        'bg-primary/20 ' +
        'focus:outline-none focus:text-indigo-200 ' +
        'focus:bg-indigo-900 ' +
        'focus:border-indigo-300 ' +
        'transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start font-medium ' +
        'focus:outline-none ' +
        'focus:text-gray-200 ' +
        'focus:bg-gray-700 ' +
        'focus:border-gray-600 ' +
        'transition duration-150 ease-in-out'
);
</script>

<template>
  <Link :href="href" :class="classes">
    <slot/>
  </Link>
</template>
