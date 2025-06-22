<template>
    <Dialog v-model:open="showModal">
        <DialogContent class="flex max-h-[600px] flex-col sm:max-w-[625px]">
            <DialogHeader class="flex-shrink-0">
                <DialogTitle>Assign to User</DialogTitle>
            </DialogHeader>

            <div class="mx-auto w-full overflow-y-auto p-4">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <Select v-model="selectedCreatorId">
                            <SelectTrigger class="w-[180px]">
                                <SelectValue placeholder="Select Creator" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Creator</SelectLabel>
                                    <SelectItem v-for="(item, index) in creators" :key="index" :value="item.id"> {{ item.name }} </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!------------------>
                <!-- Button Group -->
                <!------------------>
                <div class="flex justify-end space-x-3">
                    <Button class="cursor-pointer" @click="saveEdit()" variant="secondary">Save</Button>
                    <Button class="cursor-pointer" @click="cancelEdit" variant="destructive">Cancel</Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>

    <MessagePrompt ref="messagePromptRef" />
</template>

<script setup lang="ts">
import MessagePrompt from '@/components/assessment/MessagePrompt.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Creator } from '@/types/note';
import axios from 'axios';
import { defineExpose, ref } from 'vue';

const messagePromptRef = ref<InstanceType<typeof MessagePrompt> | null>(null);
const showModal = ref(false);
const isLoading = ref(false);
const noteId = ref(-1);
const selectedCreatorId = ref(-1);
const creators = ref<Creator[]>();
const formError = ref(false);

const saveEdit = async () => {
    if (selectedCreatorId.value == -1) {
        formError.value = true;
        return;
    }

    formError.value = false;

    try {
        await axios.put(`/notes/${noteId.value}`, {
            assigned_to: selectedCreatorId.value,
        });
        showModal.value = false;
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    }
};

const cancelEdit = () => {
    selectedCreatorId.value = -1;
    showModal.value = false;
};

const show = async (_noteId: number, _creators: Creator[]) => {
    showModal.value = true;
    isLoading.value = true;

    noteId.value = _noteId;
    creators.value = _creators;
};

defineExpose({
    show,
});
</script>
