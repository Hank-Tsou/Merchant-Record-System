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
};

const cancel = () => {
    modalInfo.value.title = '';
    modalInfo.value.contents = '';
    showModal.value = false;
};

const show = async (title: string, content: string, identifier: number) => {
    modalInfo.value.identifier = identifier;
    modalInfo.value.title = title;
    modalInfo.value.contents = content;
    showModal.value = true;
};

const hide = () => {
    showModal.value = false;
};

defineExpose({
    show,
    hide,
});
</script>
