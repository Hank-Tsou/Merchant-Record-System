<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Confirmation</DialogTitle>
            </DialogHeader>

            <div class="flex justify-center">{{ modalInfo.title }}</div>
            <div class="flex justify-center">{{ modalInfo.contents }}</div>

            <DialogFooter class="flex-shrink-0">
                <Button class="cursor-pointer" @click="cancel()" variant="secondary">Cancel</Button>
                <Button class="cursor-pointer" @click="confirm()" variant="destructive">Confirm</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<!-- MyDialog.vue -->
<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { defineExpose, ref } from 'vue';

const showModal = ref(false);
const modalInfo = ref({
    identifier: 0,
    title: '',
    contents: '',
});

const emits = defineEmits<{
    (e: 'confirm', data: any): void;
}>();

const confirm = () => {
    emits('confirm', modalInfo.value.identifier);
    showModal.value = false;
};

const cancel = () => {
    resetInfo();
    showModal.value = false;
};

const resetInfo = () => {
    modalInfo.value.title = '';
    modalInfo.value.contents = '';
};

const show = async (_title: string, _content: string, _identifier: number) => {
    modalInfo.value.identifier = _identifier;
    modalInfo.value.title = _title;
    modalInfo.value.contents = _content;
    showModal.value = true;
};

// Expose the dialog control to parent
defineExpose({
    show,
});
</script>
