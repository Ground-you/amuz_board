<template>
  <Layout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10 pb-6 border-b border-slate-100 dark:border-slate-800/60 px-4 md:px-0">
      <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
          게시판 목록
        </h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">이용자들과 실시간으로 소통하고 자유롭게 의견을 나누는 공간입니다.</p>
      </div>
      
      <div class="flex items-center">
        <Link 
          href="/chat" 
          class="inline-flex items-center gap-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-emerald-500/20 dark:shadow-none transform hover:-translate-y-0.5 transition-all duration-300 group"
        >
          <span>실시간 대화방 입장</span>
          
          <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-200 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-300"></span>
          </span>
        </Link>
      </div>
    </div>

    <div class="grid gap-8">
      <div v-if="posts.data.length === 0" class="py-20 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-800">
        <p class="text-slate-400 dark:text-slate-500">아직 작성된 글이 없습니다. 첫 번째 주인공이 되어보세요!</p>
      </div>

      <div v-for="post in posts.data" :key="post.id" class="group bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800/60 p-2 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all duration-300">
        <Link :href="'/posts/' + post.id" class="flex flex-col md:flex-row gap-6">
          <div v-if="post.image_path" class="md:w-64 h-48 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-100 dark:bg-slate-950">
            <img :src="'/storage/' + post.image_path" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
          </div>
          <div v-else class="md:w-64 h-48 rounded-2xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center flex-shrink-0 text-slate-300 dark:text-slate-700 italic text-sm">
            No Image
          </div>

          <div class="flex-1 py-4 pr-6 flex flex-col justify-between px-4 md:px-0">
            <div>
              <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition leading-tight">
                {{ post.title }}
              </h2>
              <p class="text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed mb-4 text-sm">{{ post.content }}</p>
            </div>
            <div class="flex items-center justify-between text-xs">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/60 flex items-center justify-center font-bold text-emerald-700 dark:text-emerald-400">H</div>
                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ post.user?.name || 'Hwang' }}</span>
              </div>
              <span class="text-slate-400 dark:text-slate-500 font-medium">{{ new Date(post.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </Link>
      </div>
    </div>

    <div class="mt-16 flex justify-center gap-2">
      <template v-for="(link, index) in posts.links" :key="index">
        <Link 
          v-if="link.url"
          :href="link.url"
          v-html="link.label"
          class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all border"
          :class="link.active 
            ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-200/50 dark:shadow-none' 
            : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300'"
        />
        <span 
          v-else 
          v-html="link.label" 
          class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-200 dark:text-slate-700 bg-gray-50 dark:bg-slate-900/40 border border-slate-50 dark:border-slate-800/40 cursor-not-allowed"
        ></span>
      </template>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ posts: Object });
</script>