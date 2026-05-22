<script setup>
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue'; 

defineProps({
    user: Object
});

const isDark = ref(false);

onMounted(() => {
    if (
        localStorage.getItem('theme') === 'dark' || 
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});

const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        localStorage.setItem('theme', 'light');
    }
};

const logout = () => {
    if (confirm('로그아웃 하시겠습니까?')) {
        router.post('/logout');
    }
};
</script>

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
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ user.name }}님의 계정을 연동해서 소셜 로그인을 해보시는건 어떤가요?</p>
                </div>

                <div class="grid grid-cols-1 gap-4 max-w-2xl">
                    
                    <div class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-red-200 hover:shadow-lg hover:shadow-red-50/30 dark:bg-slate-900 dark:border-slate-800 dark:hover:border-red-900/50 dark:hover:shadow-red-950/20 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Google 계정</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">현재 처리되지 않는 경우</p>
                            </div>
                        </div>
                        <a href="#" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-500 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md shadow-red-100 dark:shadow-none active:scale-95 transition-all duration-300">
                            구글 연동하기
                        </a>
                    </div>

                    <div class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-emerald-400 hover:shadow-lg hover:shadow-emerald-50/50 dark:bg-slate-900 dark:border-slate-800 dark:hover:border-emerald-900/50 dark:hover:shadow-emerald-950/20 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">네이버 계정</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">현재 처리되지 않는 경우</p>
                            </div>
                        </div>
                        <a href="#" class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-100 dark:shadow-none active:scale-95 transition-all duration-300">
                            네이버 연동하기
                        </a>
                    </div>

                    <div class="flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-slate-400 hover:shadow-lg hover:shadow-slate-100 dark:bg-slate-900 dark:border-slate-800 dark:hover:border-slate-700 dark:hover:shadow-slate-950/40 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">GitHub 계정</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">현재 처리되지 않는 경우</p>
                            </div>
                        </div>
                        <a href="#" class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-400 hover:bg-slate-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl shadow-md shadow-slate-100 dark:shadow-none active:scale-95 transition-all duration-300">
                            깃허브 연동하기
                        </a>
                    </div>

                </div>
            </div>

            <div class="pt-6 border-t border-slate-200/60 dark:border-slate-800 transition-colors duration-300 space-y-6">
                <div class="flex items-center justify-between max-w-2xl p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 transition-all duration-300">
                    <div>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">화면 테마 설정</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">눈이 편안한 어두운 테마를 제공합니다.</p>
                    </div>
                    <button 
                        @click="toggleDarkMode" 
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl shadow-sm active:scale-95 transition-all duration-300"
                    >
                        {{ isDark ? '라이트 모드로 변경' : '다크 모드로 변경' }}
                    </button>
                </div>

                <div>
                    <button 
                        @click="logout" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-900 dark:hover:bg-red-950/30 dark:text-slate-400 dark:hover:text-red-400 text-slate-600 text-sm font-bold rounded-xl transition-all duration-200 active:scale-95"
                    >
                        로그아웃 하기
                    </button>
                </div>
            </div>

        </div>
    </Layout>
</template>