<template>
  <Layout>
    <div class="grid gap-8">
      <div v-if="posts.data.length === 0" class="py-20 text-center bg-white rounded-3xl border border-dashed border-slate-300">
        <p class="text-slate-400">아직 작성된 글이 없습니다. 첫 번째 주인공이 되어보세요!</p>
      </div>

      <div v-for="post in posts.data" :key="post.id" class="group bg-white rounded-3xl border border-slate-100 p-2 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        <Link :href="'/posts/' + post.id" class="flex flex-col md:flex-row gap-6">
          <div v-if="post.image_path" class="md:w-64 h-48 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-100">
            <img :src="'/storage/' + post.image_path" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
          </div>
          <div v-else class="md:w-64 h-48 rounded-2xl bg-slate-50 flex items-center justify-center flex-shrink-0 text-slate-300 italic text-sm">
            No Image
          </div>

          <div class="flex-1 py-4 pr-6 flex flex-col justify-between px-4 md:px-0">
            <div>
              <h2 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition leading-tight">
                {{ post.title }}
              </h2>
              <p class="text-slate-500 line-clamp-2 leading-relaxed mb-4 text-sm">{{ post.content }}</p>
            </div>
            <div class="flex items-center justify-between text-xs">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-700">H</div>
                <span class="font-semibold text-slate-700">{{ post.user?.name || 'Hwang' }}</span>
              </div>
              <span class="text-slate-400 font-medium">{{ new Date(post.created_at).toLocaleDateString() }}</span>
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
            ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-200' 
            : 'bg-white border-slate-100 text-slate-400 hover:bg-slate-50 hover:text-slate-600'"
        />
        <span 
          v-else 
          v-html="link.label" 
          class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-200 bg-gray-50 border border-slate-50 cursor-not-allowed"
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