import AllPosts from './components/AllPosts.vue';
import CreateOrUpdatePost from './components/CreateOrUpdatePost.vue';
export const routes = [
    {
        name: 'home',
        path: '/posts',
        component: AllPosts
    },
    {
        name: 'store',
        path: '/store',
        component: CreateOrUpdatePost
    },
];