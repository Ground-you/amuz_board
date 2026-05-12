<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-900 font-sans">
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-slate-200/60">
      <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <Link href="/posts" class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent tracking-tighter">
          DEV_LOG
        </Link>
        <div class="flex items-center gap-8">
          <Link href="/posts" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">피드</Link>
          <Link href="/posts/create" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 hover:shadow-emerald-200 active:scale-95">
            글쓰기
          </Link>
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