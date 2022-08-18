import AllPosts from './components/AllPosts.vue';
import AddPost from './components/AddPost.vue';
import EditPost from './components/EditPost.vue';
export const routes = [
    {
        name: 'home',
        path: '/post',
        component: AllPosts
    },
    {
        name: 'add',
        path: '/add',
        component: AddPost
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditPost
    }
];