<template>
    <AlertDialog v-model:open="showDialog">
        <AlertDialogTrigger>Open</AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ description }}
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction>Continue</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>

<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { defineExpose, ref } from 'vue';

const showDialog = ref(false);
const title = ref('');
const description = ref('');

const show = (_title?: string, _description?: string) => {
    title.value = _title || 'Notification';
    description.value = _description || 'Something went wrong.';
    showDialog.value = true;
};

defineExpose({
    show,
});
</script>
