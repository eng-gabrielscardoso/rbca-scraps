<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    content: '',
});

const createPost = () => {
    form.post('/posts', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div>
        <h2 class="text-lg font-bold">Create New Post</h2>
        <form @submit.prevent="createPost">
            <div>
                <label for="title" class="block font-medium">Title</label>
                <input v-model="form.title" type="text" id="title" class="w-full mt-1 bg-transparent border rounded" required>
            </div>
            <div class="mt-4">
                <label for="content" class="block font-medium">Content</label>
                <textarea v-model="form.content" id="content" rows="3" class="w-full mt-1 bg-transparent border rounded" required></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25">
                    Create Post
                </button>
            </div>
        </form>
    </div>
</template>
