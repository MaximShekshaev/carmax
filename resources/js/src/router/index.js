import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import MyRent from '../pages/MyRent.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/myrent', name: 'MyRent', component: MyRent },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
