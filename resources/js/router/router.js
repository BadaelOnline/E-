import { createRouter, createWebHistory} from 'vue-router';
import about from '../components/pages/about.vue';

const routes = [
    
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
