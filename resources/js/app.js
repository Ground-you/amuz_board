import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '../css/app.css';

createInertiaApp({
  resolve: name => {
    // Vite의 glob import 방식
    const pages = import.meta.glob('./Pages/**/*.vue')
    
    // .default가 없는 경우를 대비해서 비동기적으로 처리하거나 
    // eager 모드에 맞춰서 정확한 파일을 반환
    const page = pages[`./Pages/${name}.vue`]
    
    if (!page) {
        console.error(`페이지를 찾을 수 없어: ./Pages/${name}.vue`)
    }

    // 함수라면 실행해서 결과를 가져오고 아니면 그대로 반환
    return typeof page === 'function' ? page() : page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})