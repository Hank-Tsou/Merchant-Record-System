<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { Toaster } from '@/components/ui/sonner';
import { computed } from 'vue';
import 'vue-sonner/style.css';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() => props.class);
</script>

<template>
    <SidebarInset v-if="props.variant === 'sidebar'" :class="className">
        <slot />
    </SidebarInset>
    <main v-else class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl" :class="className">
        <slot />
    </main>
    <Toaster />
</template>
