<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Merchant Lists" />

        <NoteFilter class="ps-6 pe-6 pt-8 pb-2" :creators="props.creators" :filters="props.filters"></NoteFilter>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Merchant</TableHead>
                    <TableHead>Title</TableHead>
                    <TableHead>Type</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Create By</TableHead>
                    <TableHead>Update At</TableHead>
                    <TableHead>Action</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in notes.data" :key="index">
                    <TableCell>{{ item.id }}</TableCell>
                    <TableCell>{{ item.merchant }}</TableCell>
                    <TableCell>{{ item.title }}</TableCell>
                    <TableCell
                        ><Badge
                            :class="[
                                'rounded px-2 py-1 text-xs font-semibold',
                                item.type === 'info'
                                    ? 'bg-blue-100 text-blue-800'
                                    : item.type === 'task'
                                      ? 'bg-green-100 text-green-800'
                                      : item.type === 'alert'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-gray-100 text-gray-800',
                            ]"
                        >
                            {{ item.type }}
                        </Badge></TableCell
                    >
                    <TableCell
                        ><Badge
                            :class="[
                                'rounded px-2 py-1 text-xs font-semibold',
                                item.status === 'in_progress'
                                    ? 'bg-blue-100 text-blue-800'
                                    : item.status === 'open'
                                      ? 'bg-green-100 text-green-800'
                                      : item.status === 'closed'
                                        ? 'bg-gray-100 text-gray-800'
                                        : 'bg-gray-100 text-gray-800',
                            ]"
                        >
                            {{ item.status }}
                        </Badge></TableCell
                    >
                    <TableCell>{{ item.created_by }}</TableCell>
                    <TableCell>{{ item.updated_at }}</TableCell>
                    <TableCell>
                        <Button class="cursor-pointer" variant="secondary" @click="viewNote(item)">View</Button>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableFooter class="bg-blue-100">
                <TableRow>
                    <TableCell :colspan="8" class="text-center">
                        <Pagination
                            :items-per-page="props.notes.meta.per_page"
                            :total="props.notes.meta.total"
                            :default-page="props.notes.meta.current_page"
                        >
                            <PaginationContent v-slot="{ items }">
                                <Link v-if="props.notes.links.prev" :href="props.notes.links.prev" preserve-scroll>
                                    <PaginationPrevious />
                                </Link>

                                <template v-for="(link, index) in props.notes.meta.links" :key="index">
                                    <Link v-if="Number(link.label)" :href="link.url" preserve-scroll>
                                        <PaginationItem :value="Number(link.label)" :is-active="link.active">
                                            {{ link.label }}
                                        </PaginationItem>
                                    </Link>
                                </template>

                                <Link v-if="props.notes.links.next" :href="props.notes.links.next" preserve-scroll>
                                    <PaginationNext />
                                </Link>
                            </PaginationContent>
                        </Pagination>
                    </TableCell>
                </TableRow>
            </TableFooter>
        </Table>
    </AppLayout>
    <ViewNotes ref="viewNoteRef"></ViewNotes>
</template>

<script setup lang="ts">
import NoteFilter from '@/components/assessment/NoteFilter.vue';
import ViewNotes from '@/components/assessment/ViewNotes.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { NoteList } from '@/types/note';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notes List',
        href: '/notes',
    },
];

const props = defineProps<NoteList>();
const isLoading = ref(true);
const viewNoteRef = ref<InstanceType<typeof ViewNotes> | null>(null);

const viewNote = (note: any) => {
    viewNoteRef.value?.show(note);
};

onMounted(() => {
    isLoading.value = true;
});
</script>
