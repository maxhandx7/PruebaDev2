<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">ToDo App </router-link>
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
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Inicio</router-link>
            </li>
            <!-- Mostrar el enlace de "Registrar" solo si el usuario no está autenticado -->
            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/register"
                >Registrar</router-link
              >
            </li>
            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/todo-list">Notas</router-link>
            </li>
            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/tags">Etiquetas</router-link>
            </li>
            <li class="nav-item" v-if="isAuthenticated">
              <h4 class="nav-link text-light"  v-if="user">
                {{ user.name }} 
              </h4>
            </li>
          </ul>
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
    <router-view @authenticated="updateUserState" />
  </div>
</template>
  
  <script>
export default {
  data() {
    return {
      user: null,
      isAuthenticated: false, // Estado de autenticación
    };
  },
  mounted() {
    // Comprobar si el usuario está autenticado al cargar el componente
    this.checkAuth();
    this.fetchUser();
  },
  methods: {
    checkAuth() {
      // Verificar si el token de autenticación existe  en localStorage
      const token = localStorage.getItem("token");
      this.isAuthenticated = !!token; // Autenticado si hay un token
    },
    async fetchUser() {
      try {
        const token = localStorage.getItem("token");
        if (token) {
          const response = await axios.get("/api/user", {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.user = response.data;
        }
      } catch (error) {
        console.error("Error al obtener el usuario:", error);
        this.user = null;
      }
    },
    updateUserState() {
      this.checkAuth();
    },
    logout() {
      // Acción de cierre de sesión
      localStorage.removeItem("token");
      this.isAuthenticated = false;
      this.$router.push("/");
    },
  },
};
</script>
  
  <style scoped>
.navbar-nav .nav-link {
  margin-right: 15px;
}
</style>
  