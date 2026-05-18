<template>
  <Layout>
    <div class="max-w-3xl mx-auto">
      <header class="mb-12 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 mb-4 tracking-tighter">
          게시글 수정
        </h1>
        <p class="text-sm text-slate-500 font-medium">기존의 글 내용을 자유롭게 변경해 보세요.</p>
      </header>

      <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 p-8 md:p-12 border border-white">
        <form @submit.prevent="submit" class="space-y-6">
          
          <div>
            <label for="title" class="block text-sm font-bold text-slate-700 mb-2 pl-1">제목</label>
            <input 
              type="text" 
              id="title" 
              v-model="form.title" 
              class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all placeholder-slate-400 text-slate-800 font-medium"
              placeholder="제목을 입력해 주세요."
            >
            <div v-if="form.errors.title" class="text-xs text-red-500 font-bold mt-1 pl-1">
              {{ form.errors.title }}
            </div>
          </div>

          <div>
            <label for="content" class="block text-sm font-bold text-slate-700 mb-2 pl-1">내용</label>
            <textarea 
              id="content" 
              v-model="form.content" 
              rows="8" 
              class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all placeholder-slate-400 text-slate-800 leading-relaxed resize-none"
              placeholder="내용을 입력해 주세요."
            ></textarea>
            <div v-if="form.errors.content" class="text-xs text-red-500 font-bold mt-1 pl-1">
              {{ form.errors.content }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 pl-1">이미지 수정 (선택)</label>
            <div class="flex items-center justify-center w-full">
              <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100/50 transition-all">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg class="w-8 h-8 mb-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h21M12 9l-3-3m0 0L6 9m3-3v12"/>
                  </svg>
                  <p class="mb-1 text-xs text-slate-500 font-bold"><span class="text-emerald-600">클릭하여 이미지 업로드</span> 또는 드래그 앤 드롭</p>
                  <p class="text-[10px] text-slate-400 font-medium">PNG, JPG, JPEG (최대 8MB)</p>
                </div>
                <input type="file" class="hidden" @change="handleFileChange" accept="image/*" />
              </label>
            </div>
            <div v-if="form.image" class="mt-2 text-xs text-emerald-600 font-bold pl-1 flex items-center gap-1.5">
              <span>✨ 선택된 파일:</span>
              <span class="underline">{{ form.image.name }}</span>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <Link 
              :href="'/posts/' + post.id" 
              class="px-6 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-200 transition active:scale-95 shadow-sm"
            >
              취소
            </Link>
            
            <button 
              type="submit" 
              class="px-6 py-3 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-emerald-600 transition active:scale-95 shadow-lg shadow-slate-900/10"
              :disabled="form.processing"
            >
              저장하기
            </button>
          </div>

        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { useForm, Link } from '@inertiajs/vue3';

// 💡 백엔드 라라벨에서 넘어오는 데이터를 명확하게 선언하여 'undefined' 에러를 전면 차단합니다!
const props = defineProps({
  post: Object
});

// 기존의 포스트 내용으로 폼 초기데이터 설정
const form = useForm({
  title: props.post.title,
  content: props.post.content,
  image: null,
  _method: 'PATCH' // 라라벨 관습법상 파일 업로드 처리가 있는 PATCH/PUT 요청은 POST에 _method를 얹어 보냅니다.
});

// 파일 선택 핸들러
const handleFileChange = (e) => {
  if (e.target.files.length > 0) {
    form.image = e.target.files[0];
  }
};

// 수정 완료 전송
const submit = () => {
  // 실제 백엔드로 수정한 폼 데이터를 보냅니다.
  form.post('/posts/' + props.post.id, {
    preserveScroll: true,
  });
};
</script>