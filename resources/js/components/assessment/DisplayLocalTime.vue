<template>
    <span>{{ formattedDate }}</span>
</template>

<script setup lang="ts">
import moment from 'moment';
import { computed } from 'vue';

type DateFormat = 'withSeconds' | 'withoutSeconds';
const props = defineProps<{
    dateIsoString: string;
    format?: DateFormat;
}>();

const format = props.format ?? 'withSeconds';
const formatMap: Record<DateFormat, string> = {
    withSeconds: 'YYYY-MM-DD HH:mm:ss',
    withoutSeconds: 'YYYY-MM-DD HH:mm',
};

const date = computed(() => {
    const m = moment.utc(props.dateIsoString);
    return m.isValid() ? m.local() : null;
});

const formattedDate = computed(() => {
    if (!date.value) return 'Invalid date';

    return date.value.format(formatMap[format]);
});
</script>
