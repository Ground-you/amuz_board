<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">새 글 쓰기</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">제목</label>
        <input v-model="form.title" type="text" class="w-full border p-2 rounded">
      </div>

      <div class="mb-4">
        <label class="block mb-1">내용</label>
        <textarea v-model="form.content" class="w-full border p-2 rounded h-40"></textarea>
      </div>
      <div class="mb-4">
        <br>
        <label class="block mb-1">이미지 업로드</label>
        <br>
        <input type="file" @input="form.image = $event.target.files[0]" class="w-full border p-2">
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        저장하기
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

// 나중에 이해하기
const form = useForm({
  title: '',
  content: '',
  image: null,
});

const submit = () => {
  form.post('/posts'); // Route::post('/posts', ...)로 데이터 보내기
};
</script>