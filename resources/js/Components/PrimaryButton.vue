<template>
  <button class="inline-flex items-center justify-center gap-2 rounded-btn px-5 py-2.5
  text-sm font-semibold
  text-primary-content
  bg-primary
  shadow-soft
  transition-all duration-150
  hover:bg-primary/90 hover:shadow-elevated
  active:scale-[0.98]
  focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-base-100
  disabled:opacity-50 disabled:pointer-events-none">
    <slot/>
  </button>
</template>
