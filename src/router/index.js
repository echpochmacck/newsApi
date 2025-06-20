import { createRouter, createWebHistory } from "vue-router";
import Login from "@/views/Login.vue";
import Register from "@/views/Register.vue";
import News from "@/views/News.vue";
import Profile from "@/views/Profile.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Login,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("token")) {
          next("news");
        } else {
          next();
        }
      },
    },
    {
      path: "/register",
      name: "register",
      component: Register,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("token")) {
          next("news");
        } else {
          next();
        }
      },
    },
    {
      path: "/profile",
      name: "profile",
      component: Profile,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("token")) {
          next();
        } else {
          next("home");
        }
      },
    },
    {
      path: "/news",
      name: "news",
      component: News,
    },
  ],
});

export default router;
