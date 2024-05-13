<script setup>

const props = defineProps({
    'items': null,
    'item': null,
    'usePivot': false
})

const moveUp = (event, item, items) => {
    if (props.usePivot) {
        moveUpPivot(event, item, items);
        return;
    }
    const currentOrder = item.order;
    item.order = items[items.indexOf(item) - 1].order
    items[items.indexOf(item) - 1].order = currentOrder
    sortItems(items);
}
const moveUpPivot = (event, item, items) => {
    const currentOrder = item.pivot.order;
    item.pivot.order = items[items.indexOf(item) - 1].pivot.order
    items[items.indexOf(item) - 1].pivot.order = currentOrder
    sortItems(items);
}

const moveDown = (event, item, items) => {
    if (props.usePivot) {
        moveDownPivot(event, item, items);
        return;
    }
    const currentOrder = item.order;
    item.order = items[items.indexOf(item) + 1].order
    items[items.indexOf(item) + 1].order = currentOrder
    sortItems(items);
}
const moveDownPivot = (event, item, items) => {
    const currentOrder = item.pivot.order;
    item.pivot.order = items[items.indexOf(item) + 1].pivot.order
    items[items.indexOf(item) + 1].pivot.order = currentOrder
    sortItems(items);
}

const sortItems = (items) => {
    items.sort((itemA, itemB) => {
        if (props.usePivot) {
            return itemA.pivot.order > itemB.pivot.order ? 1 : -1
        } else {
            return itemA.order > itemB.order ? 1 : -1
        }
    });
}
</script>

<template>
    <div class="flex flex-row w-12 justify-end">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-arrow-up-from-line hover:cursor-pointer" @click="moveUp($event, item, items)"
             v-if="items[items.indexOf(item) - 1]">
            <path d="m18 9-6-6-6 6"/>
            <path d="M12 3v14"/>
            <path d="M5 21h14"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-arrow-down-from-line hover:cursor-pointer" @click="moveDown($event, item, items)"
             v-if="items[items.indexOf(item) + 1]">
            <path d="M19 3H5"/>
            <path d="M12 21V7"/>
            <path d="m6 15 6 6 6-6"/>
        </svg>
    </div>
</template>
