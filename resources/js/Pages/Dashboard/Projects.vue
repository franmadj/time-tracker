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
                                <button type="button" :disabled="calculateDisabled" @click="calculate"
                                    class="grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Calculate</button>
                                <InputLabel for="name" value="name" class="sr-only" />
                                <TextInput id="name" ref="searchInput" @keyup="searchProject" v-model="searchTerm"
                                    type="text" class="grow" placeholder="Search by Project Name" />
                        </div>
                        <h1>{{ client.name }}</h1>

                    </div>

                    <div class="flex gap-2 items-center mb-2">
                        <InputLabel for="selectall" value="Select All" class="text-black text-xl font-light leading-5"
                            @click.stop />
                        <input type="checkbox" id="selectall" v-model="selectAll" @click.stop="doSelectAll()">
                    </div>



                    <div class="flex flex-wrap justify-between gap-5">

                        <div v-for="(project, index) of projectList" :key="index" :draggable="true"
                            @click="viewProject(project.id)" @dragstart="handleDragStart(index)"
                            v-show="project.show && !project.deleted" @dragover="handleDragOver"
                            @drop="handleDrop(index)" :style="'order:' + project.order"
                            class="p-3 hover:opacity-90 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-[360px] rounded relative overflow-hidden shadow-xl border border-slate-400 cursor-pointer">
                            <ProjectCard :project="project" :client="client" @delete:project="deleteProject"
                                @update:project="updateProject" :index="index" />
                        </div>

                        <div
                            class="p-4 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded flex justify-center items-center shadow-2xl order-[999999]">
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
                                <textarea id="notes" v-model="form.notes" type="text"
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
                            <div class="mt-6">
                                <InputLabel for="hourly_rate_two" value="hourly rate two" class="sr-only" />
                                <div class="relative w-3/4">
                                    <span class="absolute right-2 top-[9px]">{{ props.client.currency }} / Hour</span>
                                    <TextInput id="hourly_rate_two" v-model="form.hourly_rate_two" type="text"
                                        class="mt-1 block w-3/4" placeholder="Second Hourly Rate"
                                        @keyup.enter="storeProject" />
                                </div>
                                <InputError :message="form.errors.hourly_rate_two" class="mt-2" />
                            </div>
                            <div class="mt-6 flex justify-end">
                                <SecondaryButton @click="closeModal"> Close </SecondaryButton>
                                <SaveButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing" @click="storeProject">
                                    Save
                                </SaveButton>
                            </div>
                        </div>
                    </Modal>

                    <Modal :show="calculating" @close="closeCalculateModal" maxWidth="screenlg">
                        <div class="p-6">
                            <div class="flex justify-between items-center gap-2 mb-5">
                                <h2 class="text-xl font-bold text-gray-900">
                                    Reports
                                </h2>
                                

                                <div class="flex gap-1 justify-between items-center">
                                    <button type="button" :disabled="tableReportDisabled"
                                        @click="tableReportDisabled = true; textReportDisabled = false;"
                                        class="grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        Table</button>
                                    <button type="button" :disabled="textReportDisabled"
                                        @click="tableReportDisabled = false; textReportDisabled = true;"
                                        class="grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        Text</button>

                                </div>
                            </div>
                            <table class="w-full" v-show="tableReportDisabled">
                                <tr>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Earnings</th>
                                </tr>
                                <tr v-for="(selectedProject, index) in selectedProjects" :key="index">
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        {{ selectedProject.name }}
                                    </td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        {{ helpers.makeTimeClock(selectedProject.total_time) }}</td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white">
                                        {{ calculateEarnings(selectedProject) }} {{ client.currency }}
                                        <span v-show="calculateEarnings(selectedProject, true) > 0"> / {{
                                            calculateEarnings(selectedProject, true) }} {{ client.currency }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        Totals
                                    </td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        {{ totalTimeSum }}</td>
                                    <td
                                        class="text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white">
                                        {{ totalEarningsSum }}
                                        <span v-show="totalEarningsSumTwo > 0"> / {{ totalEarningsSumTwo }} {{
                                            client.currency
                                        }}</span>
                                    </td>
                                </tr>

                            </table>
                            <div v-show="textReportDisabled">
                                <div v-for="(selectedProject, index) in selectedProjects" :key="index" class="mb-5">
                                    <p class="text-xl">{{ selectedProject.name }}</p>
                                    <p>{{ helpers.makeTimeClock(selectedProject.total_time) }}</p>
                                    <p>{{ calculateEarnings(selectedProject) }}
                                        <span v-show="calculateEarnings(selectedProject, true) > 0"> / {{
                                            calculateEarnings(selectedProject, true) }} {{ client.currency }}</span>
                                    </p>
                                </div>

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
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SaveButton from '@/Components/SaveButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import ProjectCard from './Partials/ProjectCard.vue';
import helpers from '../../Composables/helpers'
import { nextTick, ref, defineProps, onMounted, computed } from 'vue';

const creatingNewProject = ref(false);
const projectNameInput = ref(null);
const searchInput = ref(null);
const selectedProjects = ref([]);
const selectAll = ref(false);
const calculating = ref(false);
const tableReportDisabled = ref(true);
const textReportDisabled = ref(false);


const props = defineProps({
    projects: {
        type: Array,
    },
    client: {
        type: Object,
    },
    searchTerm: {
        type: String,
    },
});

const projectList = computed(() => props.projects);

onMounted(() => {
    showList();
});

const showList = () => {
    projectList.value.forEach((project, index) => {
        projectList.value[index].show = true;

    });

}




const searchTerm = ref('');
let timeout = 0;
const searchProject = (e) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        projectList.value.forEach((project, index) => {
            if (project.name.toLowerCase().includes(searchTerm.value.toLowerCase()))
                projectList.value[index].show = true;
            else
                projectList.value[index].show = false;
        });
    }, 500);
}

