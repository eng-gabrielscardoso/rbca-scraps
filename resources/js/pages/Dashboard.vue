<script setup lang="ts">
import CreatePostModal from '@/components/CreatePostModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const showModal = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    name?: string;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <button @click="showModal = true" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Create New Post</button>
            <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="p-6 border rounded-lg shadow-lg">
                        <CreatePostModal />
                        <button @click="showModal = false" class="px-4 py-2 mt-4 text-sm font-semibold text-white bg-red-500 rounded hover:bg-red-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
