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
        <span>✨</span>
        <span>{{ $page.props.flash.message }}</span>
      </div>

      <p class="text-sm text-slate-500 mb-8 leading-relaxed font-medium text-left bg-slate-50 p-4 rounded-2xl border border-slate-100">
        인증 메일이 발송되었습니다. 메일을 받지 못하셨다면 재전송을 요청해주세요. (스팸 메일함도 확인해 주세요!)
      </p>
      
      <form @submit.prevent="submit" class="mb-6">
        <button 
          type="submit" 
          class="w-full bg-slate-900 text-white p-4 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95 disabled:opacity-50" 
          :disabled="form.processing"
        >
          {{ form.processing ? '메일 발송 중...' : '인증 이메일 재전송하기' }}
        </button>
      </form>

      <div class="pt-6 border-t border-slate-100 text-left">
        <p class="text-xs font-bold text-slate-400 mb-3">이메일 주소를 잘못 입력하셨나요?</p>
        <form @submit.prevent="updateEmail" class="flex gap-2">
          <input 
            v-model="emailForm.email" 
            type="email" 
            placeholder="새 이메일 주소" 
            class="w-full text-sm border-slate-200 rounded-xl focus:ring-emerald-500"
            required
          />
          <button 
            type="submit" 
            class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-200 disabled:opacity-50"
            :disabled="emailForm.processing"
          >
            변경
          </button>
        </form>
      </div>

      <button 
        @click="logout" 
        class="mt-8 w-full text-slate-400 hover:text-red-500 text-xs font-bold transition-all underline decoration-slate-200 hover:decoration-red-400"
      >
        다른 계정으로 가입하기 (로그아웃)
      </button>

    </div>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';

// 폼 관리
const form = useForm({});
const emailForm = useForm({ email: '' });

// 인증 재전송
const submit = () => {
  form.post('/email/verification-notification', { preserveScroll: true });
};

// 이메일 수정
const updateEmail = () => {
  emailForm.patch('/profile/email', {
    preserveScroll: true,
    onSuccess: () => emailForm.reset(),
  });
};

// 로그아웃
const logout = () => {
  if (confirm('현재 계정에서 로그아웃하시겠습니까?')) {
    router.post('/logout');
  }
};
</script>