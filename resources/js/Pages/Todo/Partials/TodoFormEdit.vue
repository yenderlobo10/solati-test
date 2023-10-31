<script setup>
import {useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {onMounted} from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(
    {
        todo: {
            type: Object,
            required: true,
        },
    }
);

const form = useForm({
    id: 0,
    title: '',
    description: null,
    finished: false,
});

const emit = defineEmits(['onSuccess'])

onMounted(() => {
    let todo = props.todo;
    form.id = todo.id;
    form.title = todo.title;
    form.description = todo.description;
    form.finished = todo.finished;
});

const submit = () => {
    form.put(route('todos.edit'), {
        onError: _ => {
            Swal.fire(
                `Error!`,
                'No se pudieron guardar los cambios.',
                'error'
            )
        },
        onFinish: params => {
            emit('onSuccess', params);
            Swal.fire(
                `Todo ok!`,
                'Tarea actualizada.',
                'success'
            )
        }
    });
}

</script>

<template>
    <form @submit.prevent="submit" class="p-4">
        <h4 class="text-xl font-semibold mb-4">
            Editar Tarea
        </h4>
        <div>
            <InputLabel for="title" value="Título"/>

            <TextInput
                id="title"
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                required
                autofocus
                autocomplete="title"
            />

            <InputError class="mt-2" :message="form.errors.title"/>
        </div>

        <div class="mt-4">
            <InputLabel for="description" value="Descripción"/>

            <TextInput
                id="description"
                type="text"
                class="mt-1 block w-full"
                v-model="form.description"
                autocomplete="description"
            />
        </div>

        <div class="mt-4">
            <Checkbox v-model:checked="form.finished"/>
            <span class="ml-2 text-sm text-gray-600">Finalizada</span>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Terminar
            </PrimaryButton>
        </div>
    </form>
</template>
