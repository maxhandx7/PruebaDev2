<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Formulario para agregar una nueva nota -->
        <h3 class="text-center mb-4">Agregar Nueva Nota</h3>
        <form @submit.prevent="createNote">
          <div class="form-group mb-3">
            <label for="title">Título:</label>
            <input
              v-model="newNote.title"
              type="text"
              class="form-control"
              id="title"
              required
              placeholder="Título de la nota"
            />
          </div>
          <div class="form-group mb-3">
            <label for="description">Descripción:</label>
            <textarea
              v-model="newNote.description"
              class="form-control"
              id="description"
              placeholder="Descripción"
            ></textarea>
          </div>
          <div class="form-group mb-3">
            <label for="due_date">Fecha de Vencimiento:</label>
            <input
              v-model="newNote.due_date"
              type="date"
              class="form-control"
              id="due_date"
            />
          </div>
          <div class="form-group mb-3">
            <label for="tags">Etiquetas:</label>
            <select
              v-model="selectedTags"
              multiple
              class="form-control"
              id="tags"
            >
              <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                {{ tag.name }}
              </option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            Agregar Nota
          </button>
        </form>
      </div>
      <div class="col-md-4">
        <h3 class="text-center mb-4">Lista de Notas</h3>

        <div v-if="notes.length">
          <ul class="list-group mb-4">
            <li v-for="note in notes" :key="note.id" class="list-group-item">
              <strong>{{ note.title }}</strong>
              <p>{{ note.description }}</p>
              <p>Fecha de vencimiento: {{ formatDate(note.due_date) }}</p>
              <p>
                Etiquetas:
                <span v-for="tag in note.tags" :key="tag.id">
                  {{ tag.name }}
                </span>
              </p>
              <button class="btn btn-warning" @click="EditNote(note.id)">
                <i class="fa fa-pen"></i>
              </button>
              <button class="btn btn-danger" @click="deleteNote(note.id)">
                <i class="fa fa-trash"></i>
              </button>
            </li>
          </ul>
        </div>
        <div v-else>
          <div class="alert alert-warning" role="alert">
            No hay notas disponibles.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notes: [],
      tags: [],
      success: false,
      selectedTags: [], // Nueva propiedad para almacenar los tags seleccionados
      newNote: {
        title: "",
        description: "",
        due_date: "",
      },
      loading: true,
    };
  },
  mounted() {
    this.fetchNotes();
    this.fetchTags(); // Cargar las etiquetas disponibles
  },
  methods: {
    async fetchNotes() {
      try {
        const response = await axios.get("/api/notes");
        this.notes = response.data;
        this.success = true;
      } catch (error) {
        console.error("Error:", error);
      }
    },
    async fetchTags() {
      try {
        const response = await axios.get("/api/tags");
        this.tags = response.data;
      } catch (error) {
        console.error("Error:", error);
      }
    },
    async createNote() {
      try {
        const newNoteData = {
          ...this.newNote,
          tags: this.selectedTags, // Añadir los tags seleccionados
        };
        await axios.post("/api/notes", newNoteData);
        this.newNote = {
          title: "",
          description: "",
          due_date: "",
        };
        Swal.fire("Nota creada!", "", "success");
        this.selectedTags = []; // Limpiar los tags seleccionados
        this.fetchNotes(); // Recargar la lista de notas
      } catch (error) {
        console.error("Error", error);
      }
    },
    async deleteNote(noteId) {
      try {
        await axios.delete(`/api/notes/${noteId}`);
        this.fetchNotes(); // Recargar la lista de notas
        Swal.fire("Nota eliminada!", "", "error");
      } catch (error) {
        console.error("Error", error);
      }
    },
    EditNote(noteId) {
      this.$router.push({ name: "EditNote", params: { id: noteId } });
    },
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString(undefined, options);
    },
  },
};
</script>

