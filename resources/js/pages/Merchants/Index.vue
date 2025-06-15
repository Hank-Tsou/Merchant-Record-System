<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Merchant Lists" />

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead className="w-[100px]">ID</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Category</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Phone</TableHead>
                    <TableHead>Two FA</TableHead>
                    <TableHead>Note</TableHead>
                    <TableHead>Action</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in merchants.data" :key="index">
                    <TableCell className="font-medium">{{ item.id }}</TableCell>
                    <TableCell>{{ item.name }}</TableCell>
                    <TableCell>{{ item.category }}</TableCell>
                    <TableCell>{{ item.email }}</TableCell>
                    <TableCell>{{ item.phone }}</TableCell>
                    <TableCell>{{ item.two_factor_enabled }}</TableCell>
                    <TableCell>{{ item.has_notes }}</TableCell>
                    <TableCell>
                        <Button class="cursor-pointer" variant="secondary" @click="viewNote(item.id)">View</Button>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableFooter>
                <TableRow>
                    <TableCell :colspan="5" class="text-center">
                        <Pagination
                            :items-per-page="props.merchants.meta.per_page"
                            :total="props.merchants.meta.total"
                            :default-page="props.merchants.meta.current_page"
                        >
                            <PaginationContent v-slot="{ items }">
                                <Link v-if="props.merchants.links.prev" :href="props.merchants.links.prev" preserve-scroll>
                                    <PaginationPrevious />
                                </Link>

                                <template v-for="(link, index) in props.merchants.meta.links" :key="index">
                                    <Link v-if="Number(link.label)" :href="link.url" preserve-scroll>
                                        <PaginationItem :value="Number(link.label)" :is-active="link.active">
                                            {{ link.label }}
                                        </PaginationItem>
                                    </Link>
                                </template>

                                <Link v-if="props.merchants.links.next" :href="props.merchants.links.next" preserve-scroll>
                                    <PaginationNext />
                                </Link>
                            </PaginationContent>
                        </Pagination>
                    </TableCell>
                </TableRow>
            </TableFooter>
        </Table>
    </AppLayout>
    <NoteView ref="noteViewRef"></NoteView>
</template>

<script setup lang="ts">
import NoteView from '@/components/assessment/NoteView.vue';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Merchant {
    id: number;
    uid: string;
    name: string;
    phone?: string;
    email?: string;
    category?: string;
    has_notes: number;
    two_factor_enabled: boolean;
}

interface Props {
    merchants: {
        data: Merchant[];
        meta: any;
        links: any;
    };
}

const isLoading = ref(true);
const noteViewRef = ref<InstanceType<typeof NoteView> | null>(null);

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Merchant List',
        href: '/merchant',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const viewNote = (_id: number) => {
    noteViewRef.value?.show(_id);
};

onMounted(() => {
    isLoading.value = true;
});
</script>
