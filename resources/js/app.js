require('./bootstrap');
import vue from "vue";

window.Vue = vue;

import App from './components/App.vue';

import VueAxios from "vue-axios";
import axios from "axios";

import VueRouter from "vue-router";
import { reactive } from "vue";
import { routes} from "./routes";

import Vue from "vue";
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

const router = new VueRouter({
    mode: 'history',
    routes: routes
});


axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  }, error => {
    return Promise.reject(error);
  });

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
}); 

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth) && !token) {
      next({ name: 'Login' });
    } else {
      next();
    }
  });

  export const eventBus = reactive({
    isAuthenticated: false,
    user: null
  }); 
  
  export default router;