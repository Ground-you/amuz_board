<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">글 수정하기</h1>

    <form @submit.prevent="submit" class="bg-white p-8 shadow-lg rounded-2xl">
      <div class="mb-6">
        <label class="block font-bold mb-2 text-gray-700">제목</label>
        <input v-model="form.title" type="text" class="w-full border-gray-300 p-3 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-all">
      </div>

      <div class="mb-6">
        <label class="block font-bold mb-2 text-gray-700">사진 변경</label>
        
        <div v-if="post.image_path" class="mb-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
          <p class="text-sm text-gray-500 mb-2 font-medium">현재 등록된 사진:</p>
          <img :src="'/storage/' + post.image_path" class="w-32 h-auto rounded-lg border shadow-sm">
        </div>

        <div class="mt-2">
          <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-emerald-50 hover:border-emerald-400 transition-all group">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <p class="mb-1 text-sm text-gray-600 font-semibold">새 파일을 선택하여 교체</p>
              <p class="text-xs text-gray-400">PNG, JPG up to 8MB</p>
            </div>
            <input 
              type="file" 
              class="hidden" 
              @input="form.image = $event.target.files[0]" 
              accept="image/*"
            >
          </label>
        </div>
      </div>

      <div class="mb-8">
        <label class="block font-bold mb-2 text-gray-700">내용</label>
        <textarea v-model="form.content" class="w-full border-gray-300 p-3 rounded-xl h-48 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-all"></textarea>
      </div>

      <div class="flex justify-end gap-3">
        <button 
          type="button" 
          @click="window.history.back()" 
          class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-full font-medium hover:bg-gray-200 transition-all"
        >
          취소
        </button>
        <button 
          type="submit" 
          class="bg-blue-600 text-white px-8 py-2.5 rounded-full font-bold shadow-md hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 transition-all disabled:opacity-50"
          :disabled="form.processing"
        >
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
  image: null,
  _method: 'PATCH',
});

const submit = () => {
  form.post(`/posts/${props.post.id}`, {
    forceFormData: true,
  });
};
</script>