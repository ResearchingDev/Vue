// resources/js/router/index.js

import { createRouter, createWebHistory } from 'vue-router';
import Body from '../components/body.vue';
/* Auth */
import login from '../pages/auth/login.vue';

import Default from '../pages/dashboard/default.vue';
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
          title: ' Cuba - Premium Admin Template',
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
        title: ' login | Subscription - ERP',
      }
    }
    ]
  }
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
