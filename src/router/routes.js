import authentication from './authentication.js'

const routes = [
    ...authentication,
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '/about', component: () => import('pages/IndexPage.vue') },
      { path: '/project', component: () => import('src/modules/Projects/ProjectComponent.vue'), name: 'projects' }
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
