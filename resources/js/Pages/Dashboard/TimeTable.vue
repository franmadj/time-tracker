<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Project</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="flex justify-between">
                        <Link :href="route('client.show',client.slug)"
                        class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        >Back</Link>
                        <h1 class="text-gray-200">{{ project.name }}</h1>
                        <p class="text-gray-300">Total time: <span class="font-bold text-gray-200">{{ totalTimeDisplay }}</span></p>
                    </div>
                    
                    <h3 class="text-center my-3 text-xl font-normal text-gray-200">Time Table</h3>
                    <table class="w-full mx-auto">
                        <tbody>
                            <tr>
                                <th class="text-center mb-2 text-gray-300">StartTime</th>
                                <th class="text-center mb-2 text-gray-300">EndTime</th>
                                <th class="text-center mb-2 text-gray-300">Total Time</th>
                            </tr>
                            <tr v-for="(time, index) in project.times" :key="index">

                                <td class="text-center px-5 py-1 text-lg font-normal bg-blue-900 text-gray-200 border border-b-white">{{
                                    formatDateTime(time.started_at) }}</td>
                                <td class="text-center py-1 text-lg font-normal bg-red-900 text-gray-200 border border-b-white">{{
                                    formatDateTime(time.ended_at) }}</td>
                                <td
                                    class="py-1 text-lg font-normal flex items-center justify-center bg-green-800 text-gray-200 border border-b-white">
                                    {{ getTotalDiffTime(time.started_at, time.ended_at) }} <svg
                                        @click="deleteModal(time.id)" class="w-3 h-3 cursor-pointer fill-gray-200 ml-2"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path
                                            d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z">
                                        </path>
                                    </svg>
                                </td>
                            </tr>


                        </tbody>
                    </table>



                </div>
            </div>
        </div>
        <Modal :show="confirmingDeletion" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-200">
                    Are you sure you want to delete this item?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>
                    <DangerButton class="ms-3" :class="{ 'opacity-25': formDelete.processing }"
                        :disabled="formDelete.processing" @click="deleteTime">
                        Delete Item
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link  } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { nextTick, ref, onMounted } from 'vue';

const totalTimeDisplay = ref(false);

const props = defineProps({
    project: {
        type: Object,
    },
    client: {
        type: Object,
    },
});




onMounted(() => {
    console.log('project', props.project);
    if (props.project.total_time) {
        totalTimeDisplay.value = makeTimeClock(props.project.total_time);
    }
});

const goBack=()=>{
    
}

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('it-IT', { timeZone: 'UTC' });
}

const getTotalDiffTime = (startDateStr, endDateStr) => {
    const startDate = new Date(startDateStr);
    const endDate = new Date(endDateStr);
    const diff = endDate.getTime() - startDate.getTime();
    const totalSeconds = Math.round(diff / (1000));
    return makeTimeClock(totalSeconds);
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




/*********DELETE MODAL**********/
/*******************************/
/*******************************/
/*******************************/
const confirmingDeletion = ref(false);
let deleteId = false;
const formDelete = useForm({
    name: ''
});
const deleteModal = (id) => {
    deleteId = id;
    confirmingDeletion.value = true;
};
const deleteTime = () => {
    formDelete.delete(route('timeTable.destroy', deleteId), {
        preserveScroll: true,
        onSuccess: () => {closeDeleteModal(); totalTimeDisplay.value = makeTimeClock(props.project.total_time); },
    });
};
const closeDeleteModal = () => {
    deleteId = false;
    confirmingDeletion.value = false;
};



</script>
