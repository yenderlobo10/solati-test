<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {onMounted, ref} from "vue";
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faPenToSquare, faTrashCan} from '@fortawesome/free-regular-svg-icons'
import Swal from "sweetalert2";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TodoFormAdd from "@/Pages/Todo/Partials/TodoFormAdd.vue";
import TodoFormEdit from "@/Pages/Todo/Partials/TodoFormEdit.vue";

library.add(faPenToSquare, faTrashCan);
const props = defineProps({
    userId: {type: String},
    token: {type: String}
});

const todos = ref([]);
const todoEditing = ref(null);
const showEditModal = ref(false);
const isModalEdit = ref(false);
const authHeader = {
    'Authorization': `Bearer ${props.token}`
};

onMounted(() => loadAll());

function loadAll() {
    axios.get(`/api/todos/${props.userId}`, {
        headers: authHeader
    })
        .then(response => {
            todos.value = response.data;
        })
        .catch(error => {
            console.error(error)
        });
}

const openEditModal = (todo) => {
    todoEditing.value = todo;
    isModalEdit.value = true;
    showEditModal.value = true;
}

const openCreateModal = () => {
    isModalEdit.value = false;
    showEditModal.value = true;
}

const closeEditModal = (isRedirect) => {
    showEditModal.value = false;
    isModalEdit.value = false;

    if (isRedirect) loadAll();
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Seguro quiere eliminar la tarea?',
        text: "Esta acción no se puede revertir!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarla!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) deleteTodo(id);
    })
}

function deleteTodo(id) {
    axios.delete(`/api/todos/${id}`, {
        headers: authHeader
    })
        .then(_ => {
            loadAll();
            Swal.fire({
                title: 'Todo OK',
                text: 'Tarea eliminada',
                icon: 'success'
            })
        })
        .catch(_ => {
            Swal.fire({
                title: 'Error',
                text: 'Uy! algo salió mal intentando eliminar la tarea.',
                icon: 'error'
            })
        });
}

</script>

<template>
    <Head title="Tareas"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row">
                <h2 class="flex-1 font-semibold text-xl text-gray-800 leading-tight">Tareas</h2>
                <PrimaryButton @click.prevent="openCreateModal" class="">Nueva Tarea</PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-3 gap-4" v-if="todos">

                    <div v-for="todo in todos" class="flex flex-col rounded-lg p-4 shadow"
                         :class="[ todo.finished ? 'bg-green-200' : 'bg-white' ]">
                        <h4 class="text-lg font-semibold text-slate-800">{{ todo.title }}</h4>
                        <p v-if="todo.description" class="text-base text-slate-700 flex-1">{{ todo.description }}</p>
                        <div class="flex gap-2 justify-end">
                            <button @click="openEditModal(todo)"
                                    class="text-2xl font-semibold p-4 rounded-full focus:outline-none hover:bg-indigo-200"
                                    v-if="!todo.finished">
                                <font-awesome-icon icon="fa-regular fa-pen-to-square" style="color:#0753f6"/>
                            </button>
                            <button @click="confirmDelete(todo.id)"
                                    class="text-2xl font-semibold p-4 rounded-full focus:outline-none hover:bg-indigo-200">
                                <font-awesome-icon icon="fa-regular fa-trash-can" style="color:#f60756"/>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded flex flex-col items-center p-8" v-if="todos.length === 0">
                    <img src="../../../images/empty-folder.png" width="96" alt="empty"/>
                    <h3 class="text-xl font-medium">Aún no ha creado tareas.</h3>
                </div>
            </div>
        </div>

        <Modal :show="showEditModal" @close="closeEditModal">
            <template v-if="isModalEdit">
                <TodoFormEdit :todo="todoEditing" @onSuccess="closeEditModal"/>
            </template>
            <template v-else>
                <TodoFormAdd @onSuccess="closeEditModal"/>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
