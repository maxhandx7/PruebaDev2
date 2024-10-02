<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4" v-if="!isAuthenticated">
        <h2 class="text-center mb-4">Login</h2>
        <form @submit.prevent="login">
          <div class="form-group mb-3">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              class="form-control"
              v-model="email"
              required
            />
          </div>
          <div class="form-group mb-3">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              class="form-control"
              v-model="password"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
      </div>
      <div class="col-md-4" v-if="isAuthenticated">
        <div class="jumbotron">
          <h1 class="display-3" v-if="user">{{ user.name }}</h1>
          <p class="lead">Prueba técnica para desarrollador web</p>
          <hr class="my-2" />
          <p>Crear nueva nota</p>
          <p class="lead">
            <a
              class="btn btn-primary btn-lg"
              href="/todo-list"
              target="_blank"
              role="button"
            >
              <i class="fa fa-arrow-right"></i> Ir</a
            >
          </p>
        </div>
      </div>
    </div>
    <div class="container">
      <footer
        class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top"
      >
        <p class="col-md-4 mb-0 text-body-secondary">© 2024 Alan Carabali</p>

        <a
          href="https://afdeveloper.com"
          target="_blank"
          class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"
        >
         <i class="fa fa-code"></i> Afdeveloper
        </a>

        <ul class="nav col-md-4 justify-content-end">
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-body-secondary"><i class="fab fa-facebook"></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-body-secondary"><i class="fab fa-twitter"></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-body-secondary"><i class="fab fa-instagram"></i></a>
          </li>
        </ul>
      </footer>
    </div>
  </div>
</template>
  
  
  <script>
export default {
  data() {
    return {
      email: "",
      password: "",
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
    async login() {
      try {
        const response = await axios.post("/oauth/token", {
          grant_type: "password",
          client_id: 2,
          client_secret: "wgdhDNocRTG7SFcbWpbq0p8d9ODGSeSbWwOMVjXT",
          username: this.email,
          password: this.password,
          scope: "",
        });
        // Guarda el token en el local storage o vuex
        localStorage.setItem("token", response.data.access_token);
        this.$emit("authenticated");
        this.$router.push({ name: "TodoList" });
      } catch (error) {
        console.error("Login failed", error);
      }
    },
    checkAuth() {
      // Verificar si el token de autenticación existe (puede estar en localStorage o cookies)
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
    logout() {
      // Acción de cierre de sesión
      localStorage.removeItem("token");
      this.isAuthenticated = false;
      this.$router.push("/");
    },
  },
};
</script>
