import { createRouter, createWebHistory } from "vue-router"
import Signin from "@/auth/Signin.vue"
import Dashboard from "@/pages/Dashboard.vue"
import Signup from "@/auth/Signup.vue"
import Signout from "@/auth/Signout.vue"
import VerifyEmail from "@/auth/VerifyEmail.vue"

const routes = [
    {
        path: "/",
        name: "auth.signin",
        component: Signin,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/signup",
        name: "auth.signup",
        component: Signup,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: "/signout",
        name: "auth.signout",
        component: Signout,
    },
    {
        path: "/verify/email",
        name: "auth.verify-email",
        component: VerifyEmail,
        meta: {
            requiresAuth: false
        }
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