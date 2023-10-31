<script setup>
import {useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    title: '',
    description: null,
});

const emit = defineEmits(['onSuccess'])

const submit = () => {
    form.post(route('todos.add'), {
        onError: _ => {
            Swal.fire(
                `Error!`,
                'No se puedo crear la tarea.',
                'error'
            )
        },
        onFinish: params => {
            emit('onSuccess', params);
            Swal.fire(
                `Todo ok!`,
                'Tarea creada.',
                'success'
            )
        }
    });
}

</script>

<template>
    <form @submit.prevent="submit" class="p-4">
        <h4 class="text-xl font-semibold mb-4">
            Nueva Tarea
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

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Terminar
            </PrimaryButton>
        </div>
    </form>
</template>

