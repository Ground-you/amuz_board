<template>
  <Layout>
    <div class="max-w-3xl mx-auto">
      <header class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 mb-6 leading-tight">
          {{ post.title }}
        </h1>
        <div class="flex justify-center items-center gap-4 text-slate-500 dark:text-slate-400 text-sm font-medium">
          <span class="text-slate-900 dark:text-slate-200 font-bold underline decoration-emerald-500 underline-offset-4">
            {{ post.user?.name || 'Hwang Jin-heon' }}
          </span>
          <span class="w-1 h-1 bg-slate-300 dark:bg-slate-700 rounded-full"></span>
          <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
        </div>
      </header>

      <article class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/40 dark:shadow-none overflow-hidden mb-16 border border-white dark:border-slate-800">
        <div v-if="post.image_path" class="w-full bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/60 flex justify-center">
          <img :src="'/storage/' + post.image_path" class="max-w-full max-h-175 object-contain">
        </div>
        
        <div class="p-8 md:p-12">
          <div class="text-lg leading-[1.8] whitespace-pre-wrap text-slate-700 dark:text-slate-300 mb-12">
            {{ post.content }}
          </div>

          <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
            <button 
              @click="toggleLike"
              class="flex items-center gap-2.5 px-6 py-3 rounded-2xl border font-bold text-sm transition-all active:scale-95 shadow-sm"
              :class="post.is_liked 
                ? 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-900/60 text-red-600 dark:text-red-400' 
                : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 24 24" 
                :fill="post.is_liked ? 'currentColor' : 'none'" 
                stroke="currentColor" 
                stroke-width="2" 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                class="w-5 h-5 transition-transform duration-300"
                :class="{ 'scale-125': post.is_liked }"
              >
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
              </svg>
              <span>좋아요 {{ post.likes_count || 0 }}</span>
            </button>

            <div v-if="$page.props.auth?.user?.id === post.user_id" class="flex items-center gap-3">
              <Link :href="'/posts/' + post.id + '/edit'" class="px-5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                수정
              </Link>
              <button @click="deletePost" class="px-5 py-2.5 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/40 text-sm font-bold text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/60 hover:border-red-200 dark:hover:border-red-800 transition">
                삭제
              </button>
            </div>
          </div>
        </div>
      </article>

      <section class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/40 dark:shadow-none p-8 md:p-12 border border-white dark:border-slate-800">
        <h3 class="text-xl font-black text-slate-900 dark:text-slate-100 mb-8 tracking-tighter">
          댓글 <span class="text-emerald-500">{{ post.comments?.length || 0 }}</span>
        </h3>

        <form @submit.prevent="submitComment" class="mb-10">
          <div class="relative bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 p-2 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent transition-all">
            <textarea v-model="commentForm.content" placeholder="따뜻한 댓글을 남겨주세요." rows="3" class="w-full bg-transparent border-none outline-none resize-none p-4 text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400 dark:placeholder:text-slate-600"></textarea>
            <div class="flex justify-end p-2 border-t border-slate-200/50 dark:border-slate-800/60">
              <button type="submit" class="bg-slate-900 dark:bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-600 dark:hover:bg-emerald-500 transition shadow-md" :disabled="commentForm.processing">등록</button>
            </div>
          </div>
        </form>

        <div class="space-y-6">
          <div v-for="comment in post.comments?.filter(c => !c.parent_id)" :key="comment.id" class="border-b border-slate-100 dark:border-slate-800/60 pb-6 last:border-none last:pb-0">
            
            <div class="flex justify-between items-start mb-3">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-bold text-white text-xs">
                  {{ comment.user?.name?.substring(0,1) || 'U' }}
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ comment.user?.name }}</h4>
                  <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                </div>
              </div>
              
              <div v-if="$page.props.auth?.user?.id === comment.user_id && editingId !== comment.id" class="flex gap-2 text-xs font-bold text-slate-400 dark:text-slate-500">
                <button @click="editComment(comment)" class="hover:text-slate-600 dark:hover:text-slate-300 transition">수정</button>
                <button @click="deleteComment(comment.id)" class="hover:text-red-500 transition">삭제</button>
              </div>
            </div>

            <div v-if="editingId === comment.id" class="mt-2 pl-11">
              <form @submit.prevent="updateComment(comment.id)">
                <textarea v-model="editForm.content" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                <div class="flex gap-2 mt-2 justify-end">
                  <button type="button" @click="editingId = null" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-lg hover:bg-slate-300 dark:hover:bg-slate-700">취소</button>
                  <button type="submit" class="px-3 py-1.5 bg-slate-900 dark:bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 dark:hover:bg-emerald-500">저장</button>
                </div>
              </form>
            </div>
            <p v-else class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed pl-11 mb-3">{{ comment.content }}</p>

            <div v-if="editingId !== comment.id" class="pl-11 mb-4">
              <button @click="openReply(comment.id)" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">답글 쓰기</button>
            </div>

            <div v-if="replyingId === comment.id" class="pl-11 mb-4">
              <form @submit.prevent="submitReply(comment.id)">
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-2 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent transition-all">
                  <textarea v-model="replyForm.content" placeholder="답글을 입력하세요." rows="2" class="w-full bg-transparent border-none outline-none resize-none p-3 text-sm text-slate-700 dark:text-slate-300"></textarea>
                  <div class="flex justify-end gap-2 p-1 border-t border-slate-200/50 dark:border-slate-800/50">
                    <button type="button" @click="replyingId = null" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-lg hover:bg-slate-300 dark:hover:bg-slate-700">취소</button>
                    <button type="submit" class="px-3 py-1.5 bg-slate-900 dark:bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 dark:hover:bg-emerald-500">등록</button>
                  </div>
                </div>
              </form>
            </div>

            <div class="pl-11 space-y-4 mt-4 bg-slate-50/50 dark:bg-slate-950/40 rounded-2xl p-4 border border-slate-100/50 dark:border-slate-800/40" v-if="post.comments?.filter(r => r.parent_id === comment.id).length > 0">
              <div v-for="reply in post.comments?.filter(r => r.parent_id === comment.id)" :key="reply.id" class="border-b border-slate-100 dark:border-slate-800/40 last:border-none last:pb-0 pb-4">
                
                <div class="flex justify-between items-start mb-2">
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400 text-[10px]">
                      {{ reply.user?.name?.substring(0,1) || 'U' }}
                    </div>
                    <div>
                      <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ reply.user?.name }}</h5>
                      <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ new Date(reply.created_at).toLocaleDateString() }}</span>
                    </div>
                  </div>

                  <div v-if="$page.props.auth?.user?.id === reply.user_id && editingId !== reply.id" class="flex gap-2 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                    <button @click="editComment(reply)" class="hover:text-slate-600 dark:hover:text-slate-300 transition">수정</button>
                    <button @click="deleteComment(reply.id)" class="hover:text-red-500 transition">삭제</button>
                  </div>
                </div>

                <div v-if="editingId === reply.id">
                  <form @submit.prevent="updateComment(reply.id)">
                    <textarea v-model="editForm.content" class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    <div class="flex gap-2 mt-2 justify-end">
                      <button type="button" @click="editingId = null" class="px-2 py-1 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold rounded">취소</button>
                      <button type="submit" class="px-2 py-1 bg-slate-900 dark:bg-emerald-600 text-white text-[10px] font-bold rounded">저장</button>
                    </div>
                  </form>
                </div>
                <p v-else class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed pl-8">{{ reply.content }}</p>
              </div>
            </div>

          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ post: Object });

const toggleLike = () => {
  router.post(`/likes/post/${props.post.id}`, {}, {
    preserveScroll: true
  });
};

const deletePost = () => { 
  if (confirm('정말 삭제하시겠습니까?')) router.delete('/posts/' + props.post.id); 
};

const commentForm = useForm({ content: '', parent_id: null });
const submitComment = () => { 
  commentForm.post(`/posts/${props.post.id}/comments`, { 
    preserveScroll: true, 
    onSuccess: () => commentForm.reset() 
  }); 
};

const deleteComment = (id) => { 
  if (confirm('댓글을 삭제하시겠습니까?')) router.delete('/comments/' + id, { preserveScroll: true }); 
};

const editingId = ref(null);
const editForm = useForm({ content: '' });
const editComment = (comment) => { 
  editingId.value = comment.id; 
  editForm.content = comment.content; 
};

const updateComment = (id) => { 
  editForm.patch('/comments/' + id, { 
    preserveScroll: true, 
    onSuccess: () => (editingId.value = null) 
  }); 
};

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
    onSuccess: () => (replyingId.value = null) 
  }); 
};
</script>