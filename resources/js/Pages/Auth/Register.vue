<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 px-6 py-12">
    <div class="max-w-md w-full bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
      <h2 class="text-3xl font-black text-slate-900 mb-8 text-center tracking-tighter">Sign Up</h2>
      <form @submit.prevent="form.post('/register')">
        <div class="space-y-4">
          <input v-model="form.name" type="text" placeholder="이름" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
          <input v-model="form.email" type="email" placeholder="이메일" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
          <input v-model="form.password" type="password" placeholder="비밀번호" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
          <input v-model="form.password_confirmation" type="password" placeholder="비밀번호 확인" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
        </div>
        <button type="submit" class="w-full mt-8 bg-slate-900 text-white p-4 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95" :disabled="form.processing">가입하기</button>
        <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
          이미 계정이 있으신가요? 
          <a href="/login" class="font-bold text-blue-500 hover:text-blue-600 transition-colors ml-1">
              로그인하기
          </a>
      </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

// password_confirmation 기본값을 꼭 명시해 주어야 라라벨과 완벽히 호환됩니다!
const form = useForm({ 
  name: '', 
  email: '', 
  password: '', 
  password_confirmation: '' 
});
</script>