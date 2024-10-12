import { createRouter, createWebHistory } from 'vue-router';
import MainRoutes from './MainRoutes';
import AuthRoutes from './AuthRoutes';
import { useAuthStore } from '@/stores/auth';
import { useUIStore } from '@/stores/ui';

export const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      name: 'index',
      path: '/',
      component: () => import('@/views/pages/Index.vue'),
    
    },
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
  login(email: string, password: string): Promise<void>;
  logout(): void;
}

router.beforeEach(async (to, from, next) => {
  // Define las páginas públicas y las páginas de autenticación
  const publicPages = ['/auth/login', '/auth/register','/auth/olvidepassword','/auth/resetpassword/:token',];
  const authRequired = !publicPages.includes(to.path);
  const auth: AuthStore = useAuthStore();

  // Verifica si la ruta requiere autenticación y si el usuario está autenticado
  if (authRequired && to.matched.some((record) => record.meta.requiresAuth)) {
    if (!auth.user) {
      auth.returnUrl = to.fullPath;
      return next('/auth/login');
    } else {
      return next();
    }
  }

  // Impide el acceso a las páginas de login y registro si el usuario ya está autenticado
  if (publicPages.includes(to.path) && auth.user) {
    return next('/dashboard'); // Redirige al dashboard o a la página principal
  }

  next();
});

router.beforeEach(() => {
  const uiStore = useUIStore();
  uiStore.isLoading = true;
});

router.afterEach(() => {
  const uiStore = useUIStore();
  uiStore.isLoading = false;
});
