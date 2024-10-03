<template>
  <div id="app">
    <!-- Barra de navegación principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <!-- Enlace al inicio de la aplicación -->
        <router-link class="navbar-brand" to="/">ToDo App </router-link>
        
        <!-- Botón de navegación para pantallas pequeñas -->
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido de la barra de navegación, se colapsa en pantallas pequeñas -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <!-- Lista de enlaces de la barra de navegación -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            <!-- Enlace al inicio -->
            <li class="nav-item">
              <router-link class="nav-link" to="/">Inicio</router-link>
            </li>
            
            <!-- Mostrar el enlace de "Registrar" solo si el usuario no está autenticado -->
            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/register">Registrar</router-link>
            </li>

            <!-- Mostrar los siguientes enlaces solo si el usuario está autenticado -->
            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/todo-list">Notas</router-link>
            </li>

            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/tags">Etiquetas</router-link>
            </li>

            <!-- Mostrar el nombre del usuario autenticado -->
            <li class="nav-item" v-if="isAuthenticated">
              <h4 class="nav-link text-light" v-if="user">
                {{ user.name }}
              </h4>
            </li>
          </ul>

          <!-- Botón para cerrar sesión, solo visible si el usuario está autenticado -->
          <ul class="navbar-nav">
            <li class="nav-item" v-if="isAuthenticated">
              <button class="btn btn-outline-danger ml-3" @click="logout">
                Cerrar sesión
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Donde se renderizan las rutas dinámicas definidas en Vue Router -->
    <router-view @authenticated="updateUserState" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null,           // Almacena los datos del usuario autenticado
      isAuthenticated: false, // Estado de autenticación del usuario
    };
  },

  // Este método se ejecuta cuando el componente se monta
  mounted() {
    this.checkAuth();   // Verifica si el usuario está autenticado
    this.fetchUser();   // Obtiene los datos del usuario si está autenticado
  },

  methods: {
    // Método para verificar si el usuario está autenticado
    checkAuth() {
      const token = localStorage.getItem("token"); // Busca el token en localStorage
      this.isAuthenticated = !!token;             // Si hay token, el usuario está autenticado
    },

    // Método para obtener los datos del usuario autenticado
    async fetchUser() {
      try {
        const token = localStorage.getItem("token");
        if (token) {
          // Realiza una solicitud GET a la API para obtener los datos del usuario
          const response = await axios.get("/api/user", {
            headers: {
              Authorization: `Bearer ${token}`,  // Envía el token en los headers
            },
          });
          this.user = response.data;  // Almacena los datos del usuario
        }
      } catch (error) {
        // En caso de error, muestra un mensaje en la consola
        console.error("Error al obtener el usuario:", error);
        this.user = null;  // Si hay error, establece 'user' como null
      }
    },

    // Actualiza el estado del usuario autenticado
    updateUserState() {
      this.checkAuth();  // Vuelve a verificar si el usuario está autenticado
    },

    // Método para cerrar sesión
    logout() {
      localStorage.removeItem("token");  // Elimina el token del almacenamiento local
      this.isAuthenticated = false;      // Cambia el estado a no autenticado
      this.$router.push("/");            // Redirige al usuario a la página de inicio
    },
  },
};
</script>

<style scoped>
/* Estilos para los enlaces de la barra de navegación */
.navbar-nav .nav-link {
  margin-right: 15px;
}
</style>
