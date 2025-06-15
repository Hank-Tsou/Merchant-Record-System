<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Merchant Note List</DialogTitle>
                <DialogDescription>Make changes to your profile here. Click save when you're done.</DialogDescription>
            </DialogHeader>

            <!-- Scrollable notes container -->
            <div class="mx-auto max-w-xl flex-1 overflow-y-auto p-4">
                <ul class="space-y-4">
                    <li v-for="(note, index) in notes" :key="note.id" class="rounded-lg border p-4 shadow-sm transition hover:shadow-md">
                        <div class="mb-4 flex items-center space-x-3">
                            <DropdownMenu v-if="editNoteIndex === index">
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

                            <DropdownMenu v-if="editNoteIndex === index">
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
                            <div v-if="editNoteIndex === index" class="w-100%">
                                <Input v-model="editForm.title" />
                            </div>
                            <h3 v-else class="text-lg font-semibold text-gray-800">{{ note.title }}</h3>
                        </div>

                        <!-- Body: show textarea if editing -->
                        <div v-if="editNoteIndex === index" class="mb-4">
                            <Textarea v-model="editForm.body" class="w-full rounded border px-2 py-1" rows="4"></Textarea>
                        </div>
                        <p v-else class="mb-4 text-gray-600">{{ note.body }}</p>

                        <div class="flex justify-end space-x-3">
                            <Button v-if="editNoteIndex !== index" class="cursor-pointer" @click="startEdit(index, note)" variant="secondary"
                                >Edit</Button
                            >

                            <Button v-if="editNoteIndex === index" class="cursor-pointer" @click="saveEdit(note)" variant="secondary">Save</Button>
                            <Button v-if="editNoteIndex === index" class="cursor-pointer" @click="cancelEdit" variant="destructive">Cancel</Button>

                            <Button v-if="editNoteIndex !== index" class="cursor-pointer" @click="showConfirm(note)" variant="destructive"
                                >Delete</Button
                            >
                        </div>
                    </li>
                </ul>
            </div>

            <DialogFooter class="flex-shrink-0">
                <Button class="cursor-pointer" @click="showAddNote()">Add Note</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <AddNote ref="addNoteRef"></AddNote>
    <ConfirmModal ref="confirmModalRef" @confirm="deleteNote"></ConfirmModal>
</template>

<!-- MyDialog.vue -->
<script setup lang="ts">
import AddNote from '@/components/assessment/AddNote.vue';
import ConfirmModal from '@/components/assessment/ConfirmModal.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import axios from 'axios';
import { defineExpose, ref } from 'vue';

const addNoteRef = ref<InstanceType<typeof AddNote> | null>(null);
const confirmModalRef = ref<InstanceType<typeof ConfirmModal> | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const notes = ref({} as any);
const editNoteIndex = ref(-1);
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
function startEdit(_index: number, _note: any) {
    editNoteIndex.value = _index;
    editForm.value.title = _note.title;
    editForm.value.body = _note.body;
    editForm.value.type = _note.type;
    editForm.value.status = _note.status;
}

function cancelEdit() {
    editNoteIndex.value = -1;
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
        editNoteIndex.value = -1;
    }
};

const deleteNote = async (_id: number) => {
    try {
        await axios.delete(`/notes/${_id}`);
    } catch (err) {
        console.error(err);
    } finally {
        notes.value = notes.value.filter((note: any) => note.id !== _id);
    }
};

const showAddNote = () => {
    addNoteRef.value?.show();
};

const show = async (merchantId: number) => {
    showModal.value = true;
    isLoading.value = true;
    try {
        const response = await axios.get(`/merchants/${merchantId}/notes`);
        notes.value = response.data.data;
    } catch (err) {
        console.error(err);
    } finally {
        isLoading.value = false;
    }
};

// Expose the dialog control to parent
defineExpose({
    show,
});
</script>