const doSelectAll = () => {
    setTimeout(() => {
        projectList.value.forEach((project, index) => {
            projectList.value[index].selected = selectAll.value;
        });
    }, 100)
}

const deleteProject = (index) => {
    projectList.value[index].deleted = true
}

const updateProject = () => {
    nextTick(() => showList());
}


const viewProject = (id) => {
    router.get(route('project.show', id))
}



/************DRAG & DROP********/
/*******************************/
/*******************************/
/*******************************/
const draggedItemIndex = ref(null);
const handleDragStart = (index) => {
    draggedItemIndex.value = index;
}
const handleDragOver = (event) => {
    event.preventDefault();
}
const handleDrop = (dropItemIndex) => {
    const dragOrder=projectList.value[draggedItemIndex.value].order;
    const droppedOrder=projectList.value[dropItemIndex].order;

    projectList.value[draggedItemIndex.value].order=droppedOrder;
    projectList.value[dropItemIndex].order=dragOrder;

    let projectsOrder = [];
    projectList.value.forEach(element => {
        projectsOrder.push({ 'id': element.id, 'order': element.order, 'name': element.name });
    });

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


/************CREATE PROJECT*****/
/*******************************/
/*******************************/
/*******************************/
const form = useForm({
    client_id: props.client.id,
    client_slug: props.client.slug,
    name: '',
    hourly_rate: String(props.client.hourly_rate),
    hourly_rate_two: String(props.client.hourly_rate_two),
    notes: '',
});
const newProject = () => {
    creatingNewProject.value = true;
    nextTick(() => projectNameInput.value.focus());
};
const storeProject = () => {
    console.log(form);
    form
        .post(route('project.store'), {
            preserveScroll: true,
            onSuccess: () => { closeModal(); nextTick(() => showList()) },
            onError: () => projectNameInput.value.focus()

        });
};
const closeModal = () => {
    creatingNewProject.value = false;
    form.reset();
};



/*********CALCULATE PROJECT*****/
/*******************************/
/*******************************/
/*******************************/
const totalTimeSum = ref(0);
const totalEarningsSum = ref(0);
const totalEarningsSumTwo = ref(0);

const calculateEarnings = (project, two = false) => {
    let hourlyRate = two ? project.hourly_rate_two : project.hourly_rate;
    return parseFloat(((project.total_time / 60) / 60) * hourlyRate).toFixed(2);
}

const calculateDisabled = computed(() => {
    return !projectList.value.filter((project) => {
        return project.selected
    }).length;

});

const calculate = () => {
    selectedProjects.value = projectList.value.filter((project) => {
        return project.selected;
    });

    let totalEarnings = 0;
    let totalEarningsTwo = 0;

    const totalSeconds = selectedProjects.value.reduce((total, project) => {
        totalEarnings += parseFloat(calculateEarnings(project));
        totalEarningsTwo += parseFloat(calculateEarnings(project, true));
        console.log(totalEarnings);
        return total + project.total_time;
    }, 0);

    totalTimeSum.value = helpers.makeTimeClock(totalSeconds)
    totalEarningsSum.value = totalEarnings.toFixed(2);
    totalEarningsSumTwo.value = totalEarningsTwo.toFixed(2);
    console.log(selectedProjects.value);
    calculating.value = true;
};
const closeCalculateModal = () => {
    calculating.value = false;
};



</script>
