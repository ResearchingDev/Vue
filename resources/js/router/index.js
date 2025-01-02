// resources/js/router/index.js

import { createRouter, createWebHistory } from 'vue-router';
import Body from '../components/body.vue';
/* Auth */
import login from '../pages/auth/login.vue';

import Default from '../pages/dashboard/default.vue';

// user
import userProfile from '../pages/users/profile/userProfile.vue'
import userEdit from '../pages/users/profile/userEdit.vue'
const routes = [
  {
    path: '/',
    component: Body,

    children: [
      {
        path: '',
        name: 'defaultRoot',
        component: Default,
        meta: {
          title: 'Subscription - ERP Software',
        }
      },

    ]
  },
  {
    path: '/auth',
    children: [
    {
      path: 'login',
      name: 'Login 1',
      component: login,
      meta: {
        title: ' login | Subscription - ERP Software',
      }
    }
    ]
  },
  {
    path: '/users',
    component: Body,
    children: [
      {
        path: "profile",
        name: "profile",
        component: userProfile,
        meta: {
          title: "Users Profile | Subscription - ERP Software",
        },
      },
      {
        path: "edit",
        name: "edit",
        component: userEdit,
        meta: {
          title: "Users Edit | Subscription - ERP Software",
        },
      },
    ]
  },
];
// console.log(routes);
const router = createRouter({
  history: createWebHistory(),
  routes,
});
router.beforeEach((to, from, next) => {
  if(to.meta.title)
    document.title = to.meta.title;
  const  path = ['/auth/login','/auth/register'];
   if(path.includes(to.path) || localStorage.getItem('User')){
    return next();
   }
   next('/auth/login');
});
export default router;
