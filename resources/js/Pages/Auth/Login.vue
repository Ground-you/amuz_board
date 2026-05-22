<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 px-6">
    <div class="max-w-md w-full bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
      <h2 class="text-3xl font-black text-slate-900 mb-8 text-center tracking-tighter">Login</h2>
      
      <form @submit.prevent="form.post('/login')">
        <div class="space-y-4">
          <input v-model="form.email" type="email" placeholder="이메일" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
          <div v-if="form.errors.email" class="text-red-500 text-xs pl-2">{{ form.errors.email }}</div>
          <input v-model="form.password" type="password" placeholder="비밀번호" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none">
        </div>
        <button type="submit" class="w-full mt-8 bg-slate-900 text-white p-4 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95" :disabled="form.processing">로그인</button>
      </form>

      <div class="mt-8 space-y-4">
        <div class="relative flex items-center justify-center">
          <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
          <span class="relative bg-white px-4 text-xs text-slate-400 font-bold">간편 로그인</span>
        </div>

        <div class="grid grid-cols-1 gap-3">
          <a href="/auth/google" class="flex items-center justify-center w-full p-4 bg-white border border-slate-200 rounded-2xl hover:border-red-500 transition-all font-bold text-sm text-slate-600 active:scale-95">
            구글로 계속하기
          </a>
          <a href="/auth/naver" class="flex items-center justify-center w-full p-4 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 transition-all font-bold text-sm text-slate-600 active:scale-95">
            네이버로 계속하기
          </a>
          <a href="/auth/github" class="flex items-center justify-center w-full p-4 bg-white border border-slate-200 rounded-2xl hover:border-slate-800 transition-all font-bold text-sm text-slate-600 active:scale-95">
            깃허브로 계속하기
          </a>
        </div>
      </div>

      <p class="mt-8 text-center text-sm text-slate-400 font-medium">
        계정이 없으신가요? 
        <Link href="/register" class="text-emerald-600 font-bold underline underline-offset-4">회원가입</Link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
const form = useForm({ email: '', password: '', remember: false });
</script>