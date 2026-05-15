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
      <p class="mt-6 text-center text-sm text-slate-400 font-medium">계정이 없으신가요? <Link href="/register" class="text-emerald-600 font-bold underline underline-offset-4">회원가입</Link></p>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
const form = useForm({ email: '', password: '', remember: false });
</script>