<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import axios from "axios";
import {onMounted, ref} from "vue";
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPenToSquare, faTrashCan } from '@fortawesome/free-regular-svg-icons'

library.add(faPenToSquare, faTrashCan);
const props = defineProps({
    userId: {type: String}
});

const todos = ref([]);

onMounted(() => loadAll());

function loadAll() {
    axios.get(`/api/todos/2`)
        .then(response => {
            todos.value = response.data;
        })
        .catch(error => {
            console.error(error)
        });
}

</script>

<template>
    <Head title="Tareas"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tareas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-flow-col grid-cols-3 gap-4">

                    <div v-for="todo in todos" class="flex flex-col bg-white rounded-lg p-4 shadow">
                        <h4 class="text-lg font-semibold text-slate-800">{{ todo.title }}</h4>
                        <p v-if="todo.description" class="text-base text-slate-700 flex-1">{{ todo.description}}</p>
                        <div class="flex gap-2 justify-end">
                            <button class="text-2xl font-semibold p-4 rounded-full focus:outline-none hover:bg-indigo-200">
                                <font-awesome-icon icon="fa-regular fa-pen-to-square" style="color:#0753f6" />
                            </button>
                            <button class="text-2xl font-semibold p-4 rounded-full focus:outline-none hover:bg-indigo-200">
                                <font-awesome-icon icon="fa-regular fa-trash-can" style="color:#f60756" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
