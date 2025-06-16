<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Merchant Note List</DialogTitle>
                <DialogDescription>Make changes to your profile here. Click save when you're done.</DialogDescription>
            </DialogHeader>

            <form @submit.prevent="save" class="p-4">
                <div class="p-4">
                    <div class="mb-4 flex items-center space-x-3">
                        <!-- Note Type -->
                        <DropdownMenu>
                            <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                {{ editForm.type || 'Select type' }}
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem @click="editForm.type = NoteType.INFO">Info</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.type = NoteType.TASK">Task</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.type = NoteType.ALERT">Alert</DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <!-- Note Status -->
                        <DropdownMenu>
                            <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                {{ editForm.status || 'Select status' }}
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.OPEN">Open</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.CLOSED">Closed</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.IN_PROGRESS">In Progress</DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <!-- Note Title -->
                    <div class="w-100% mb-4">
                        <Input v-model="editForm.title" />
                    </div>

                    <!-- Note Body -->
                    <Textarea v-model="editForm.body" class="mb-4 w-full rounded border px-2 py-1" rows="4"></Textarea>
                    <p v-if="formError" class="mb-4 rounded bg-red-100 px-3 py-2 text-center text-sm text-red-700">All fields are required!</p>
                </div>

                <DialogFooter class="flex-shrink-0">
                    <Button class="cursor-pointer" type="submit" variant="secondary">Save</Button>
                    <Button class="cursor-pointer" @click="cancel" variant="destructive">Cancel</Button>
                </DialogFooter>
            </form>

            <!-- Button Group -->
        </DialogContent>
    </Dialog>
</template>

<!-- MyDialog.vue -->
<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { NewForm, NoteStatus, NoteType } from '@/types/note';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { defineExpose, ref } from 'vue';

const showModal = ref(false);
const formError = ref(false);
const editForm = ref<NewForm>({
    merchantId: -1,
    title: '',
    body: '',
    type: '',
    status: '',
});

const emits = defineEmits<{
    (e: 'refresh', merchantId: number): void;
}>();

const save = async () => {
    if (!Object.values(editForm.value).every((value) => value !== '')) {
        formError.value = true;
        return;
    }

    formError.value = false;

    try {
        await axios.post('/notes', editForm.value);
    } catch (err) {
        console.error(err);
    } finally {
        emits('refresh', editForm.value.merchantId);
        router.reload();
        showModal.value = false;
    }
};

const cancel = () => {
    showModal.value = false;
    resetEditForm();
};

const resetEditForm = () => {
    formError.value = false;
    editForm.value = {
        merchantId: -1,
        title: '',
        body: '',
        type: '',
        status: '',
    };
};

const show = async (merchantId: number) => {
    resetEditForm();
    editForm.value.merchantId = merchantId;
    showModal.value = true;
};

defineExpose({
    show,
});
</script>
