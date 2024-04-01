<template>

    <div
        class="p-4 pl-9 pt-9 hover:opacity-90 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded relative overflow-hidden shadow-xl border border-slate-400 cursor-pointer">

        <svg @click="editModal" class="absolute right-8 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z" />
        </svg>
        <svg @click="deleteModal" class="absolute right-3 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path
                d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z" />
        </svg>

        <h2 class="text-white font-bold mb-2">{{ project.name }}</h2>
        <p v-show="project.period_from" class="mb-1">Prdiod from: {{ project.period_from }}</p>
        <p v-show="project.active_projects" class="mb-1">Active Projects: {{ project.active_projects }}</p>
        <p v-show="project.total_time" class="mb-1">Total time: 10:25</p>
        <p v-show="project.earnings" class="mb-1">Earnings: {{ project.currency }}{{ project.earnings }}</p>
        <p class="mb-1">Prioroty:
            <span @click="setPrioroty('high')" class="cursor-pointer"
                :class="{ 'text-red-500': 'high' == prioroty }">High</span> /
            <span @click="setPrioroty('middle')" class="cursor-pointer"
                :class="{ 'text-yellow-500': 'middle' == prioroty }">Middle</span> /
            <span @click="setPrioroty('low')" class="cursor-pointer"
                :class="{ 'text-green-500': 'low' == prioroty }">Low</span>
        </p>
    </div>
    <Modal :show="confirmingEdition" @close="closeEditModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Project
            </h2>
            <div class="mt-6">
                <InputLabel for="name" value="name" class="sr-only" />
                <TextInput id="name" ref="projectNameInput" v-model="form.name" type="text" class="mt-1 block w-3/4"
                    placeholder="Project Name" @keyup.enter="storeProject" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6">
                <InputLabel for="notes" value="notes" class="sr-only" />
                <textarea id="notes" ref="projectNameInput" v-model="form.notes" type="text"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Project Name" @keyup.enter="storeProject"></textarea>

                <InputError :message="form.errors.notes" class="mt-2" />
            </div>

            <div class="mt-6">
                <InputLabel for="hourly_rate" value="hourly_rate" class="sr-only" />
                <div class="relative w-3/4">
                    <span class="absolute right-2 top-[9px]">{{ props.client.currency }} / Hour</span>
                    <TextInput id="hourly_rate" v-model="form.hourly_rate" type="text" class="mt-1 block w-3/4"
                        placeholder="Hourly Rate" @keyup.enter="storeProject" />
                </div>
                <InputError :message="form.errors.hourly_rate" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                <SaveButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="storeProject">
                    Save
                </SaveButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingDeletion" @close="closeDeleteModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this client?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                This client and all its projects will be deleted.
            </p>

            <div class="mt-6">
                <InputLabel for="name" value="name" class="sr-only" />

                <TextInput id="name" ref="deleteNameInput" v-model="formDelete.name" type="text"
                    class="mt-1 block w-3/4" placeholder="Client Name" @keyup.enter="deleteClient" />

                <InputError :message="formDelete.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteClient">
                    Delete Client
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
import { ColorPicker } from 'vue-accessible-color-picker'
import { nextTick, ref, defineProps, onMounted } from 'vue';



const editNameInput = ref(null);
const deleteNameInput = ref(null);
let color = ref('#ccc');
let prioroty = ref('low');

const confirmingDeletion = ref(false);
const confirmingEdition = ref(false);

const props = defineProps({
    client: {
        type: Object,
    },
});

let form;

const formDelete = useForm({
    name: ''
});

onMounted(() => {
    console.log('onmounted');
    prioroty.value = props.client.prioroty;
    form = useForm({
        name: props.client.name,
        hourly_rate: String(props.client.hourly_rate),
        currency: props.client.currency
    });
    color.value = props.client.color;
})

const viewClient = () => {
    router.get(route('client.show', props.client.id))
}

const editModal = () => {
    confirmingEdition.value = true;
    nextTick(() => editNameInput.value.focus());
}

const deleteModal = () => {
    confirmingDeletion.value = true;
    nextTick(() => deleteNameInput.value.focus());
};

const deleteClient = () => {
    if (props.client.name != formDelete.name) {
        formDelete.setError('name', 'Name is incorrect.');
        return;
    }
    formDelete.clearErrors()

    form.delete(route('client.destroy', props.client.id), {
        preserveScroll: true,
        onSuccess: () => closeEditModal(),
        onError: () => deleteNameInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const updateClient = () => {
    form
        .transform((data) => ({
            ...data,
            color: color.value,
        })).put(route('client.update', props.client.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeEditModal()
            },
            onError: () => editNameInput.value.focus(),
            onFinish: () => form.reset(),

        });
};

const closeDeleteModal = () => {
    confirmingDeletion.value = false;
    form.reset();
};
const closeEditModal = () => {
    confirmingEdition.value = false;
};





const setPrioroty = (newPrioroty) => {
    console.log(route('client.prioroty', props.client.id));
    axios.patch(route('client.prioroty', props.client.id), { "prioroty": newPrioroty })
        .then(res => {
            if (res.data.success) {
                prioroty.value = newPrioroty;

            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
};



const updateColor = (eventData) => {
    console.log(eventData.cssColor)
    color.value = eventData.cssColor;
}

</script>
<style>
@import url('vue-accessible-color-picker/styles');
</style>
