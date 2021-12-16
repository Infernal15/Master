import {createRouter, createWebHistory} from "vue-router";
import About from "@/pages/About";
import Catalog from "@/pages/Catalog";
import FAQ from "@/pages/FAQ";
import GoodPage from "@/pages/GoodPage";
import Main from "@/pages/Main";
import Payment from "@/pages/Payment";
import Rules from "@/pages/Rules";
import UserProfile from "@/pages/UserProfile";
import Categories from "../pages/Categories";
import AuthForm from "../pages/AuthForm";
import Checkout from "../pages/Checkout";
const routes = [
    {
        path: '/about',
        component: About
    },
    {
        path: '/categories',
        component: Categories
    },
    {
        path: '/catalog/:type',
        component: Catalog
    },
    {
        path: '/faq',
        component: FAQ
    },
    {
        path: '/good/:id/:section',
        component: GoodPage
    },
    {
        path: '/',
        component: Main
    },
    {
        path: '/cart',
        component: Payment
    },
    {
        path: '/rules',
        component: Rules
    },
    {
        path: '/profile',
        component: UserProfile
    },
    {
        path: '/authentication',
        component: AuthForm
    },
    {
        path: '/checkout',
        component: Checkout
    }
]

const router = createRouter({
    mode: 'history',
    routes,
    history: createWebHistory(process.env.BASE_URL)
})
export default router;