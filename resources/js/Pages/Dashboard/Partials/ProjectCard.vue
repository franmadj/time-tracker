<template>

    <div>

        <svg @click.stop="editModal" class="absolute right-8 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z" />
        </svg>
        <svg @click.stop="deleteModal" class="absolute right-3 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path
                d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z" />
        </svg>
        <h2 class="text-white font-bold mb-2 max-w-[288px]">{{ project.name }}</h2>
        <p class="mb-1">Prioroty:
            <span @click.stop="setPrioroty('high')" class="cursor-pointer"
                :class="{ 'text-red-500': 'high' == props.project.prioroty }">High</span> /
            <span @click.stop="setPrioroty('middle')" class="cursor-pointer"
                :class="{ 'text-yellow-500': 'middle' == props.project.prioroty }">Middle</span> /
            <span @click.stop="setPrioroty('low')" class="cursor-pointer"
                :class="{ 'text-green-500': 'low' == props.project.prioroty }">Low</span>
        </p>

        <div class="flex">
            <div class="f w-[224px]">
                <p v-show="project.period_from" class="mb-1">Prdiod from: {{ project.period_from }}</p>
                <p v-show="project.ended_at" class="mb-1">Ended At: {{ project.ended_at }}</p>
                <p v-show="totalTimeDisplay" class="mb-1">Total time: {{ totalTimeDisplay }}</p>
                <p v-show="project.earnings" class="mb-1 underline cursor-pointer">Time Table</p>
            </div>
            <div class="w-[102px]">
                <div class="flex items-center gap-2 justify-end mb-2">
                    <InputLabel :for="'selected-' + project.id" value="selected"
                        class="text-white text-xl font-light leading-5" @click.stop />
                    <input type="checkbox" :id="'selected-' + project.id" v-model="project.selected" @click.stop>
                </div>
                <div class="flex items-center gap-2 justify-end mb-2">
                    <InputLabel for="ended" value="ended" class="text-white text-xl font-light leading-5" @click.stop />
                    <input type="checkbox" id="ended" v-model="ended" @click.stop>
                </div>

                <p v-show="timing" class="mb-1 text-right">{{ timing }}</p>

            </div>
        </div>

        <svg v-if="played" @click.stop="stopTime"
            class="absolute right-3 bottom-3 w-8 h-8 cursor-pointer fill-red-400 hover:fill-red-500"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <path
                d="M0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
        </svg>
        <svg v-else @click.stop="playTime"
            class="absolute right-3 bottom-3 w-8 h-8 cursor-pointer fill-green-400 hover:fill-green-500"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <path
                d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" />
        </svg>



    </div>

    <Modal :show="confirmingEdition" @close="closeEditModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Project
            </h2>
            <div class="mt-6">
                <InputLabel for="name" value="name" class="sr-only" />
                <TextInput id="name" ref="projectNameInput" v-model="form.name" type="text" class="mt-1 block w-3/4"
                    placeholder="Project Name" @keyup.enter="updateProject" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel for="notes" value="notes" class="sr-only" />
                <textarea id="notes" ref="editNameInput" v-model="form.notes" type="text"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Project Name" @keyup.enter="updateProject"></textarea>

                <InputError :message="form.errors.notes" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel for="hourly_rate" value="hourly_rate" class="sr-only" />
                <div class="relative w-3/4">
                    <span class="absolute right-2 top-[9px]">{{ props.client.currency }} / Hour</span>
                    <TextInput id="hourly_rate" v-model="form.hourly_rate" type="text" class="mt-1 block w-3/4"
                        placeholder="Hourly Rate" @keyup.enter="updateProject" />
                </div>
                <InputError :message="form.errors.hourly_rate" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel for="extra_time" value="extra_time" class="sr-only" />
                <div class="flex gap-2 items-center w-3/4">

                    <TextInput id="extra_time" v-model="form.extra_time" type="text" class="mt-1 block w-32"
                        placeholder="Extra Minutes" @keyup.enter="updateProject" />
                    <span class="">Minutes / <b>{{ extraHours }}</b> Hours</span>
                </div>
                <InputError :message="form.errors.extra_time" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeEditModal"> Cancel </SecondaryButton>
                <SaveButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="updateProject">
                    Save
                </SaveButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingDeletion" @close="closeDeleteModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this proyect?
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                This proyect and all its time history will be deleted.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>
                <DangerButton class="ms-3" :class="{ 'opacity-25': formDelete.processing }"
                    :disabled="formDelete.processing" @click="deleteProject">
                    Delete Project
                </DangerButton>
            </div>
        </div>
    </Modal>


