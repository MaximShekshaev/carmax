import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import MyRentals from '../pages/MyRentals.vue';


const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
 {
  path: "/rent/:carId",
  name: "rent",
  component: () => import("../pages/RentCar.vue"),
},
{ path: '/my-rentals', name:'MyRentals', component: MyRentals },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});




export default router;
