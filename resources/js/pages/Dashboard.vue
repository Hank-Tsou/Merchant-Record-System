<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-[calc(100vh-100px)] flex-col gap-4 rounded-xl p-4">
            <div class="grid flex-1 grid-cols-1 gap-4 md:grid-cols-3">
                <div class="flex flex-col gap-4">
                    <div
                        class="relative flex-grow-[2] overflow-hidden rounded-2xl border border-gray-500/70 bg-white p-6 shadow-md dark:border-gray-200"
                    >
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Assessment Info</h2>
                                <span>Deadline: Wed, Jun 18</span>
                            </div>
                            <div class="flex flex-col">
                                <span>Submit Time: Tue, Jun 17</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <!-- Backend Column -->
                            <div>
                                <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-200">Backend</h3>
                                <ul class="mb-6 space-y-3 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Merchant model</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Note model + relation</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Seed merchant & note</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Eloquent CRUD operation</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> List notes for merchant</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Create note</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Update note</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" /> Delete note</li>
                                </ul>

                                <!-- Extra -->
                                <h4 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-200">Extra</h4>
                                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Documentation</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Notes List</li>
                                </ul>
                            </div>

                            <!-- Frontend Column -->
                            <div>
                                <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-200">Frontend</h3>
                                <ul class="mb-6 space-y-3 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Display merchants list</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />View merchant notes</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Add a new note via a form</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Edit or delete notes inline</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Style using TailwindCSS</li>
                                </ul>

                                <!-- Bonus Section inside Frontend Column -->
                                <h4 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-200">Bonus</h4>
                                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Search or filter notes</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Add basic authentication</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Associate notes to user</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Permissions for actions</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Human-readable time</li>
                                    <li class="flex items-center"><Check class="me-2" color="#198754" />Use Inertia.js</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex-grow overflow-hidden rounded-2xl border border-gray-500/70 bg-white p-6 shadow-md dark:border-gray-200">
                        <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-100">Requirements</h2>
                        <p class="mb-4 text-gray-600 dark:text-gray-300">Ensure the following versions are installed:</p>
                        <ul class="list-disc space-y-1 pl-6 text-gray-700 dark:text-gray-200">
                            <li>Laravel ≥ 12.x</li>
                            <li>PHP ≥ 8.x</li>
                            <li>Composer ≥ 2.x</li>
                            <li>Node.js ≥ 22.x</li>
                            <li>NPM ≥ 10.x</li>
                            <li>Vue.js 3</li>
                            <li>Tailwind CSS 4.x</li>
                            <li>Vite 6</li>
                        </ul>
                    </div>
                </div>

                <div class="relative flex-1 overflow-hidden rounded-xl border border-gray-500/70 md:col-span-2 dark:border-b-gray-200">
                    <iframe
                        src="https://fluttering-mountain-f82.notion.site/ebd/213c56aa362b80c29dd5ce69e6498bf0"
                        class="h-full w-full"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
