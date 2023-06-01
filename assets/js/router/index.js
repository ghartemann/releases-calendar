import * as VueRouter from "vue-router";

import Home from "@pages/Home";
import Type from "@pages/Type";
import Calendar from "@pages/Calendar";
import NotFound from "@pages/Not-Found";

const routes = [
    {
        path: "/",
        name: "homepage",
        component: Home
    },
    {
        path: "/t/:type",
        name: "type",
        component: Type
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
