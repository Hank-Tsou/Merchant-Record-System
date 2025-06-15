<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Note Details</DialogTitle>
                <DialogDescription>Make changes on note here. Click save when you're done.</DialogDescription>
            </DialogHeader>

            <!-- Scrollable notes container -->
            <div class="mx-auto max-w-xl flex-1 overflow-y-auto p-4">
                <div class="mb-4 flex items-center space-x-3">
                    <DropdownMenu v-if="editNote">
                        <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                            {{ editForm.type || 'Select type' }}
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem @click="editForm.type = 'info'">Info</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.type = 'task'">Task</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.type = 'alert'">Alert</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <span v-else :class="noteTypeClass(note.type)" class="rounded px-2 py-1 text-xs font-semibold uppercase">
                        {{ note.type }}
                    </span>

                    <DropdownMenu v-if="editNote">
                        <DropdownMenuTrigger class="rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                            {{ editForm.status || 'Select status' }}
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem @click="editForm.status = 'open'">Open</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.status = 'closed'">Closed</DropdownMenuItem>
                            <DropdownMenuItem @click="editForm.status = 'in_progress'">In Progress</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <span v-else :class="statusClasses(note.status)" class="rounded px-2 py-1 text-xs font-semibold uppercase">
                        {{ note.status }}
                    </span>
                </div>

                <div class="mb-4">
                    <div v-if="editNote" class="w-100%">
                        <Input v-model="editForm.title" />
                    </div>
                    <h3 v-else class="text-lg font-semibold text-gray-800">{{ note.title }}</h3>
                </div>

                <!-- Body: show textarea if editing -->
                <div v-if="editNote" class="mb-4">
                    <Textarea v-model="editForm.body" class="w-full rounded border px-2 py-1" rows="4"></Textarea>
                </div>
                <p v-else class="mb-4 text-gray-600">{{ note.body }}</p>

                <div class="flex justify-end space-x-3">
                    <Button v-if="!editNote" class="cursor-pointer" @click="startEdit()" variant="secondary">Edit</Button>

                    <Button v-if="editNote" class="cursor-pointer" @click="saveEdit(note)" variant="secondary">Save</Button>
                    <Button v-if="editNote" class="cursor-pointer" @click="cancelEdit" variant="destructive">Cancel</Button>

                    <Button v-if="!editNote" class="cursor-pointer" @click="showConfirm(note)" variant="destructive">Delete</Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>

    <ConfirmModal ref="confirmModalRef" @confirm="deleteNote"></ConfirmModal>
</template>

<!-- MyDialog.vue -->
<script setup lang="ts">
import ConfirmModal from '@/components/assessment/ConfirmModal.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { defineExpose, ref } from 'vue';

const confirmModalRef = ref<InstanceType<typeof ConfirmModal> | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const note = ref({} as any);
const editNote = ref(false);
const editForm = ref({
    title: '',
    body: '',
    type: '',
    status: '',
});
type NoteStatus = 'open' | 'closed' | 'in progress' | string;
function statusClasses(status: NoteStatus): string {
    switch (status) {
        case 'open':
            return 'bg-green-100 text-green-800';
        case 'closed':
            return 'bg-gray-300 text-gray-800';
        case 'in progress':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return 'bg-blue-100 text-blue-800';
    }
}

type NoteType = 'info' | 'task' | 'alert';
function noteTypeClass(type: NoteType): string {
    switch (type) {
        case 'info':
            return 'bg-blue-100 text-blue-800 border border-blue-300';
        case 'task':
            return 'bg-green-100 text-green-800 border border-green-300';
        case 'alert':
            return 'bg-red-100 text-red-800 border border-red-300';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-300';
    }
}
function startEdit() {
    editNote.value = true;
    editForm.value.title = note.value.title;
    editForm.value.body = note.value.body;
    editForm.value.type = note.value.type;
    editForm.value.status = note.value.status;
}

function cancelEdit() {
    editNote.value = false;
}

const showConfirm = (_note: any) => {
    confirmModalRef.value?.show('Are you sure you want to delete.', _note.title, _note.id);
};

const saveEdit = async (_note: any) => {
    try {
        await axios.put(`/notes/${_note.id}`, editForm.value);
    } catch (err) {
        console.error(err);
    } finally {
        _note.title = editForm.value.title;
        _note.body = editForm.value.body;
        _note.type = editForm.value.type;
        _note.status = editForm.value.status;
        editNote.value = false;
    }
};

const deleteNote = async (_id: number) => {
    try {
        await axios.delete(`/notes/${_id}`);
        showModal.value = false;
        router.reload();
    } catch (err) {
        console.error(err);
    }
};

const show = async (_note: any) => {
    showModal.value = true;
    isLoading.value = true;

    note.value = _note;
};

// Expose the dialog control to parent
defineExpose({
    show,
});
</script>
