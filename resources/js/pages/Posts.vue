<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Comment {
    id: number;
    content: string;
    user_id: number;
    user: User;
}

interface Post {
    id: number;
    title: string;
    content: string;
    user_id: number;
    user: User;
    comments: Comment[];
}

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const editingCommentId = ref<number | null>(null);
const editingPostId = ref<number | null>(null);

const commentForm = useForm({
    content: '',
});

const postForm = useForm({
    title: '',
    content: '',
});

const startEditingComment = (comment: Comment) => {
    editingCommentId.value = comment.id;
    commentForm.content = comment.content;
};

const cancelEditingComment = () => {
    editingCommentId.value = null;
    commentForm.reset();
};

const updateComment = (commentId: number) => {
    commentForm.put(route('comments.update', commentId), {
        onSuccess: () => {
            editingCommentId.value = null;
            commentForm.reset();
        },
    });
};

const deleteComment = (commentId: number) => {
    if (confirm('Are you sure to delete this comment?')) {
        useForm().delete(route('comments.delete', commentId));
    }
};

const startEditingPost = (post: Post) => {
    editingPostId.value = post.id;
    postForm.title = post.title;
    postForm.content = post.content;
};

const cancelEditingPost = () => {
    editingPostId.value = null;
    postForm.reset();
};

const updatePost = (postId: number) => {
    postForm.put(route('posts.update', postId), {
        onSuccess: () => {
            editingPostId.value = null;
            postForm.reset();
        },
    });
};

const deletePost = (postId: number) => {
    if (confirm('Are you sure to delete this post? (All comments will be lose)')) {
        useForm().delete(route('posts.delete', postId));
    }
};

const isCommentAuthor = (comment: Comment) => {
    return authUser.value && comment.user_id === authUser.value.id;
};

const isPostAuthor = (post: Post) => {
    return authUser.value && post.user_id === authUser.value.id;
};
</script>

<template>
    <Head title="Posts">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                    <Link
                        :href="route('posts')"
                        class="inline-block rounded-sm border border-[primary] bg-[#191400] px-5 py-1.5 text-sm leading-normal text-white hover:bg-[#322b1b] dark:border-[#EDEDEC] dark:bg-[#EDEDEC] dark:text-[#1b1b18] dark:hover:bg-[#d6d6d6]"
                    >
                        Posts
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 starting:opacity-0 lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse gap-4 overflow-hidden rounded-lg lg:max-w-4xl">
                <template v-if="page.props.posts.length">
                    <div
                        v-for="(post, i) in page.props.posts"
                        :key="i"
                        class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                    >
                        <div v-if="authUser && isPostAuthor(post)" class="flex justify-end gap-2 mb-2">
                            <button
                                @click="deletePost(post.id)"
                                class="px-2 py-1 text-red-400 border rounded hover:bg-red-50 dark:hover:bg-red-900"
                                title="Delete post"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                            <button
                                v-if="editingPostId !== post.id"
                                @click="startEditingPost(post)"
                                class="px-2 py-1 text-yellow-400 border rounded hover:bg-yellow-50 dark:hover:bg-yellow-900"
                                title="Edit post"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>

                        <div v-if="editingPostId === post.id">
                            <form @submit.prevent="updatePost(post.id)" class="mb-4">
                                <div class="mb-2">
                                    <label class="block mb-1">Title</label>
                                    <Title
                                        v-model="postForm.title"
                                        type="text"
                                        class="w-full rounded-md border p-2 text-sm dark:border-[#3E3E3A] dark:bg-[#242424]"
                                        required
                                    />
                                </div>
                                <div class="mb-2">
                                    <label class="block mb-1">Content</label>
                                    <textarea
                                        v-model="postForm.content"
                                        class="w-full rounded-md border p-2 text-sm dark:border-[#3E3E3A] dark:bg-[#242424]"
                                        rows="4"
                                        required
                                    ></textarea>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <button
                                        type="submit"
                                        class="px-3 py-1 text-xs text-white bg-green-500 rounded hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800"
                                        :disabled="postForm.processing"
                                    >
                                        Save
                                    </button>
                                    <button
                                        type="button"
                                        @click="cancelEditingPost"
                                        class="px-3 py-1 text-xs text-white bg-gray-500 rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-800"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <template v-else>
                            <h1 class="mb-1 font-medium">{{ post.title }}</h1>
                            <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                                {{ post.content }}
                            </p>
                        </template>

                        <hr class="mb-2" />
                        <h2 class="mb-1 font-medium">Comments</h2>
                        <template v-if="post.comments.length">
                            <div v-for="(comment, j) in post.comments" :key="j" class="mb-4">
                                <div class="flex items-center self-start gap-2">
                                    <span
                                        >{{ comment.user.name }} &lsaquo;<a :href="`mailto:${comment.user.email}`">{{ comment.user.email }}</a
                                        >&rsaquo;</span
                                    >

                                    <button
                                        v-if="authUser && isCommentAuthor(comment)"
                                        @click="deleteComment(comment.id)"
                                        class="px-2 py-1 text-red-400 border rounded hover:bg-red-50 dark:hover:bg-red-900"
                                        title="Delete comment"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button
                                        v-if="authUser && isCommentAuthor(comment) && editingCommentId !== comment.id"
                                        @click="startEditingComment(comment)"
                                        class="px-2 py-1 text-yellow-400 border rounded hover:bg-yellow-50 dark:hover:bg-yellow-900"
                                        title="Edit comment"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>

                                <div v-if="editingCommentId === comment.id" class="mt-2">
                                    <form @submit.prevent="updateComment(comment.id)">
                                        <textarea
                                            v-model="commentForm.content"
                                            class="w-full rounded-md border p-2 text-sm dark:border-[#3E3E3A] dark:bg-[#242424]"
                                            rows="3"
                                            required
                                        ></textarea>
                                        <div class="flex gap-2 mt-2">
                                            <button
                                                type="submit"
                                                class="px-3 py-1 text-xs text-white bg-green-500 rounded hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800"
                                                :disabled="commentForm.processing"
                                            >
                                                Save
                                            </button>
                                            <button
                                                type="button"
                                                @click="cancelEditingComment"
                                                class="px-3 py-1 text-xs text-white bg-gray-500 rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-800"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <p v-else class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ comment.content }}</p>
                            </div>
                        </template>
                        <template v-else>
                            <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">No comments yet</p>
                        </template>
                    </div>
                </template>
                <template v-else>
                    <div
                        class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                    >
                        <h1 class="mb-1 font-medium">No content yet!</h1>
                    </div>
                </template>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
