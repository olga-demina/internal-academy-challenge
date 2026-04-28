<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        :class="className"
        v-bind="$attrs"
    >
        <text
            x="50%"
            y="50%"
            dominant-baseline="central"
            text-anchor="middle"
            font-family="sans-serif"
            font-weight="700"
            font-size="18"
            fill="currentColor"
        >IA</text>
    </svg>
</template>
