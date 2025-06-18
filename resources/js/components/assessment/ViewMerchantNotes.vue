<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Merchant Note List</DialogTitle>
                <div class="flex items-center justify-between">
                    <DialogDescription>Make changes to your profile here. Click save when you're done.</DialogDescription>
                    <NotePopFilter @search="search"></NotePopFilter>
                </div>
            </DialogHeader>

            <!-- Scrollable notes container -->
            <div class="mx-auto w-full overflow-y-auto p-4">
                <ul class="space-y-4">
                    <li
                        v-for="(note, index) in notes"
                        :key="note.id"
                        class="rounded-lg border p-4"
                        style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!--------------->
                                <!-- Note Type -->
                                <!--------------->
                                <DropdownMenu v-if="editNoteIndex === index">
                                    <DropdownMenuTrigger class="w-25 rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                        <span class="flex items-center justify-between"
                                            >{{ editForm.type || 'Select type' }}<ChevronDown class="ms-3"
                                        /></span>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent>
                                        <DropdownMenuItem @click="editForm.type = NoteType.INFO" class="cursor-pointer">Info</DropdownMenuItem>
                                        <DropdownMenuItem @click="editForm.type = NoteType.TASK" class="cursor-pointer">Task</DropdownMenuItem>
                                        <DropdownMenuItem @click="editForm.type = NoteType.ALERT" class="cursor-pointer">Alert</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                                <span
                                    v-else
                                    class="rounded px-2 py-1 text-xs font-semibold uppercase"
                                    :class="{
                                        'border border-green-300 bg-green-100 text-green-800': note.type === 'info',
                                        'border border-blue-300 bg-blue-100 text-blue-800': note.type === 'task',
                                        'border border-red-300 bg-red-100 text-red-800': note.type === 'alert',
                                    }"
                                >
                                    {{ note.type }}
                                </span>

                                <!----------------->
                                <!-- Note Status -->
                                <!----------------->
                                <DropdownMenu v-if="editNoteIndex === index">
                                    <DropdownMenuTrigger class="w-35 rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                        <span class="flex items-center justify-between"
                                            >{{ editForm.status || 'Select status' }}<ChevronDown class="ms-3"
                                        /></span>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent>
                                        <DropdownMenuItem @click="editForm.status = NoteStatus.OPEN" class="cursor-pointer">Open</DropdownMenuItem>
                                        <DropdownMenuItem @click="editForm.status = NoteStatus.CLOSED" class="cursor-pointer"
                                            >Closed</DropdownMenuItem
                                        >
                                        <DropdownMenuItem @click="editForm.status = NoteStatus.IN_PROGRESS" class="cursor-pointer"
                                            >In Progress</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                                <span
                                    v-else
                                    class="rounded px-2 py-1 text-xs font-semibold uppercase"
                                    :class="{
                                        'bg-green-100 text-green-800': note.status === 'open',
                                        'bg-gray-300 text-gray-800': note.status === 'closed',
                                        'bg-blue-100 text-blue-800': note.status === 'in_progress',
                                    }"
                                >
                                    {{ note.status }}
                                </span>
                            </div>

                            <div class="flex items-center">
                                <UserPen :size="12" class="h-5 w-5 rounded-full bg-gray-200" />
                                <span class="ms-3 flex text-sm">{{ note.created_by }}</span>
                            </div>
                        </div>

                        <!---------------->
                        <!-- Note Title -->
                        <!---------------->
                        <div v-if="editNoteIndex === index" class="w-100% mb-4">
                            <Input v-model="editForm.title" />
                        </div>
                        <div v-else class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">{{ note.title }}</h3>
                            <span class="text-sm text-gray-500">Last Update: {{ note.updated_at }}</span>
                        </div>

                        <!---------------->
                        <!-- Note Body -->
                        <!---------------->
                        <div v-if="editNoteIndex === index" class="mb-4">
                            <Textarea v-model="editForm.body" class="w-full rounded border px-2 py-1" rows="4"></Textarea>
                        </div>
                        <p v-else class="mb-4 text-gray-600">{{ note.body }}</p>
                        <p v-if="editNoteIndex === index && formError" class="mb-4 rounded bg-red-100 px-3 py-2 text-center text-sm text-red-700">
                            All fields are required!
                        </p>

                        <!------------------>
                        <!-- Button Group -->
                        <!------------------>
                        <div class="flex justify-end space-x-3">
                            <Button v-if="editNoteIndex === index" class="cursor-pointer" @click="saveEdit(note)" variant="secondary">Save</Button>
                            <Button v-if="editNoteIndex === index" class="cursor-pointer" @click="cancelEdit" variant="destructive">Cancel</Button>
                            <Button v-if="editNoteIndex !== index" class="cursor-pointer" @click="startEdit(index, note)" variant="secondary"
                                >Edit</Button
                            >
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

    <AddNote ref="addNoteRef" @refresh="show"></AddNote>
    <ConfirmModal ref="confirmModalRef" @confirm="deleteNote"></ConfirmModal>
    <MessagePrompt ref="messagePromptRef" />
