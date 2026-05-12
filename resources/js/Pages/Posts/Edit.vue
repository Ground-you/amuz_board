<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">글 수정하기</h1>

    <form @submit.prevent="submit" class="bg-white p-6 shadow rounded-lg">
      <div class="mb-4">
        <label class="block font-bold mb-2">제목</label>
        <input v-model="form.title" type="text" class="w-full border p-2 rounded">
      </div>

      <div class="mb-4">
        <label class="block font-bold mb-2">사진 변경</label>
        
        <div v-if="post.image_path" class="mb-2">
          <p class="text-sm text-gray-500 mb-1">현재 등록된 사진:</p>
          <img :src="'/storage/' + post.image_path" class="w-32 h-auto rounded border">
        </div>

        <input 
          type="file" 
          @input="form.image = $event.target.files[0]" 
          class="w-full border p-2 rounded"
          accept="image/*"
        >
        <p class="text-xs text-gray-400 mt-1">새 파일을 선택하면 기존 사진이 교체됩니다.</p>
      </div>

      <div class="mb-6">
        <label class="block font-bold mb-2">내용</label>
        <textarea v-model="form.content" class="w-full border p-2 rounded h-48"></textarea>
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" @click="window.history.back()" class="bg-gray-500 text-white px-4 py-2 rounded">취소</button>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded" :disabled="form.processing">
          수정 완료
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ post: Object });

const form = useForm({
  title: props.post.title,
  content: props.post.content,
  image: null, // 새 이미지를 담을 변수
  _method: 'PATCH', // 중요: 라라벨에서 파일과 함께 PATCH를 보낼 때 필요한 트릭
});

const submit = () => {
  // 파일을 포함한 수정(PATCH)은 가끔 브라우저 특성상 POST로 보내면서 _method를 섞어줘야 안전
  form.post(`/posts/${props.post.id}`, {
    forceFormData: true,
  });
};
</script>