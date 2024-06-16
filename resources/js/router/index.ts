import { createRouter, createWebHashHistory } from 'vue-router';
import MainRoutes from './MainRoutes';
import AuthRoutes from './AuthRoutes';
import { useStore } from 'vuex'
//import { useAuthStore } from '@/stores/auth';
//import { useUIStore } from '@/stores/ui';

export const router = createRouter({
  history: createWebHashHistory(),
  
  routes: [
    {
      path: '/:pathMatch(.*)*',
      component: () => import('@/views/pages/maintenance/error/Error404Page.vue')
    },
    MainRoutes,
    AuthRoutes
  ]
});

interface User {
  // Define the properties and their types for the user data here
  // For example:
  id: number;
  name: string;
}

// Assuming you have a type/interface for your authentication store
interface AuthStore {
  user: User | null;
  returnUrl: string | null;
  login(username: string, password: string): Promise<void>;
  logout(): void;
}

router.beforeEach(async (to, from, next) => {
  // redirect to login page if not logged in and trying to access a restricted page
  console.log('to.path', to.path);
  const publicPages = ['/auth/login'];
  const authRequired = !publicPages.includes(to.path);
  const store = useStore();
  const authStoreState = store.state.auth;

  //next();
  
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if ( authRequired && (!authStoreState.user || (new Date()) >= (new Date(authStoreState.user.expiration_date))) ) {
      //authStore.returnUrl = to.fullPath;
      return next('/auth/login');
    } else next();
  } else {
    next();
  }
  
});

router.beforeEach(() => {
  //const uiStore = useUIStore();
  //uiStore.isLoading = true;
});

router.afterEach(() => {
  //const uiStore = useUIStore();
  //uiStore.isLoading = false;
});
