// resources/js/router/index.js

import { createRouter, createWebHistory } from "vue-router";
import Body from "../components/body.vue";
/* Auth */
import login from "../pages/auth/login.vue";

//User
import user from "../pages/users/index.vue";
import userEdit from "../pages/users/profile/userEdit.vue";

//Dashboard
import apex_chart from "../pages/advance/charts/ApexChart/apex_chart.vue";
const routes = [
    {
        path: "/",
        component: Body,
        children: [
            {
                path: "",
                name: "defaultRoot",
                component: apex_chart,
                meta: {
                    title: "Subscription - ERP Software",
                },
            },
        ],
    },
    {
        path: "/admin",
        component: Body,
        children: [
            {
                path: "dashboard",
                name: "Dashboard",
                component: apex_chart,
                meta: {
                    title: " Dashboard | Subscription - ERP Software",
                },
            },
        ],
    },
    {
        path: "/login",
        name: "Login 1",
        component: login,
        meta: {
            title: " login | Subscription - ERP Software",
        },
    },
    {
        path: "/users",
        component: Body,
        children: [
            {
                path: "",
                name: "users",
                component: user,
                meta: {
                    title: "Manage Users | Subscription - ERP Software",
                },
            },
            {
                path: "profile",
                name: "profile",
                component: userEdit,
                meta: {
                    title: "Users Edit | Subscription - ERP Software",
                },
            },
        ],
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    if (to.meta.title) document.title = to.meta.title;
    const path = ["/login", "/auth/register"];
    if (path.includes(to.path) || localStorage.getItem("User")) {
        return next();
    }
    next("/login");
});
export default router;
