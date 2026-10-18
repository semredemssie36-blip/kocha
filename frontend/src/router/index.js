import { createRouter, createWebHistory } from 'vue-router'

// Route lazy loading (code-splitting) for sub-second performance
const routes = [
  // Public Routes
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/public/HomeView.vue'),
    meta: { title: 'Home' }
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('@/views/public/AboutView.vue'),
    meta: { title: 'About City' }
  },
  {
    path: '/government/mayor',
    name: 'Mayor',
    component: () => import('@/views/public/MayorView.vue'),
    meta: { title: "Mayor's Office" }
  },
  {
    path: '/government/sub-cities',
    name: 'SubCities',
    component: () => import('@/views/public/SubCitiesView.vue'),
    meta: { title: 'Sub-Cities' }
  },
  {
    path: '/government/offices',
    name: 'Offices',
    component: () => import('@/views/public/OfficesView.vue'),
    meta: { title: 'Offices Directory' }
  },
  {
    path: '/services',
    name: 'Services',
    component: () => import('@/views/public/ServicesView.vue'),
    meta: { title: 'Citizen Services' }
  },
  {
    path: '/media/news',
    name: 'News',
    component: () => import('@/views/public/NewsView.vue'),
    meta: { title: 'Latest News' }
  },
  {
    path: '/media/news/:slug',
    name: 'NewsDetail',
    component: () => import('@/views/public/NewsDetailView.vue'),
    meta: { title: 'News Article' }
  },
  {
    path: '/media/tenders',
    name: 'Tenders',
    component: () => import('@/views/public/TendersView.vue'),
    meta: { title: 'Tenders & Procurement' }
  },
  {
    path: '/media/vacancies',
    name: 'Vacancies',
    component: () => import('@/views/public/VacanciesView.vue'),
    meta: { title: 'Job Vacancies' }
  },
  {
    path: '/projects',
    name: 'Projects',
    component: () => import('@/views/public/ProjectsView.vue'),
    meta: { title: 'Mega Projects' }
  },
  {
    path: '/resources',
    name: 'Resources',
    component: () => import('@/views/public/ResourcesView.vue'),
    meta: { title: 'Document Center' }
  },
  {
    path: '/explore',
    name: 'Explore',
    component: () => import('@/views/public/ExploreView.vue'),
    meta: { title: 'Explore Kombolcha' }
  },
  {
    path: '/explore/:slug',
    name: 'ExploreDetail',
    component: () => import('@/views/public/ExploreDetailView.vue'),
    meta: { title: 'Explore Destination' }
  },
  {
    path: '/gallery',
    name: 'Gallery',
    component: () => import('@/views/public/GalleryView.vue'),
    meta: { title: 'Photo Gallery' }
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('@/views/public/ContactView.vue'),
    meta: { title: 'Contact Us' }
  },
  {
    path: '/internal-board',
    name: 'InternalBoard',
    component: () => import('@/views/public/InternalBoardView.vue'),
    meta: { title: 'Internal Board' }
  },

  // Admin Routes
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('@/views/admin/LoginView.vue'),
    meta: { title: 'Admin Login' }
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('@/views/admin/DashboardView.vue'),
    meta: { requiresAuth: true, title: 'Admin Dashboard' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' }
  }
})

// Navigation & Authentication Guards
router.beforeEach((to) => {
  const token = localStorage.getItem('kombolcha_admin_token')

  // Set document title dynamically
  if (to.meta.title) {
    document.title = `${to.meta.title} | Kombolcha Metropolitan Administration`
  }

  // 1. If user is already authenticated and visits /admin/login, redirect to /admin dashboard
  if (to.path === '/admin/login' && token) {
    return '/admin'
  }

  // 2. If route requires authentication and user is not logged in, redirect to /admin/login
  if (to.meta.requiresAuth && !token) {
    return '/admin/login'
  }
})

export default router
