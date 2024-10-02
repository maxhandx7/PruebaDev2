<template>
  <div class="container mt-4">
    <h2>Editar Nota</h2>

    <!-- Muestra un mensaje de carga mientras se obtienen los datos -->
    <div v-if="loading">Cargando datos de la nota...</div>

    <!-- Formulario de edición -->
    <form v-if="!loading" @submit.prevent="editNote">
      <div class="form-group">
        <label for="title">Título</label>
        <input
          type="text"
          class="form-control"
          id="title"
          v-model="note.title"
          required
        />
      </div>

      <div class="form-group mt-3">
        <label for="description">Descripción</label>
        <textarea
          class="form-control"
          id="description"
          v-model="note.description"
          required
        ></textarea>
      </div>

      <div class="form-group mt-3">
        <label for="due_date">Fecha de Vencimiento</label>
        <input
          type="date"
          class="form-control"
          id="due_date"
          v-model="note.due_date"
          required
        />
      </div>

      <div class="form-group mt-3">
        <label for="tags">Etiquetas</label>
        <input type="text" class="form-control" id="tags" v-model="note.tags" />
      </div>

      <button type="submit" class="btn btn-primary mt-3">
        Guardar Cambios
      </button>
    </form>

    <!-- Botón para regresar -->
    <button class="btn btn-secondary mt-3" @click="goBack">Cancelar</button>
  </div>
</template>
  
  <script>
export default {
  data() {
    return {
      note: {
        title: "",
        description: "",
        due_date: "",
        tags: [],
      },
      loading: true, // Indicador de carga
    };
  },
  mounted() {
    this.fetchNote(); // Llamamos a la función para cargar los datos cuando el componente se monta
  },
  methods: {
    async fetchNote() {
      const noteId = this.$route.params.id; // Obtiene el ID de la nota desde las rutas
      const token = localStorage.getItem("token"); // Obtener token del usuario
      try {
        const response = await axios.get(`/api/notes/${noteId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.note = response.data; // Cargar los datos de la nota en el formulario
        this.loading = false; // Desactivar indicador de carga
      } catch (error) {
        console.error("Error al cargar la nota:", error);
        this.loading = false; // Desactivar indicador de carga en caso de error
      }
    },
    async editNote() {
      const noteId = this.$route.params.id; // Obtiene el ID de la nota
      const token = localStorage.getItem("token"); // Obtener token del usuario
      try {
        await axios
          .put(`/api/notes/${noteId}`, this.note, {
            headers: { Authorization: `Bearer ${token}` },
          })
          .then(Swal.fire("Nota editada!", "", "success"));

        this.$router.push({ name: "TodoList" }); // Redirigir a la lista de notas después de guardar
      } catch (error) {
        console.error("Error al editar la nota:", error);
      }
    },
    goBack() {
      this.$router.push({ name: "TodoList" }); // Redirige a la lista de notas al cancelar
    },
  },
};
</script>
  
  <style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
</style>
  