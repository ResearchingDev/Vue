// resources/js/router/index.js

import { createRouter, createWebHistory } from "vue-router";
import Body from "../components/body.vue";

/* Auth */
import login from "../pages/auth/login.vue";

/* Client */
import clients from "../pages/clients/index.vue";
import userEdit from "../pages/clients/profile/userEdit.vue";

/* User */
import users from "../pages/users/index.vue";

/* Dashboard */
import apexChart from "../pages/advance/charts/ApexChart/apex_chart.vue";

/* Error Page */

import Error404 from "../pages/error/error404.vue";

import roles from "../pages/roles/index.vue";
const routes = [
    {
        path: "/",
        component: Body,
        children: [
            {
                path: "",
                name: "defaultRoot",
                component: apexChart,
                meta: {
                    title: "Subscription - ERP Software",
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
        path: "/profile",
        component: Body,
        children: [
            {
                path: "",
                name: "profile",
                component: userEdit,
                meta: {
                    title: "Users Edit | Subscription - ERP Software",
                },
            }
        ]
    },
    {
        path: "/admin",
        component: Body,
        children: [
            {
                path: "dashboard",
                name: "dashboard",
                component: apexChart,
                meta: {
                    title: " Dashboard | Subscription - ERP Software",
                }
            },
            {
                path: "client",
                name: "client",
                component: clients,
                meta: {
                    title: "Manage Clients | Subscription - ERP Software",
                },
            },
        ],
    },
    {
        path: "/client",
        component: Body,
        children: [
            {
                path: "dashboard",
                name: "Dashboard",
                component: apexChart,
                meta: {
                    title: " Dashboard | Subscription - ERP Software",
                },
            },
            {

                path: "users",
                name: "users",
                component: users,
                meta: {
                    title: "Manage Users | Subscription - ERP Software",
                },
            },
            {
                path: "roles",
                name: "Role",
                component: roles,
                meta: {
                    title: " Roles | Subscription - ERP Software",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*", // This will match any undefined path
        name: "NotFound",
        component: Error404,
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    if (to.meta.title) document.title = to.meta.title;
    const path = ["/login"];
    if (path.includes(to.path) || localStorage.getItem("token")) {
        return next();
    }
    next("/login");
});
export default router;
