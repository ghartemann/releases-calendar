import * as VueRouter from "vue-router";

import Home from "@pages/Home";
import NotFound from "@pages/Not-Found";
import Calendar from "@pages/Calendar";

const routes = [
    {
        path: "/",
        name: "homepage",
        component: Home
    },
    {
        path: "/calendar",
        name: "calendar",
        component: Calendar
    },
    // insert new routes here
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound
    },
];

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

export default router;
