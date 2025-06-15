<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Merchant Note List</DialogTitle>
                <DialogDescription>Make changes to your profile here. Click save when you're done.</DialogDescription>
            </DialogHeader>

            <div class="p-4">
                <div class="mb-4 flex items-center space-x-3">
                    <DropdownMenu>
                        <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                            {{ editForm.type || 'Select type' }}
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem @click="editForm.type = 'info'">Info</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.type = 'task'">Task</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.type = 'alert'">Alert</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <DropdownMenu>
                        <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                            {{ editForm.status || 'Select status' }}
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem @click="editForm.status = 'open'">Open</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.status = 'closed'">Closed</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.status = 'in_progress'">In Progress</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <div class="w-100% mb-4">
                    <Input v-model="editForm.title" />
                </div>

                <Textarea v-model="editForm.body" class="mb-4 w-full rounded border px-2 py-1" rows="4"></Textarea>
            </div>

            <DialogFooter class="flex-shrink-0">
                <Button class="cursor-pointer" @click="save()" variant="secondary">Save</Button>
                <Button class="cursor-pointer" @click="cancel" variant="destructive">Cancel</Button>
            </DialogFooter>
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
import axios from 'axios';
import { defineExpose, ref } from 'vue';

const showModal = ref(false);
const editForm = ref({
    title: '',
    body: '',
    type: '',
    status: '',
});

const save = async () => {
    try {
        await axios.post('/notes', editForm.value);
    } catch (err) {
        console.error(err);
    } finally {
        console.log('Success');
    }
};

const resetEditForm = () => {
    editForm.value = {
        title: '',
        body: '',
        type: '',
        status: '',
    };
};

const cancel = () => {
    showModal.value = false;
    resetEditForm();
};

const show = async () => {
    resetEditForm();
    showModal.value = true;
};

// Expose the dialog control to parent
defineExpose({
    show,
});
</script>
