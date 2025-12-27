import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import MyRentals from '../pages/MyRentals.vue';
import Admin from '../admin/pages/Admin.vue';
import AdminCars from '../admin/pages/AdminCars.vue';
import AdminUsers from '../admin/pages/AdminUsers.vue';


const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
 {path: "/rent/:carId",name: "rent",component: () => import("../pages/RentCar.vue"),},
{ path: '/my-rentals', name:'MyRentals', component: MyRentals },
{
  path: "/admin",
  component: Admin, 
  meta: { admin: true },
  children: [
    { path: "", component: AdminCars },   
    { path: "cars", component: AdminCars },
    { path: "users", component: AdminUsers },
    { path: "reviews", component: () => import("../admin/pages/AdminReviews.vue") },
    { path: "rental-conditions", component: () => import("../admin/pages/AdminRentalConditions.vue") },
    { path: "faqs", component: () => import("../admin/pages/AdminFaq.vue") },
  ],
},
{ path: '/admin/login', name: 'AdminLogin', component: () => import('../admin/pages/AdminLogin.vue') },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  if (to.meta.admin && !localStorage.getItem("admin_token")) {
    return "/admin/login";
  }
});



export default router;
