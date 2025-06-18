<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Merchant Lists" />

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Category</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Phone</TableHead>
                    <TableHead>Note</TableHead>
                    <TableHead>Action</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(merchant, index) in merchants.data" :key="index" class="hover:bg-gray-100">
                    <TableCell>{{ merchant.id }}</TableCell>
                    <TableCell>{{ merchant.name }}</TableCell>
                    <TableCell>{{ merchant.category }}</TableCell>
                    <TableCell>{{ merchant.email }}</TableCell>
                    <TableCell>{{ merchant.phone }}</TableCell>
                    <TableCell
                        ><Badge
                            :class="[
                                'rounded px-2 py-1 text-xs font-semibold',
                                merchant.has_notes > 0 ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600',
                            ]"
                        >
                            {{ merchant.has_notes }}
                        </Badge></TableCell
                    >
                    <TableCell>
                        <!-- View merchant notes -->
                        <Button class="cursor-pointer" variant="secondary" @click="viewNote(merchant.id)">View</Button>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableFooter class="bg-blue-100">
                <TableRow>
                    <TableCell :colspan="7" class="text-center">
                        <Pagination
                            :items-per-page="props.merchants.meta.per_page"
                            :total="props.merchants.meta.total"
                            :default-page="props.merchants.meta.current_page"
                        >
                            <PaginationContent v-slot="{}">
                                <Link v-if="props.merchants.links.prev" :href="props.merchants.links.prev" preserve-scroll>
                                    <PaginationPrevious class="cursor-pointer" />
                                </Link>

                                <template v-for="(link, index) in props.merchants.meta.links" :key="index">
                                    <Link v-if="Number(link.label)" :href="link.url" preserve-scroll>
                                        <PaginationItem class="cursor-pointer" :value="Number(link.label)" :is-active="link.active">
                                            {{ link.label }}
                                        </PaginationItem>
                                    </Link>
                                </template>

                                <Link v-if="props.merchants.links.next" :href="props.merchants.links.next" preserve-scroll>
                                    <PaginationNext class="cursor-pointer" />
                                </Link>
                            </PaginationContent>
                        </Pagination>
                    </TableCell>
                </TableRow>
            </TableFooter>
        </Table>
    </AppLayout>

    <ViewMerchantNotes ref="noteViewRef" />
</template>

<script setup lang="ts">
import ViewMerchantNotes from '@/components/assessment/ViewMerchantNotes.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { IMerchantProps } from '@/types/merchant';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Merchant List',
        href: '/merchant',
    },
];

const isLoading = ref(true);
const props = defineProps<IMerchantProps>();
const noteViewRef = ref<InstanceType<typeof ViewMerchantNotes> | null>(null);

const viewNote = (merchantId: number) => {
    noteViewRef.value?.show(merchantId);
};

onMounted(() => {
    isLoading.value = true;
});
</script>
