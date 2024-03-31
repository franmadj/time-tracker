<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-wrap justify-between gap-5">

                    <div v-for="(project, index) of projects" :key="index" :draggable="true"
                        @dragstart="handleDragStart(index)" @dragover="handleDragOver" @drop="handleDrop(index)"
                        class="p-4 pl-9 pt-9 hover:opacity-90 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded relative overflow-hidden shadow-xl border border-slate-400 cursor-pointer">

                        <ProjectCard :project="project" />
                    </div>





                    <div
                        class="p-4 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded flex justify-center items-center shadow-2xl">
                        <svg class="cursor-pointer w-20 h-20 opacity-50" @click="newProject"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                        </svg>
                    </div>

                    <Modal :show="creatingNewProject" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                Create new Project
                            </h2>
                            <div class="mt-6">
                                <InputLabel for="name" value="name" class="sr-only" />
                                <TextInput id="name" ref="projectNameInput" v-model="form.name" type="text"
                                    class="mt-1 block w-3/4" placeholder="Project Name" @keyup.enter="storeProject" />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div class="mt-6">
                                <InputLabel for="notes" value="notes" class="sr-only" />
                                <textarea id="notes" ref="projectNameInput" v-model="form.notes" type="text"
                                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Project Name" @keyup.enter="storeProject"></textarea>
                                
                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div>
                            
                            <div class="mt-6">
                                <InputLabel for="hourly_rate" value="hourly_rate" class="sr-only" />
                                <div class="relative w-3/4">
                                    <span class="absolute right-2 top-[9px]">{{ form.currency }} / Hour</span>
                                    <TextInput id="hourly_rate" v-model="form.hourly_rate" type="text"
                                        class="mt-1 block w-3/4" placeholder="Hourly Rate" @keyup.enter="storeProject" />
                                </div>
                                <InputError :message="form.errors.hourly_rate" class="mt-2" />
                            </div>

                            



                            <div class="mt-6 flex justify-end">
                                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                                <SaveButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing" @click="storeProject">
                                    Save
                                </SaveButton>
                            </div>
                        </div>
                    </Modal>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SaveButton from '@/Components/SaveButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import ProjectCard from './Partials/ProjectCard.vue';
import { nextTick, ref, defineProps } from 'vue';

let creatingNewProject = ref(false);
const projectNameInput = ref(null);

const props = defineProps({
    projects: {
        type: Array,
    },
});

const draggedItem = ref(null);

const handleDragStart = (index) => {
    draggedItem.value = index;
}

const handleDragOver = (event) => {
    event.preventDefault();
}

const handleDrop = (index) => {
    const droppedItem = props.clients.value.splice(draggedItem.value, 1)[0];
    props.clients.value.splice(index, 0, droppedItem);
    draggedItem.value = null;

    let clientsOrder = [];
    let order = 0;

    props.clients.value.forEach(element => {
        clientsOrder.push({ 'id': element.id, 'order': order });
        order++;
    });

    console.log(clientsOrder)

    axios.patch(route('project.ordering'), { clientsOrder })
        .then(res => {
            if (res.data.success) {
                

            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
}


const form = useForm({
    name: '',
    hourly_rate: '25',
    notes: '',
});

const newProject = () => {
    console.log('d');
    creatingNewProject.value = true;
    nextTick(() => projectNameInput.value.focus());
};

const storeProject = () => {
    console.log(form);
    form
        .post(route('project.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => projectNameInput.value.focus(),
            onFinish: () => form.reset(),

        });
};

const closeModal = () => {
    creatingNewProject.value = false;
    form.reset();
};



</script>

