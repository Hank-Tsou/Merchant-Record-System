<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Note Details</DialogTitle>
                <DialogDescription>Make changes on note here. Click save when you're done.</DialogDescription>
            </DialogHeader>

            <!-- Scrollable notes container -->
            <div class="mx-auto w-full overflow-y-auto p-4">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!--------------->
                        <!-- Note Type -->
                        <!--------------->
                        <DropdownMenu v-if="editNote">
                            <DropdownMenuTrigger class="w-25 rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                <span class="flex items-center justify-between"
                                    >{{ editForm.type || 'Select type' }}<ChevronDown class="ms-3"
                                /></span>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem @click="editForm.type = NoteType.INFO">Info</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.type = NoteType.TASK">Task</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.type = NoteType.ALERT">Alert</DropdownMenuItem>
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
                        <DropdownMenu v-if="editNote">
                            <DropdownMenuTrigger class="w-35 rounded bg-gray-200 px-3 py-1 text-gray-800 hover:bg-gray-400">
                                <span class="flex items-center justify-between"
                                    >{{ editForm.status || 'Select status' }}<ChevronDown class="ms-3"
                                /></span>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.OPEN">Open</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.CLOSED">Closed</DropdownMenuItem>
                                <DropdownMenuItem @click="editForm.status = NoteStatus.IN_PROGRESS">In Progress</DropdownMenuItem>
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
                <div v-if="editNote" class="w-100% mb-4">
                    <Input v-model="editForm.title" />
                </div>
                <div v-else class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">{{ note.title }}</h3>
                    <span class="text-sm text-gray-500">Last Update: {{ note.updated_at }}</span>
                </div>

                <!---------------->
                <!-- Note Body -->
                <!---------------->
                <div v-if="editNote" class="mb-4">
                    <Textarea v-model="editForm.body" class="w-full rounded border px-2 py-1" rows="4"></Textarea>
                </div>
                <p v-else class="mb-4 text-gray-600">{{ note.body }}</p>
                <p v-if="editNote && formError" class="mb-4 rounded bg-red-100 px-3 py-2 text-center text-sm text-red-700">
                    All fields are required!
                </p>

                <!------------------>
                <!-- Button Group -->
                <!------------------>
                <div class="flex justify-end space-x-3">
                    <Button v-if="editNote" class="cursor-pointer" @click="saveEdit(note)" variant="secondary">Save</Button>
                    <Button v-if="editNote" class="cursor-pointer" @click="cancelEdit" variant="destructive">Cancel</Button>
                    <Button v-if="!editNote" class="cursor-pointer" @click="startEdit()" variant="secondary">Edit</Button>
                    <Button v-if="!editNote" class="cursor-pointer" @click="showConfirm(note)" variant="destructive">Delete</Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>

    <ConfirmModal ref="confirmModalRef" @confirm="deleteNote"></ConfirmModal>
    <MessagePrompt ref="messagePromptRef" />
</template>

<script setup lang="ts">
import ConfirmModal from '@/components/assessment/ConfirmModal.vue';
import MessagePrompt from '@/components/assessment/MessagePrompt.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { EditForm, NoteStatus, NoteType } from '@/types/note';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ChevronDown, UserPen } from 'lucide-vue-next';
import { defineExpose, ref } from 'vue';

const confirmModalRef = ref<InstanceType<typeof ConfirmModal> | null>(null);
const messagePromptRef = ref<InstanceType<typeof MessagePrompt> | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const formError = ref(false);
const note = ref({} as any);
const editNote = ref(false);
const editForm = ref<EditForm>({
    title: '',
    body: '',
    type: '',
    status: '',
});
function startEdit() {
    editNote.value = true;
    editForm.value.title = note.value.title;
    editForm.value.body = note.value.body;
    editForm.value.type = note.value.type;
    editForm.value.status = note.value.status;
}

function cancelEdit() {
    formError.value = false;
    editNote.value = false;
}

const showConfirm = (_note: any) => {
    confirmModalRef.value?.show('Are you sure you want to delete.', _note.title, _note.id);
};

const saveEdit = async (_note: any) => {
    if (!Object.values(editForm.value).every((value) => value !== '')) {
        formError.value = true;
        return;
    }

    formError.value = false;

    try {
        await axios.put(`/notes/${_note.id}`, editForm.value);
        _note.title = editForm.value.title;
        _note.body = editForm.value.body;
        _note.type = editForm.value.type;
        _note.status = editForm.value.status;
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        editNote.value = false;
    }
};

const deleteNote = async (_id: number) => {
    try {
        await axios.delete(`/notes/${_id}`);
        showModal.value = false;
        router.reload();
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        confirmModalRef.value?.hide();
    }
};

const show = async (_note: any) => {
    cancelEdit();
    showModal.value = true;
    isLoading.value = true;
    note.value = _note;
};

defineExpose({
    show,
});
</script>
