<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Merchant Lists" />

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Note</TableHead>
                    <TableHead>Action</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(user, index) in users.data" :key="index" class="hover:bg-gray-100">
                    <TableCell>{{ user.id }}</TableCell>
                    <TableCell>{{ user.name }}</TableCell>
                    <TableCell>{{ user.email }}</TableCell>
                    <TableCell
                        ><Badge
                            :class="[
                                'rounded px-2 py-1 text-xs font-semibold',
                                user.has_notes > 0 ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600',
                            ]"
                        >
                            {{ user.has_notes }}
                        </Badge></TableCell
                    >
                    <TableCell>
                        <!-- View user notes -->
                        <Button class="cursor-pointer" variant="secondary" @click="getViewNotes(user.id)">View</Button>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableFooter class="bg-blue-100">
                <TableRow>
                    <TableCell :colspan="7" class="text-center">
                        <Pagination :items-per-page="users.meta.per_page" :total="users.meta.total" :default-page="users.meta.current_page">
                            <PaginationContent v-slot="{}">
                                <Link v-if="users.links.prev" :href="users.links.prev" preserve-scroll>
                                    <PaginationPrevious class="cursor-pointer" />
                                </Link>

                                <template v-for="(link, index) in users.meta.links" :key="index">
                                    <Link v-if="Number(link.label)" :href="link.url" preserve-scroll>
                                        <PaginationItem class="cursor-pointer" :value="Number(link.label)" :is-active="link.active">
                                            {{ link.label }}
                                        </PaginationItem>
                                    </Link>
                                </template>

                                <Link v-if="users.links.next" :href="users.links.next" preserve-scroll>
                                    <PaginationNext class="cursor-pointer" />
                                </Link>
                            </PaginationContent>
                        </Pagination>
                    </TableCell>
                </TableRow>
            </TableFooter>
        </Table>
    </AppLayout>

    <EntityNoteViewer ref="noteViewRef" @getViewNotes="getViewNotes" />
    <MessagePrompt ref="messagePromptRef" />
</template>

<script setup lang="ts">
import EntityNoteViewer from '@/components/assessment/EntityNoteViewer.vue';
import MessagePrompt from '@/components/assessment/MessagePrompt.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { IUserProps } from '@/types/user';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

defineProps<IUserProps>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User List',
        href: '/users',
    },
];

const isLoading = ref(true);
const noteViewRef = ref<InstanceType<typeof EntityNoteViewer> | null>(null);
const messagePromptRef = ref<InstanceType<typeof MessagePrompt> | null>(null);

const getViewNotes = async (userId: number, data?: any) => {
    try {
        const response = await axios.get(`/users/${userId}/notes`, {
            params: data,
        });
        noteViewRef.value?.show(userId, response.data.data, false);
    } catch (err) {
        messagePromptRef.value?.show(undefined, err.response?.data?.message);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    isLoading.value = true;
});
</script>
