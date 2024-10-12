const MainRoutes = {
  path: '/main',
  meta: {
    requiresAuth: true
  },
  redirect: '/main',
  component: () => import('@/layouts/dashboard/DashboardLayout.vue'),
  children: [
    {
      name: 'Dashboard',
      path: '/dashboard',
      component: () => import('@/views/dashboard/DefaultDashboard.vue')
    },
    {
      name: 'EditarPerfil',
      path: '/editarperfil',
      component: () => import('@/views/EditarPerfil/EditarPerfilPage.vue')
    },
    {
      name: 'Vender',
      path: '/vender',
      component: () => import('@/views/Vender/Vender.vue')
    },
    {
      name: 'Productos',
      path: '/productos',
      component: () => import('@/views/Productos/Productos.vue')
    },
    {
      name: 'Categorias',
      path: '/categorias',
      component: () => import('@/views/Categorias/Categorias.vue')
    },
   
    {
      name: 'Marcas',
      path: '/marcas',
      component: () => import('@/views/Marcas/Marcas.vue')
    },
    {
      name: 'Proveedores',
      path: '/proveedores',
      component: () => import('@/views/Proveedores/Proveedores.vue')
    },
  
  ]
};

export default MainRoutes;
