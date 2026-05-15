<template>
  <Layout>
    <div class="max-w-2xl mx-auto">
      <div class="mb-10 text-center">
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter mb-3">새 글 쓰기</h1>
      </div>

      <form @submit.prevent="submit" class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
        <div class="mb-8">
          <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">제목</label>
          <input 
            v-model="form.title" 
            type="text" 
            placeholder="제목을 입력하세요"
            class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all placeholder:text-slate-300"
          >
        </div>

        <div class="mb-8">
          <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">대표 이미지</label>
          <div class="mt-2">
            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-100 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition-all group">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-10 h-10 mb-3 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <p class="mb-1 text-sm text-slate-500 font-semibold">클릭하여 사진 업로드</p>
                <p class="text-xs text-slate-300">PNG, JPG up to 8MB</p>
              </div>
              <input type="file" class="hidden" @input="form.image = $event.target.files[0]" accept="image/*">
            </label>
          </div>
          <p v-if="form.image" class="mt-3 text-xs text-emerald-600 font-bold ml-2">✓ {{ form.image.name }} 선택됨</p>
        </div>

        <div class="mb-10">
          <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">내용</label>
          <textarea 
            v-model="form.content" 
            placeholder="내용을 자유롭게 작성해 보세요"
            class="w-full p-5 bg-slate-50 border-none rounded-[2rem] h-60 focus:ring-2 focus:ring-emerald-500 outline-none transition-all resize-none placeholder:text-slate-300"
          ></textarea>
        </div>

        <div class="flex gap-4">
          <button 
            type="button" 
            @click="window.history.back()" 
            class="flex-1 bg-slate-100 text-slate-500 p-4 rounded-2xl font-bold hover:bg-slate-200 transition-all active:scale-95"
          >
            취소
          </button>
          <button 
            type="submit" 
            class="flex-[2] bg-slate-900 text-white p-4 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 active:scale-95 disabled:opacity-50"
            :disabled="form.processing"
          >
            작성 완료
          </button>
        </div>
      </form>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue'; // Layout 임포트 추가!
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  content: '',
  image: null,
});

const submit = () => {
  form.post('/posts');
};
</script>