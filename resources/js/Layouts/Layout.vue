<template>
  <div class="w-full min-h-screen bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans transition-colors duration-300">
    
    <nav class="sticky top-0 z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-lg border-b border-slate-200/60 dark:border-slate-800/60 transition-colors duration-300">
      <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <Link href="/posts" class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent tracking-tighter">
          게시판
        </Link>
        
        <div class="flex items-center gap-8">
          <Link href="/posts" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">피드</Link>
          
          <template v-if="$page.props.auth?.user">
            <div class="flex items-center gap-6">
              <span class="text-sm font-bold text-slate-700 dark:text-slate-300">
                <span class="text-emerald-600 dark:text-emerald-400">{{ $page.props.auth.user.name }}</span>님
              </span>

              <Link 
                href="/profile" 
                class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition"
              >
                내 프로필
              </Link>

              <Link href="/posts/create" class="bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-100 dark:shadow-none active:scale-95">
                글쓰기
              </Link>
            </div>
          </template>

          <template v-else>
            <div class="flex items-center gap-4">
              <Link href="/login" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">로그인</Link>
              <Link href="/register" class="bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-lg active:scale-95">
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
import { Link } from '@inertiajs/vue3';
</script>