</template>

<script setup lang="ts">
import AddNote from '@/components/assessment/AddNote.vue';
import ConfirmModal from '@/components/assessment/ConfirmModal.vue';
import MessagePrompt from '@/components/assessment/MessagePrompt.vue';
import NotePopFilter from '@/components/assessment/NotePopFilter.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { EditForm, Note, NoteStatus, NoteType } from '@/types/note';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ChevronDown, UserPen } from 'lucide-vue-next';
import { defineExpose, ref } from 'vue';

const addNoteRef = ref<InstanceType<typeof AddNote> | null>(null);
const confirmModalRef = ref<InstanceType<typeof ConfirmModal> | null>(null);
const messagePromptRef = ref<InstanceType<typeof MessagePrompt> | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const formError = ref(false);
const notes = ref<Note[]>([]);
const editNoteIndex = ref(-1);
const merchantId = ref(-1);
const editForm = ref<EditForm>({
    title: '',
    body: '',
    type: '',
    status: '',
});

function startEdit(noteIndex: number, note: Note) {
    editNoteIndex.value = noteIndex;
    editForm.value.title = note.title;
    editForm.value.body = note.body;
    editForm.value.type = note.type;
    editForm.value.status = note.status;
}

function cancelEdit() {
    formError.value = false;
    editNoteIndex.value = -1;
}

const saveEdit = async (note: any) => {
    if (!Object.values(editForm.value).every((value) => value !== '')) {
        formError.value = true;
        return;
    }

    formError.value = false;

    try {
        await axios.put(`/notes/${note.id}`, editForm.value);
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        note.title = editForm.value.title;
        note.body = editForm.value.body;
        note.type = editForm.value.type;
        note.status = editForm.value.status;
        editNoteIndex.value = -1;
    }
};

const deleteNote = async (noteId: number) => {
    try {
        await axios.delete(`/notes/${noteId}`);
        notes.value = notes.value.filter((note: any) => note.id !== noteId);
        router.reload();
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        confirmModalRef.value?.hide();
    }
};

const showAddNote = () => {
    addNoteRef.value?.show(merchantId.value);
};

const showConfirm = (note: any) => {
    confirmModalRef.value?.show('Are you sure you want to delete.', note.title, note.id);
};

const search = async (_data: any) => {
    try {
        const response = await axios.get(`/merchants/${merchantId.value}/notes`, {
            params: _data,
        });
        notes.value = response.data.data;
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        isLoading.value = false;
    }
};

const show = async (_merchantId: number) => {
    showModal.value = true;
    isLoading.value = true;
    cancelEdit();

    merchantId.value = _merchantId;
    try {
        const response = await axios.get(`/merchants/${_merchantId}/notes`);
        notes.value = response.data.data;
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        isLoading.value = false;
    }
};

defineExpose({
    show,
});
</script>
