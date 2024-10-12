const AuthRoutes = {
  path: '/auth',
  component: () => import('@/layouts/blank/BlankLayout.vue'),
  meta: {
    requiresAuth: false,
    requiresGuest: true
  },
  children: [
   
    {
      name: 'Login',
      path: '/auth/login',
      component: () => import('@/views/authentication/auth/LoginPage.vue'),
    
    },
    {
      name: 'Register',
      path: '/auth/register',
      component: () => import('@/views/authentication/auth/RegisterPage.vue')
    },
    {
      name: 'olvidepassword',
      path: '/auth/olvidepassword',
      component: () => import('@/views/authentication/auth/OlvidePassword.vue')
    },
    {
      name: 'resetpassword',
      path: '/auth/resetpassword/:token',
      component: () => import('@/views/authentication/auth/ResetPassword.vue')
    },
    {
      name: 'Error 404',
      path: '/pages/error',
      component: () => import('@/views/pages/maintenance/error/Error404Page.vue')
    }
  ]
};

export default AuthRoutes;
