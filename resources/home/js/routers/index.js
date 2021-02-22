import Vue from 'vue';
import VueRouter from 'vue-router';
import Routers from './router';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: Routers
});

export default router;
