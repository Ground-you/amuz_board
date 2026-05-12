<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
      <Link href="/posts" class="text-blue-500 hover:underline">← 목록으로 돌아가기</Link>
    </div>

    <article class="bg-white p-8 shadow rounded-lg mb-10">
      <h1 class="text-4xl font-bold mb-4">{{ post.title }}</h1>
      <div class="flex items-center text-gray-500 mb-8 pb-4 border-b text-sm">
        <span class="mr-4">작성자: {{ post.user?.name || '익명' }}</span>
        <span>날짜: {{ new Date(post.created_at).toLocaleString() }}</span>
      </div>

      <div v-if="post.image_path" class="mb-8 rounded-lg overflow-hidden border">
        <img :src="'/storage/' + post.image_path" class="w-full h-auto">
      </div>

      <div class="text-lg leading-relaxed whitespace-pre-wrap mb-8">
        {{ post.content }}
      </div>

      <div class="flex gap-2 border-t pt-6">
        <Link :href="'/posts/' + post.id + '/edit'" class="text-sm bg-yellow-500 text-white px-3 py-1 rounded">수정</Link>
        <button @click="deletePost" class="text-sm bg-red-500 text-white px-3 py-1 rounded">삭제</button>
      </div>
    </article>

    <section class="mt-12 border-t pt-8">
      <h3 class="text-2xl font-bold mb-6">댓글</h3>

      <form @submit.prevent="submitComment" class="mb-8">
        <textarea v-model="commentForm.content" class="w-full border p-3 rounded-lg" placeholder="댓글을 입력하세요..." rows="2"></textarea>
        <div class="flex justify-end mt-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">등록</button>
        </div>
      </form>

      <div v-for="comment in post.comments.filter(c => !c.parent_id)" :key="comment.id" class="mb-6">
        
        <div class="p-4 bg-gray-50 rounded-lg border">
          <div class="flex justify-between items-center mb-2">
            <span class="font-bold text-gray-700 text-sm">{{ comment.user?.name || '익명' }}</span>
            <div class="flex gap-2 text-xs text-gray-400">
              <span>{{ new Date(comment.created_at).toLocaleString() }}</span>
              <button @click="openReply(comment.id)" class="hover:text-blue-600 font-bold">답글</button>
              <button @click="editComment(comment)" class="hover:text-blue-500">수정</button>
              <button @click="deleteComment(comment.id)" class="hover:text-red-500">삭제</button>
            </div>
          </div>

          <div v-if="editingId === comment.id">
            <textarea v-model="editForm.content" class="w-full border p-2 rounded mb-2"></textarea>
            <div class="flex gap-2 justify-end">
              <button @click="editingId = null" class="text-xs text-gray-500">취소</button>
              <button @click="updateComment(comment.id)" class="text-xs bg-blue-500 text-white px-2 py-1 rounded">저장</button>
            </div>
          </div>
          <p v-else class="text-gray-800">{{ comment.content }}</p>
        </div>

        <div v-if="replyingId === comment.id" class="ml-10 mt-2 mb-4">
          <textarea v-model="replyForm.content" class="w-full border p-2 rounded text-sm" placeholder="답글 내용을 입력하세요..." rows="2"></textarea>
          <div class="flex justify-end gap-2 mt-1">
            <button @click="replyingId = null" class="text-xs text-gray-500">취소</button>
            <button @click="submitReply(comment.id)" class="text-xs bg-blue-600 text-white px-3 py-1 rounded">답글 등록</button>
          </div>
        </div>

        <div v-for="reply in post.comments.filter(c => c.parent_id === comment.id)" :key="reply.id" class="ml-10 mt-2 p-3 bg-white rounded-lg border-l-4 border-blue-200 shadow-sm">
          <div class="flex justify-between items-center mb-1">
            <span class="font-bold text-sm text-gray-600">↳ {{ reply.user?.name || '익명' }}</span>
            <div class="flex gap-2 text-[10px] text-gray-400">
              <span>{{ new Date(reply.created_at).toLocaleString() }}</span>
              <button @click="editComment(reply)" class="hover:text-blue-500">수정</button>
              <button @click="deleteComment(reply.id)" class="hover:text-red-500">삭제</button>
            </div>
          </div>

          <div v-if="editingId === reply.id">
            <textarea v-model="editForm.content" class="w-full border p-2 rounded mb-2 text-sm"></textarea>
            <div class="flex gap-2 justify-end">
              <button @click="editingId = null" class="text-[10px]">취소</button>
              <button @click="updateComment(reply.id)" class="text-[10px] bg-blue-500 text-white px-2 py-1 rounded">저장</button>
            </div>
          </div>
          <p v-else class="text-sm text-gray-700">{{ reply.content }}</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ post: Object });

// 게시글 삭제
const deletePost = () => {
  if (confirm('게시글을 삭제할까요?')) router.delete('/posts/' + props.post.id);
};

// 새 부모 댓글 등록
const commentForm = useForm({ content: '', parent_id: null });
const submitComment = () => {
  commentForm.post(`/posts/${props.post.id}/comments`, {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
  });
};

// 댓글/대댓글 삭제 (하나의 함수로 공통 사용)
const deleteComment = (id) => {
  if (confirm('댓글을 삭제할까요?')) {
    router.delete('/comments/' + id, { preserveScroll: true });
  }
};

// 댓글/대댓글 수정 (하나의 로직으로 공통 사용)
const editingId = ref(null);
const editForm = useForm({ content: '' });

const editComment = (comment) => {
  editingId.value = comment.id;
  editForm.content = comment.content;
};

const updateComment = (id) => {
  editForm.patch('/comments/' + id, {
    preserveScroll: true,
    onSuccess: () => (editingId.value = null),
  });
};

// 대댓글(답글) 등록 로직
const replyingId = ref(null);
const replyForm = useForm({ content: '', parent_id: null });

const openReply = (id) => {
  replyingId.value = id;
  replyForm.content = '';
};

const submitReply = (parentId) => {
  replyForm.parent_id = parentId;
  replyForm.post(`/posts/${props.post.id}/comments`, {
    preserveScroll: true,
    onSuccess: () => {
      replyingId.value = null;
      replyForm.reset();
    },
  });
};
</script>