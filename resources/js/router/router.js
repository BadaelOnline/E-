import { createRouter, createWebHistory} from 'vue-router';
import about from '../components/pages/about.vue';

const routes = [
    {
        name:'about',
        path: '/',
        component: about
    }
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
