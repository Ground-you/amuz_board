<template>
  <Layout>
    <div class="max-w-3xl mx-auto">
      <header class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
          {{ post.title }}
        </h1>
        <div class="flex justify-center items-center gap-4 text-slate-500 text-sm font-medium">
          <span class="text-slate-900 font-bold underline decoration-emerald-500 underline-offset-4">{{ post.user?.name || 'Hwang Jin-heon' }}</span>
          <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
          <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
        </div>
      </header>

      <article class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 overflow-hidden mb-16 border border-white">
        <div v-if="post.image_path" class="w-full bg-slate-50 border-b border-slate-100 flex justify-center">
          <img :src="'/storage/' + post.image_path" class="max-w-full max-h-[700px] object-contain">
        </div>
        
        <div class="p-8 md:p-12">
          <div class="text-lg leading-[1.8] whitespace-pre-wrap text-slate-700 mb-12">
            {{ post.content }}
          </div>

          <div class="flex justify-end gap-4 pt-8 border-t border-slate-50">
            <Link :href="`/posts/${post.id}/edit`" class="text-xs font-black text-slate-300 hover:text-amber-500 transition tracking-widest uppercase">Edit</Link>
            <button @click="deletePost" class="text-xs font-black text-slate-300 hover:text-red-500 transition tracking-widest uppercase">Delete</button>
          </div>
        </div>
      </article>

      <section class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 p-8 md:p-12 border border-white">
        <h3 class="text-xl font-bold text-slate-900 mb-8 flex items-center gap-3">
          Comments <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-lg text-sm">{{ post.comments.length }}</span>
        </h3>

        <form @submit.prevent="submitComment" class="mb-12">
          <div class="relative group">
            <textarea 
              v-model="commentForm.content" 
              class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-5 pr-16 focus:bg-white focus:border-emerald-500 transition-all outline-none resize-none text-slate-700" 
              placeholder="따뜻한 댓글을 남겨주세요..." 
              rows="3"
            ></textarea>
            <button type="submit" class="absolute right-3 bottom-3 bg-slate-900 text-white p-3 rounded-xl hover:bg-emerald-600 transition-all shadow-lg active:scale-95">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
              </svg>
            </button>
          </div>
        </form>

        <div v-for="comment in post.comments.filter(c => !c.parent_id)" :key="comment.id" class="mb-10 last:mb-0">
          <div class="group">
            <div class="flex justify-between items-center mb-3">
              <span class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                {{ comment.user?.name || '익명' }}
              </span>
              <div class="flex gap-4 text-[10px] font-black text-slate-300 uppercase tracking-tighter">
                <button @click="openReply(comment.id)" class="hover:text-emerald-500">Reply</button>
                <button @click="editComment(comment)" class="hover:text-amber-500">Edit</button>
                <button @click="deleteComment(comment.id)" class="hover:text-red-500">Delete</button>
              </div>
            </div>
            
            <div v-if="editingId === comment.id" class="mt-2">
              <textarea v-model="editForm.content" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl p-4 text-sm"></textarea>
              <div class="flex justify-end gap-2 mt-2">
                <button @click="editingId = null" class="text-[10px] font-bold text-slate-400">CANCEL</button>
                <button @click="updateComment(comment.id)" class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold">SAVE</button>
              </div>
            </div>
            <p v-else class="text-slate-600 text-[15px] leading-relaxed pl-4 border-l-2 border-slate-100 group-hover:border-emerald-200 transition-colors">{{ comment.content }}</p>
          </div>

          <div v-if="replyingId === comment.id" class="ml-10 mt-6 bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
            <textarea v-model="replyForm.content" class="w-full bg-white border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="답글을 작성하세요..."></textarea>
            <div class="flex justify-end gap-2 mt-3">
              <button @click="replyingId = null" class="text-[10px] font-bold text-slate-400">CANCEL</button>
              <button @click="submitReply(comment.id)" class="bg-emerald-600 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold">SUBMIT</button>
            </div>
          </div>

          <div v-for="reply in post.comments.filter(c => c.parent_id === comment.id)" :key="reply.id" 
               class="ml-10 mt-6 p-5 bg-slate-50/50 rounded-[1.5rem] border-l-4 border-emerald-500/30">
            <div class="flex justify-between items-center mb-2">
              <span class="font-bold text-xs text-slate-700">↳ {{ reply.user?.name || '익명' }}</span>
              <button @click="deleteComment(reply.id)" class="text-[9px] font-black text-slate-300 hover:text-red-500 uppercase">Delete</button>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">{{ reply.content }}</p>
          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ post: Object });

// 로직 연동
const deletePost = () => { if (confirm('정말 삭제하시겠습니까?')) router.delete('/posts/' + props.post.id); };
const commentForm = useForm({ content: '', parent_id: null });
const submitComment = () => { commentForm.post(`/posts/${props.post.id}/comments`, { preserveScroll: true, onSuccess: () => commentForm.reset() }); };
const deleteComment = (id) => { if (confirm('댓글을 삭제하시겠습니까?')) router.delete('/comments/' + id, { preserveScroll: true }); };
const editingId = ref(null);
const editForm = useForm({ content: '' });
const editComment = (comment) => { editingId.value = comment.id; editForm.content = comment.content; };
const updateComment = (id) => { editForm.patch('/comments/' + id, { preserveScroll: true, onSuccess: () => (editingId.value = null) }); };
const replyingId = ref(null);
const replyForm = useForm({ content: '', parent_id: null });
const openReply = (id) => { replyingId.value = id; replyForm.content = ''; };
const submitReply = (parentId) => { replyForm.parent_id = parentId; replyForm.post(`/posts/${props.post.id}/comments`, { preserveScroll: true, onSuccess: () => { replyingId.value = null; replyForm.reset(); } }); };
</script>