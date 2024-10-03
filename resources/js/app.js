// Carga los archivos de configuración de Bootstrap
require('./bootstrap');

// Importa Vue
import vue from "vue";

// Define Vue globalmente para que esté disponible en todo el archivo
window.Vue = vue;

// Importa el componente principal de la aplicación
import App from './components/App.vue';

// Importa VueAxios para manejar solicitudes HTTP con Axios
import VueAxios from "vue-axios";
import axios from "axios";

// Importa VueRouter para manejar el enrutamiento dentro de la aplicación Vue
import VueRouter from "vue-router";

// Importa el método `reactive` de Vue para manejar el estado reactivo
import { reactive } from "vue";

// Importa las rutas desde un archivo externo
import { routes } from "./routes";

// Carga nuevamente Vue para usar sus plugins
import Vue from "vue";

// Utiliza VueRouter en la aplicación
Vue.use(VueRouter);

// Integra VueAxios y Axios en Vue
Vue.use(VueAxios, axios);

// Configuración del enrutador 
const router = new VueRouter({
    mode: 'history',  // Usa el modo 'history' para eliminar el hash 
    routes: routes    // Define las rutas de la aplicación
});

// Interceptor de solicitudes Axios
// Este bloque se ejecuta antes de cada solicitud HTTP y añade el token de autorización
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');  // Obtiene el token del almacenamiento local
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;  // Si existe el token, lo agrega a los encabezados
    }
    return config;
  }, error => {
    return Promise.reject(error);  // Si hay algún error, lo rechaza
  });

// Crea una nueva instancia de Vue
const app = new Vue({
    el: '#app',        // El componente principal se monta en el elemento con el id 'app'
    router: router,    // Se le pasa el enrutador configurado
    render: h => h(App)  // Renderiza el componente principal App.vue
});

// Define un middleware global en VueRouter
// Se ejecuta antes de cada cambio de ruta
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');  // Obtiene el token de autenticación
    // Si la ruta requiere autenticación y no hay token, redirige a la página de login
    if (to.matched.some(record => record.meta.requiresAuth) && !token) {
      next({ name: 'Login' });  // Redirige al login si no está autenticado
    } else {
      next();  // Si está autenticado o la ruta no requiere autenticación, sigue normalmente
    }
});

// Define un EventBus para manejar el estado reactivo de autenticación y usuario
export const eventBus = reactive({
    isAuthenticated: false,  // Estado de si el usuario está autenticado
    user: null               // Información del usuario autenticado
});

// Exporta el enrutador para que pueda ser utilizado en otras partes del proyecto
export default router;