</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SaveButton from '@/Components/SaveButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { nextTick, ref, defineProps, onMounted, computed } from 'vue';


const props = defineProps({
    project: {
        type: Object,
    },
    client: {
        type: Object,
    },
});

const played = ref(false);
const ended = ref(false);
const timing = ref('00:00:00');
let runTimeInterval = 1;
let currentTimeTableId = 0;
const totalTime = ref(false);

const emit = defineEmits(['delete:project']) // must emits



onMounted(() => {
    console.log('project', props.project);
    setData();
});

const extraHours = computed(() => {
    return form.extra_time > 0 ? (form.extra_time / 60).toFixed(2) : 0;
})

const totalTimeDisplay = computed(() => {
    return makeTimeClock(totalTime.value);
})

const setData = () => {
    if (props.project.time_started) {
        currentTimeTableId = props.project.time_id;
        const startedTime = new Date(props.project.time_started);
        startClock(startedTime);
        played.value = true;
    }
    if (props.project.total_time) {
        totalTime.value = props.project.total_time;
    }

}

const playTime = () => {
    timing.value = '00:00:00';
    played.value = true;
    const startedTime = new Date();
    axios.post(route('project.startTime', props.project.id), { startedAt: startedTime.toUTCString() })
        .then(res => {
            if (res.data.success && res.data.id > 0) {
                currentTimeTableId = res.data.id;
                startClock(startedTime);
            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
}

const startClock = (startedTime) => {
    runTimeInterval = setInterval(() => {
        const now = new Date();
        const diff = now.getTime() - startedTime.getTime();
        const totalSeconds = Math.round(diff / (1000));
        timing.value = makeTimeClock(totalSeconds);
    }, 1000);

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

const stopTime = () => {
    played.value = false;
    clearInterval(runTimeInterval);
    const stoppedTime = new Date();
    axios.post(route('timeTable.stopTime', currentTimeTableId), { stoppedAt: stoppedTime.toUTCString() })
        .then(res => {
            console.log(res);
            if (res.data.success) {
                console.log(res.data.total_time);
                totalTime.value = res.data.total_time;
            }

        })
        .catch(function (error) {
            //toaster.error(error);
        });

}

const viewProject = () => {
    router.get(route('project.show', props.project.id))
}


/*********DELETE MODAL**********/
/*******************************/
/*******************************/
/*******************************/
const confirmingDeletion = ref(false);
const formDelete = useForm({
    name: ''
});
const deleteModal = () => {
    confirmingDeletion.value = true;
};
const deleteProject = () => {
    formDelete.delete(route('project.destroy', props.project.id), {
        preserveScroll: true,
        onSuccess: () => { emit('delete:project'); closeDeleteModal() },
        onError: () => deleteNameInput.value.focus()
    });
};
const closeDeleteModal = () => {
    confirmingDeletion.value = false;
};


/************EDIT MODAL***********/
/*******************************/
/*******************************/
/*******************************/
const editNameInput = ref(null);
const confirmingEdition = ref(false);
let form;
const editModal = () => {
    form = useForm({
        name: props.project.name,
        hourly_rate: String(props.project.hourly_rate),
        notes: props.project.notes,
        extra_time: props.project.extra_time,
        client_slug: props.client.slug
    });
    confirmingEdition.value = true;
    nextTick(() => editNameInput.value.focus());
}
const updateProject = () => {
    form
        .put(route('project.update', props.project.id), {
            preserveScroll: true,
            onSuccess: () => {
                setData();
            },
            onError: () => editNameInput.value.focus(),
            onFinish: () => form.reset(),

        });
};
const closeEditModal = () => {
    confirmingEdition.value = false;
    form.reset();
};







const setPrioroty = (newPrioroty) => {
    console.log(route('project.prioroty', props.project.id));
    axios.patch(route('project.prioroty', props.project.id), { "prioroty": newPrioroty })
        .then(res => {
            if (res.data.success) {
                props.project.prioroty = newPrioroty;

            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
};


</script>
