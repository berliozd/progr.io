<template>
    <button
        class="inline-flex items-center justify-center gap-2 rounded-btn px-4 py-2
        text-sm font-semibold text-white
        bg-error
        shadow-soft
        transition-all duration-150
        hover:bg-error/90 hover:shadow-elevated
        active:scale-[0.98]
        focus:outline-none focus-visible:ring-2 focus-visible:ring-error/40 focus-visible:ring-offset-2 focus-visible:ring-offset-base-100
        disabled:opacity-50 disabled:pointer-events-none"
    >
        <slot />
    </button>
</template>
