require('./bootstrap');

import Vue from 'vue';
import App from './App.vue'
import router from './routers';
import ViewUi from 'view-design';
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUi);

new Vue({
    router,
    render: (h) => h(App),
}).$mount('#app');
