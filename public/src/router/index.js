import Vue from 'vue'
import VueRouter from 'vue-router'
import {store} from '../store/index'
import Home from '../views/Home.vue'
import Login from '../views/Login'
import Logout from '../views/Logout'
import User from '../views/User'
import Horses from '../views/Horses'
import Orders from '../views/Orders'
import Add from "../views/Add";
import EditH from "../views/EditH";
import Gebruikers from "../views/Gebruikers";
import Register from "../views/Register";
import List from "../views/List";


Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/logout',
        name: 'Logout',
        component: Logout,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user',
        name: 'User',
        component: User,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/horses',
        name: 'Horses',
        component: Horses
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/orders',
        name: 'Orders',
        component: Orders,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/add',
        name: 'Add',
        component: Add,
        meta: {
            requiresAuth: true,
            isAdmin: true,
        }
    },
    {
        path: '/edit',
        name: 'EditH',
        component: EditH,
        meta: {
            requiresAuth: true,
            isAdmin: true,
        }
    },
    {
        path: '/gebruikers',
        name: 'Gebruikers',
        component: Gebruikers,
        meta: {
            requiresAuth: true,
            isAdmin: true,
        }
    },
    {
        path: '/list',
        name: 'List',
        component: List,
        meta: {
            requiresAuth: true,
            isAdmin: true,
        }
    },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})
router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (localStorage.getItem("token") == null) {
            next({
                path: "/logout",
                params: {nextUrl: to.fullPath},
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.isAdmin)) {
        store.dispatch('getUserInfo').then(response => {
            if (response.data['rank'] == '1') {
                next();
            } else {
                next({
                    path: "/logout",
                    params: {nextUrl: to.fullPath},
                });
            }
        })
    } else {
        next();
    }
});

export default router
