<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">게시글 목록</h1>
      <Link href="/posts/create" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        새 글 쓰기
      </Link>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-4 border-b">제목</th>
            <th class="p-4 border-b">작성자</th>
            <th class="p-4 border-b">작성일</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50 transition">
            <td class="p-4 border-b">
              <Link :href="'/posts/' + post.id" class="text-blue-600 font-semibold hover:underline">
                {{ post.title }}
              </Link>
            </td>
            <td class="p-4 border-b text-gray-600">{{ post.user?.name || '익명' }}</td>
            <td class="p-4 border-b text-sm text-gray-500">
              {{ new Date(post.created_at).toLocaleDateString() }}
            </td>
          </tr>
          <tr v-if="posts.data.length === 0">
            <td colspan="3" class="p-8 text-center text-gray-500">
              게시글이 없습니다. 첫 글을 남겨보세요!
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-8 flex justify-center items-center">
      <nav class="inline-flex flex-wrap shadow-sm rounded-md -space-x-px" aria-label="Pagination">
        <template v-for="(link, index) in posts.links" :key="index">
          
          <Link 
            v-if="link.url"
            :href="link.url"
            v-html="link.label"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-all duration-200"
            :class="{
              'z-10 bg-green-500 border-green-500 text-white': link.active,
              'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
              'rounded-l-md': index === 0,
              'rounded-r-md': index === posts.links.length - 1
            }"
          />

          <span 
            v-else 
            v-html="link.label" 
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed"
            :class="{
              'rounded-l-md': index === 0,
              'rounded-r-md': index === posts.links.length - 1
            }"
          ></span>

        </template>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

// 중요: 페이지네이션 적용 시 posts는 Array가 아니라 Object임
defineProps({
  posts: Object
});
</script>