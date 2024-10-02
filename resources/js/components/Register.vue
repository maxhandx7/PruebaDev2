<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h1 class="text-center mb-4">Registro</h1>
        <form @submit.prevent="register">
          <div class="form-group mb-3">
            <label for="name">Nombre:</label>
            <input
              v-model="name"
              type="text"
              class="form-control"
              id="name"
              required
              placeholder="Tu nombre"
            />
          </div>
          <div class="form-group mb-3">
            <label for="email">Correo electrónico:</label>
            <input
              v-model="email"
              type="email"
              class="form-control"
              id="email"
              required
              placeholder="Tu correo"
            />
          </div>
          <div class="form-group mb-3">
            <label for="password">Contraseña:</label>
            <input
              v-model="password"
              type="password"
              class="form-control"
              id="password"
              required
              placeholder="Tu contraseña"
            />
          </div>
          <div class="form-group mb-3">
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input
              v-model="password_confirmation"
              type="password"
              class="form-control"
              id="password_confirmation"
              required
              placeholder="Confirma tu contraseña"
            />
          </div>
          <button type="submit" class="btn btn-primary w-100">
            Registrarse
          </button>
        </form>

        <div v-if="error" class="alert alert-danger mt-3">
          <p>{{ error }}</p>
        </div>

        <div v-if="successMessage" class="alert alert-success d-flex justify-content-between mt-3">
          <p>{{ successMessage }}</p>
          <a
            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
            href="/"
            >Iniciar sesión</a
          >
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import axios from "axios";

export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      error: null,
      successMessage: "",
    };
  },
  methods: {
    async register() {
      this.error = null;
      this.successMessage = "";

      try {
        const response = await axios.post("/api/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        this.successMessage =
          "Usuario registrado con éxito. Ahora puedes iniciar sesión.";
        this.resetForm();
        Swal.fire("Usuario registrado con éxito.", "Ahora puedes iniciar sesión.", "success");
      } catch (error) {
        this.error =
          error.response.data.message ||
          "Hubo un problema al registrar al usuario";
      }
    },
    resetForm() {
      this.name = "";
      this.email = "";
      this.password = "";
      this.password_confirmation = "";
    },
  },
};
</script>
  