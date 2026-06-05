<template>
  <Layout>
    <div class="max-w-4xl mx-auto px-4 md:px-0">
      
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800/60">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
            실시간 대화방 <span class="text-2xl">💬</span>
          </h1>
          <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">접속 중인 이용자들과 실시간으로 자유롭게 대화하는 공간입니다.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 px-4 py-2 rounded-full text-xs font-bold text-emerald-600 dark:text-emerald-400 self-start sm:self-center shadow-sm">
          <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          실시간 채널 연결됨
        </div>
      </div>

      <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-xl overflow-hidden flex flex-col h-[600px]">
        
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/40 dark:bg-slate-950/20">
          
          <div v-if="messages.length === 0" class="h-full flex flex-col items-center justify-center text-center py-20">
            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-full flex items-center justify-center text-xl mb-3 animate-bounce">
              💬
            </div>
            <p class="text-slate-400 dark:text-slate-500 font-medium text-sm">아직 나눈 대화가 없습니다.</p>
            <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">첫 번째 메시지를 보내 대화를 시작해보세요!</p>
          </div>

          <div 
            v-else 
            v-for="(msg, index) in messages" 
            :key="index" 
            class="flex flex-col"
            :class="msg.user_id === $page.props.auth.user?.id ? 'items-end' : 'items-start'"
          >
            <span v-if="msg.user_id !== $page.props.auth.user?.id" class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 ml-2">
              {{ msg.user?.name || '상대방' }}
            </span>
            
            <div class="flex items-end gap-2 max-w-[75%]" :class="msg.user_id === $page.props.auth.user?.id ? 'flex-row-reverse' : 'flex-row'">
              <div 
                class="px-5 py-3 text-sm shadow-sm transition-all duration-200 break-all"
                :class="msg.user_id === $page.props.auth.user?.id 
                  ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-3xl rounded-tr-none font-medium' 
                  : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-100 dark:border-slate-700/50 rounded-3xl rounded-tl-none'"
              >
                {{ msg.message }}
              </div>
              
              <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium whitespace-nowrap mb-1">
                {{ msg.time || '방금 전' }}
              </span>
            </div>
          </div>

        </div>

        <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800/60">
          <form @submit.prevent="sendMessage" class="relative flex items-center">
            <input 
              v-model="newMessage"
              type="text" 
              placeholder="따뜻한 한 마디를 입력해보세요..." 
              class="w-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 pl-6 pr-20 py-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-sm transition-all duration-300"
            />
            
            <button 
              type="submit"
              :disabled="!newMessage.trim()"
              class="absolute right-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 disabled:opacity-40 disabled:pointer-events-none text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-emerald-500/10 transition-all duration-300 flex items-center gap-1"
            >
              <span>전송</span>
              <span class="text-xs">🚀</span>
            </button>
          </form>
        </div>

      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({ 
  messages: Array 
});

const newMessage = ref('');

const sendMessage = () => {
  if (!newMessage.value.trim()) return;
  
  router.post('/chat', {
    message: newMessage.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      newMessage.value = '';
    }
  });
};

onMounted(() => {
  try {
    if (window.Echo) {
      window.Echo.channel('chat')
        .listen('.MessageSent', (e) => { // 💡 이벤트 명 앞에 온점(.)을 붙여 네임스페이스 문제를 방지합니다.
          router.reload({ only: ['messages'] });
        });
    }
  } catch (error) {
    console.error('Laravel Echo 연결 에러:', error);
  }
});
</script>