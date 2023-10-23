import { createRouter, createWebHistory } from 'vue-router'
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("@/views/Home.vue"),
            children: [
                {
                    path: "",
                    name: "currency-list",
                    component: () => import("@/views/sections/CurrencyList.vue")
                }
            ]
        },
        {
            path: "/convert",
            name: "convert",
            component: () => import("@/views/Convert.vue")
        }
    ]
})

export default router
