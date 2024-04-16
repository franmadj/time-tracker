<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">



                    <div class="flex justify-between items-center mb-3 gap-2">
                        <div class="flex gap-3 grow">
                            <Link :href="route('client.index')"
                                class="grow-0 inline-flex items-center w-fit px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            << Back</Link>
                                <button type="button" @click="calculate"
                                    class="grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Calculate</button>
                                <InputLabel for="name" value="name" class="sr-only" />
                                <TextInput id="name" ref="projectNameInput" @keyup="searchProject" v-model="formSearch.name" type="text"
                                    class="grow" placeholder="Search by Project Name" />
                        </div>
                        <h1>{{ client.name }}</h1>

                    </div>

                    <div class="flex gap-2 items-center mb-2">
                        <InputLabel for="selectall" value="Select All" class="text-black text-xl font-light leading-5"
                            @click.stop />
                        <input type="checkbox" id="selectall" v-model="selectAll" @click.stop="doSelectAll()">
                    </div>



                    <div class="flex flex-wrap justify-between gap-5">

                        <div v-for="(project, index) of projects" :key="index" :draggable="true"
                            @click="viewProject(project.id)" @dragstart="handleDragStart(index)"
                            @dragover="handleDragOver" @drop="handleDrop(index)"
                            class="p-3 hover:opacity-90 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-[360px] rounded relative overflow-hidden shadow-xl border border-slate-400 cursor-pointer">
                            <ProjectCard :project="project" :client="client" @delete:project="deleteProject" />
                        </div>

                        <div
                            class="p-4 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded flex justify-center items-center shadow-2xl">
                            <svg class="cursor-pointer w-20 h-20 opacity-50" @click="newProject"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                            </svg>
                        </div>
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
                                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    placeholder="Notes" @keyup.enter="storeProject"></textarea>

                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div>

                            <div class="mt-6">
                                <InputLabel for="hourly_rate" value="hourly_rate" class="sr-only" />
                                <div class="relative w-3/4">
                                    <span class="absolute right-2 top-[9px]">{{ props.client.currency }} / Hour</span>
                                    <TextInput id="hourly_rate" v-model="form.hourly_rate" type="text"
                                        class="mt-1 block w-3/4" placeholder="Hourly Rate"
                                        @keyup.enter="storeProject" />
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

                    <Modal :show="calculating" @close="closeCalculateModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-2">
                                Calculations
                            </h2>
                            <table class="w-full">
                                <tr>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Earnings</th>
                                </tr>
                                <tr v-for="(selectedProject, index) in selectedProjects" :key="index">
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        {{
                                            selectedProject.name }}</td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        {{
                                            makeTimeClock(selectedProject.total_time) }}</td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        300
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        Totals
                                    </td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        {{
                                            makeTimeClock(99999) }}</td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        300
                                    </td>
                                </tr>

                            </table>


                        </div>
                    </Modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SaveButton from '@/Components/SaveButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import ProjectCard from './Partials/ProjectCard.vue';
import { nextTick, ref, defineProps, onMounted, computed } from 'vue';
import axios from 'axios';

const creatingNewProject = ref(false);
const projectNameInput = ref(null);
const projects = ref([]);
const selectedProjects = ref([]);
const selectAll = ref(false);
const calculating = ref(false);
//const searchProject = ref('');

const props = defineProps({
    projects: {
        type: Array,
    },
    client: {
        type: Object,
    },
});

onMounted(() => {
    console.log('props', props);
    projects.value = props.projects;
});


const formSearch = useForm({
    name: ''
});
const searchProject=(e)=>{
    //console.log(props.client.slug,e.target.value, route('client.search', [props.client.slug, e.target.value]));
  
    axios
        .get(route('client.search', [props.client.slug, e.target.value]))
        .then(res => {
            console.log(res);
            if (200==res.status) {
                projects.value = res.data.projects;

            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
}


const doSelectAll = () => {
    setTimeout(() => {
        console.log(selectAll.value)
        projects.value = projects.value.map((project) => {
            project.selected = selectAll.value;
            return project;

        });
        console.log(projects.value);
    }, 100)

}

const deleteProject = () => {
    //projects.value = props.projects;
    //router.reload({ only: ['projects'] })
}

const makeTimeClock = (totalSeconds) => {
    let clockHours = Math.floor(totalSeconds / (60 * 60));
    let clockMinutes = Math.floor((totalSeconds % (60 * 60)) / (60));
    let clockSeconds = Math.floor((totalSeconds % (60)) / 1);
    clockHours = (clockHours < 10 ? "0" : "") + clockHours;
    clockMinutes = (clockMinutes < 10 ? "0" : "") + clockMinutes;
    clockSeconds = (clockSeconds < 10 ? "0" : "") + clockSeconds;
    return (clockHours + ':' + clockMinutes + ':' + clockSeconds);

}

const viewProject = (id) => {
    console.log(id);
    router.get(route('project.show', id))
}

const draggedItem = ref(null);

const handleDragStart = (index) => {
    draggedItem.value = index;
}

const handleDragOver = (event) => {
    event.preventDefault();
}

const handleDrop = (index) => {
    console.log(props.projects);
    const droppedItem = props.projects.splice(draggedItem.value, 1)[0];
    props.projects.splice(index, 0, droppedItem);
    draggedItem.value = null;
    let projectsOrder = [];
    let order = 0;
    props.projects.forEach(element => {
        projectsOrder.push({ 'id': element.id, 'order': order });
        order++;
    });

    console.log(projectsOrder)

    axios.patch(route('project.ordering'), { projectsOrder })
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
    client_id: props.client.id,
    client_slug: props.client.slug,
    name: '',
    hourly_rate: String(props.client.hourly_rate),
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
            onSuccess: () => { closeModal() },
            onError: () => projectNameInput.value.focus()

        });
};

const closeModal = () => {
    creatingNewProject.value = false;
    form.reset();
};

const calculate = () => {
    selectedProjects.value = projects.value.filter((project) => {
        return project.selected;

    });
    console.log(selectedProjects.value);
    calculating.value = true;
};

const closeCalculateModal = () => {
    calculating.value = false;
};



</script>
