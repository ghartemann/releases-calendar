import * as VueRouter from "vue-router";

import Home from "@pages/Home";
import Slug from "@pages/home/components/slug";
import NotFound from "@pages/Not-Found";

const routes = [
    {
        path: "/",
        name: "homepage",
        component: Home,
        children: {
            path: ':slug',
            component: Slug,
        }
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
