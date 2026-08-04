<script setup lang="ts">
withDefaults(
  defineProps<{
    type?: 'button' | 'submit' | 'reset'
    variant?: 'primary' | 'outline' | 'ghost' | 'danger'
    size?: 'sm' | 'md' | 'icon'
    disabled?: boolean
    fullWidth?: boolean
  }>(),
  {
    type: 'button',
    variant: 'primary',
    size: 'md',
    disabled: false,
    fullWidth: false,
  },
)

const variantClasses = {
  primary:
    'text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 dark:disabled:bg-blue-800/80 border border-transparent',
  outline:
    'text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60',
  ghost:
    'text-gray-600 dark:text-gray-300 bg-transparent border border-transparent hover:bg-gray-100 dark:hover:bg-gray-700/60',
  danger:
    'text-white bg-red-600 hover:bg-red-700 disabled:bg-red-400 border border-transparent',
}

const sizeClasses = {
  sm: 'px-3 py-1 text-xs rounded-md',
  md: 'px-4 py-2 text-sm rounded-lg',
  icon: 'p-2 rounded-md',
}
</script>

<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[
      'inline-flex items-center justify-center gap-2 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer',
      variantClasses[variant],
      sizeClasses[size],
      fullWidth ? 'w-full' : '',
    ]"
  >
    <slot name="icon" />
    <slot />
  </button>
</template>
