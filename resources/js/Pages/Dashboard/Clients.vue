<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-wrap justify-between gap-5">

                    <ClientCard v-for="(client, index) of clients" :key="index" :client="client"/>




                    <div
                        class="p-4 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded flex justify-center items-center shadow-2xl">
                        <svg class="cursor-pointer w-20 h-20 opacity-50" @click="newClient"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                        </svg>
                    </div>

                    <Modal :show="creatingNewClient" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                Create new client
                            </h2>
                            <div class="mt-6">

                                <InputLabel for="name" value="name" class="sr-only" />
                                <TextInput id="name" ref="clientNameInput" v-model="form.name" type="text"
                                    class="mt-1 block w-3/4" placeholder="Client Name" @keyup.enter="storeClient" />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="currency" value="currency" class="sr-only" />
                                <select
                                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.currency">
                                    <option value="€">€</option>
                                    <option value="$">$</option>
                                </select>
                                <InputError :message="form.errors.currency" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="hourly_rate" value="hourly_rate" class="sr-only" />
                                <div class="relative w-3/4">
                                    <span class="absolute right-2 top-[9px]">{{ form.currency }} / Hour</span>
                                    <TextInput id="hourly_rate" v-model="form.hourly_rate" type="text"
                                        class="mt-1 block w-3/4" placeholder="Hourly Rate" @keyup.enter="storeClient" />
                                </div>
                                <InputError :message="form.errors.hourly_rate" class="mt-2" />
                            </div>

                            <div class="mt-6">
                                <InputLabel for="color" value="color" class="sr-only" />
                                <ColorPicker id="color" :visible-formats="['hex']" @color-change="updateColor" />
                                <InputError :message="form.errors.color" class="mt-2" />
                            </div>



                            <div class="mt-6 flex justify-end">
                                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                                <SaveButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing" @click="storeClient">
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
import { ColorPicker } from 'vue-accessible-color-picker'
import ClientCard from './Partials/ClientCard.vue';
import { nextTick, ref, defineProps } from 'vue';

let creatingNewClient = ref(false);
const clientNameInput = ref(null);
let color='#ccc';

defineProps({
    clients: {
        type: Array,
    },
});


const form = useForm({
    name: '',
    hourly_rate: '25',
    currency: '$',
});

const newClient = () => {
    console.log('d');
    creatingNewClient.value = true;
    nextTick(() => clientNameInput.value.focus());
};

const storeClient = () => {
    console.log(form);
    form
        .transform((data) => ({
            ...data,
            color,
        })).post(route('client.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => clientNameInput.value.focus(),
            onFinish: () => form.reset(),

        });
};

const closeModal = () => {
    creatingNewClient.value = false;
    form.reset();
};

const updateColor = (eventData) => {
    console.log(eventData.cssColor)
    color = eventData.cssColor;
}

</script>
<style>
@import url('vue-accessible-color-picker/styles');
</style>
