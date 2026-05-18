<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-900 font-sans">
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-slate-200/60">
      <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <Link href="/posts" class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent tracking-tighter">
          게시판
        </Link>
        
        <div class="flex items-center gap-8">
          <Link href="/posts" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">피드</Link>
          
          <template v-if="$page.props.auth?.user">
            <div class="flex items-center gap-6">
              <span class="text-sm font-bold text-slate-700">
                <span class="text-emerald-600">{{ $page.props.auth.user.name }}</span>님
              </span>
              <Link href="/posts/create" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 active:scale-95">
                글쓰기
              </Link>
              <button @click="logout" class="text-sm font-bold text-slate-400 hover:text-red-500 transition uppercase tracking-tighter">
                Logout
              </button>
            </div>
          </template>

          <template v-else>
            <div class="flex items-center gap-4">
              <Link href="/login" class="text-sm font-bold text-slate-600 hover:text-emerald-600 transition">로그인</Link>
              <Link href="/register" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95">
                회원가입
              </Link>
            </div>
          </template>
        </div>
      </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const logout = () => {
  if (confirm('로그아웃 하시겠습니까?')) {
    // 안전한 고정 주소로 로그아웃 요청 전송
    router.post('/logout');
  }
};
</script>