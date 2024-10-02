//Establecer las rutas del front
const TodoList = () => import('./components/TodoList.vue');
const Login = () => import('./components/Login.vue');
const Register = () => import('./components/Register.vue');
const Tags = () => import('./components/TagList.vue');
const Edit = () => import('./components/EditNote.vue');




export const routes = [
    {
        name: 'Login',
        path: '/',
        component: Login
    },

    {
        name: 'Registro',
        path: '/register',
        component: Register
    },

    {
        name: 'Etiquetas',
        path: '/tags',
        component: Tags,
        meta: { requiresAuth: true }
    },

    {
        name: 'EditNote',
        path: '/edit-note/:id',
        component: Edit,
        meta: { requiresAuth: true }
    },


    {
        name: 'TodoList',
        path: '/todo-list',
        component: TodoList,
        meta: { requiresAuth: true }
    },

];