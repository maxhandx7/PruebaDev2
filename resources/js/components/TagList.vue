<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h3 class="text-center mb-4">Gestión de Etiquetas</h3>

        <form @submit.prevent="createTag" class="mb-4">
          <div class="form-group">
            <label for="tag">Nueva etiqueta:</label>
            <input
              v-model="newTag"
              type="text"
              class="form-control"
              id="tag"
              placeholder="Nombre de la etiqueta"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary mt-2">
            Agregar Etiqueta
          </button>
        </form>
      </div>

      <div class="col-md-4">
        <h3 class="text-center mb-3">Etiquetas existentes</h3>
        <ul class="list-group">
          <li
            v-for="tag in tags"
            :key="tag.id"
            class="list-group-item d-flex justify-content-between align-items-center"
          >
            {{ tag.name }}
            <button class="btn btn-danger btn-sm" @click="deleteTag(tag.id)">
              Eliminar
            </button>
          </li>
        </ul>

        <div v-if="error" class="alert alert-danger mt-3">
          <p>{{ error }}</p>
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
      tags: [],
      newTag: "",
      error: null,
    };
  },
  mounted() {
    this.fetchTags();
  },
  methods: {
    async fetchTags() {
      try {
        const response = await axios.get("/api/tags");
        this.tags = response.data;
      } catch (error) {
        this.error = "Error al obtener las etiquetas.";
      }
    },
    async createTag() {
      try {
        await axios.post("/api/tags", { name: this.newTag });
        this.newTag = "";
        this.fetchTags();
        Swal.fire("Etiqueta creada!", "", "success");
      } catch (error) {
        this.error = "Error al crear la etiqueta.";
      }
    },
    async deleteTag(tagId) {
      try {
        await axios.delete(`/api/tags/${tagId}`);
        this.fetchTags();
        Swal.fire("Nota eñiminada!", "", "error");
      } catch (error) {
        this.error = "Error al eliminar la etiqueta.";
      }
    },
  },
};
</script>
  
  <style scoped>
</style>
  