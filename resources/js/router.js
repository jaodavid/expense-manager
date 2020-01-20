import VueRouter from 'vue-router'// Pages
import Home from './components/pages/Home'
import Register from './components/pages/Register'
import Login from './components/pages/Login'
import Dashboard from './components/pages/Dashboard'
import AdminDashboard from './components/pages/admin/Dashboard'
import UserManagement from './components/UserManagement'
import RoleManagement from './components/RoleManagement'
import CategoryManagement from './components/CategoryManagement'
import ExpenseManagement from './components/ExpenseManagement'
import ChangePass from './components/pages/ChangePass'

// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Login,
    meta: {
      auth: undefined
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
  // CHANGE PASSWORD
  {
    path: '/change-password',
    name: 'Change Password',
    component: ChangePass,
    meta: {
      auth: true
    }
  },
  // USER ROUTES
  {
    path: '/dashboard',
    name: 'My Expenses',
    component: Dashboard,
    meta: {
      auth: true
    },
    children: [
        {
          path: 'expense-management',
          name: 'Expense Management',
          component: ExpenseManagement
        }
      ]
  },
  // ADMIN ROUTES
  {
    path: '/admin',
    name: 'My Expenses (Admin)',
    component: AdminDashboard,
    meta: {
      auth: {roles: 1, redirect: {name: 'login'}, forbiddenRedirect: '/403'}
    },
    children: [
        {
          path: 'user-management',
          name: 'User Management',
          component: UserManagement
        },
        {
          path: 'roles-management',
          name: 'Role Management',
          component: RoleManagement
        },
        {
          path: 'category-management',
          name: 'Category Management',
          component: CategoryManagement
        },
        {
          path: 'expense-management',
          name: 'Expense Management',
          component: ExpenseManagement
        }
      ]
  },
]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})

export default router