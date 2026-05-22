<template>
    <Head title="내 프로필" />

    <Layout>
        <div class="space-y-10 transition-colors duration-300">
            
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 dark:bg-slate-900 dark:border-slate-800 transition-colors duration-300">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>내 프로필 정보</span>
                </h3>
                
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-slate-400 dark:text-slate-500">이름</label>
                        <p class="text-base text-slate-800 dark:text-slate-100 font-bold">{{ user.name }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-slate-400 dark:text-slate-500">이메일 계정</label>
                        <p class="text-base text-slate-800 dark:text-slate-100 font-bold font-mono">{{ user.email }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>연동 계정 관리</span>
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ user.name }}님의 계정을 소셜 서비스와 연동하세요.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 max-w-2xl">
                    <div v-for="provider in ['google', 'naver', 'github']" :key="provider" 
                         class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 dark:bg-slate-900 dark:border-slate-800 transition-all duration-300">
                        <div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300 capitalize">{{ provider }} 계정</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ isLinked(provider) ? '연동됨' : '미연동' }}</p>
                        </div>
                        <button @click="handleAction(provider)" 
                                :class="isLinked(provider) ? 'bg-gray-500 text-red-600 hover:bg-gray-700' : getProviderColor(provider)" 
                                class="px-5 py-2.5 text-xs font-bold rounded-xl active:scale-95 transition-all text-white">
                            {{ isLinked(provider) ? '연동 해제하기' : `${provider} 연동하기` }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200/60 dark:border-slate-800 space-y-6">
                <div class="flex items-center justify-between max-w-2xl p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 transition-all duration-300">
                    <div>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">화면 테마 설정</p>
                        <p class="text-xs text-slate-400 mt-0.5">다크 모드와 라이트 모드를 전환합니다.</p>
                    </div>
                    <button @click="toggleTheme" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                        테마 변경하기
                    </button>
                </div>

                <button @click="logout" class="w-full sm:w-auto px-6 py-3 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm font-bold rounded-xl hover:text-red-600 transition-all">
                    로그아웃 하기
                </button>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue'; 

const props = defineProps({
    user: Object,
    linkedAccounts: {
        type: Array,
        default: () => []
    }
});

const isLinked = (provider) => props.linkedAccounts.includes(provider);

const getProviderColor = (provider) => {
    const colors = { google: 'bg-red-500 hover:bg-red-700', naver: 'bg-emerald-500 hover:bg-emerald-700', github: 'bg-slate-500 hover:bg-slate-700' };
    return colors[provider] || 'bg-slate-500';
};

const toggleTheme = () => {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
};

const logout = () => {
    if (confirm('로그아웃 하시겠습니까?')) {
        router.post('/logout');
    }
};

const handleAction = (provider) => {
    if (isLinked(provider)) {
        if (confirm(`${provider} 연동을 정말 해제하시겠습니까?`)) {
            router.post(`/auth/disconnect/${provider}`);
        }
    } else {
        window.location.href = `/auth/${provider}`;
    }
};
</script>