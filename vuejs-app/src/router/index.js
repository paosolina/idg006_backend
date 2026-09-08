import { createRouter, createWebHistory } from "vue-router"
import Signin from "@/auth/Signin.vue"
import Dashboard from "@/pages/Dashboard.vue"
import Signup from "@/auth/Signup.vue"
import Signout from "@/auth/Signout.vue"

const routes = [
    {
        path: "/",
        name: "auth.signin",
        component: Signin,
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
    },
    {
        path: "/signup",
        name: "auth.signup",
        component: Signup,
    },
    {
        path: "/signout",
        name: "auth.signout",
        component: Signout,
    },
    {
        path: "/:pathMatch(.*)",
        redirect: "/notfound",
    },
]


const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router