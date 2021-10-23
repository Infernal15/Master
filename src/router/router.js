import {createRouter, createWebHistory} from "vue-router";
import About from "@/pages/About";
import Catalog from "@/pages/Catalog";
import FAQ from "@/pages/FAQ";
import GoodPage from "@/pages/GoodPage";
import Main from "@/pages/Main";
import Payment from "@/pages/Payment";
import Rules from "@/pages/Rules";
import UserProfile from "@/pages/UserProfile";
const routes = [
    {
        path: '/about',
        component: About
    },
    {
        path: '/catalog',
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
    }
]

const router = createRouter({
    routes,
    history: createWebHistory(process.env.BASE_URL)
})
export default router;