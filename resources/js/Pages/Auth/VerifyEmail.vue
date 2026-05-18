<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 px-6">
    <div class="max-w-md w-full bg-white p-10 rounded-[2.5rem] shadow-xl border border-white text-center">
      
      <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-sm">
        ✉️
      </div>
      
      <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter">이메일 인증 필요</h2>
      
      <div 
        v-if="$page.props.flash?.message" 
        class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl text-left leading-relaxed shadow-sm flex items-start gap-2"
      >
        <span class="text-sm">✨</span>
        <span>{{ $page.props.flash.message }}</span>
      </div>

      <p class="text-sm text-slate-500 mb-8 leading-relaxed font-medium text-left bg-slate-50 p-4 rounded-2xl border border-slate-100">
        만약 메일을 받지 못하셨다면 아래 버튼을 눌러 인증 이메일을 다시 요청할 수 있습니다. (스팸 메일함도 꼭 확인해 주세요!)
      </p>
      
      <form @submit.prevent="submit">
        <button 
          type="submit" 
          class="w-full bg-slate-900 text-white p-4 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95 disabled:opacity-50" 
          :disabled="form.processing"
        >
          {{ form.processing ? '메일 발송 중...' : '인증 이메일 재전송하기' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const submit = () => {
  form.post('/email/verification-notification', {
    preserveScroll: true
  });
};
</script>