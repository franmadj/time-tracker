<template>
    <div>
        <svg
            class="absolute right-14 top-3 w-4 h-4 cursor-pointer"
            @click.stop="editNotesModal"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
        >
            <path
                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
            />
        </svg>

        <svg
            @click.stop="editModal"
            class="absolute right-8 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
        >
            <path
                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"
            />
        </svg>
        <svg
            @click.stop="deleteModal"
            class="absolute right-3 top-3 w-4 h-4 cursor-pointer fill-white"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"
        >
            <path
                d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"
            />
        </svg>

        <div
            class="w-20 h-20 border border-black absolute -left-10 -top-10 rotate-45"
            :style="'background:' + client.color"
        ></div>
        <h2 class="text-white font-bold mb-2">{{ client.name }}</h2>
        <p v-show="client.period_from" class="mb-1">
            Prdiod from: {{ client.period_from }}
        </p>
        <p v-show="client.active_projects" class="mb-1">
            Active Projects: {{ client.active_projects }}
        </p>
        <p v-show="client.total_time" class="mb-1">
            Total time: {{ helpers.makeTimeClock(client.total_time) }}
        </p>
        <p v-show="client.earnings" class="mb-1">
            Earnings: {{ client.currency }}{{ client.earnings }}
        </p>
        <p class="mb-1">
            Prioroty:
            <span
                @click.stop="setPrioroty('high')"
                class="cursor-pointer"
                :class="{ 'text-red-500': 'high' == props.client.prioroty }"
                >High</span
            >
            /
            <span
                @click.stop="setPrioroty('middle')"
                class="cursor-pointer"
                :class="{
                    'text-yellow-500': 'middle' == props.client.prioroty,
                }"
                >Middle</span
            >
            /
            <span
                @click.stop="setPrioroty('low')"
                class="cursor-pointer"
                :class="{ 'text-green-500': 'low' == props.client.prioroty }"
                >Low</span
            >
        </p>
    </div>
    <Modal :show="confirmingEdition" @close="closeEditModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Edit client</h2>
            <div class="mt-6">
                <InputLabel for="name" value="name" class="sr-only" />
                <TextInput
                    id="name"
                    ref="editNameInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Client Name"
                    @keyup.enter="updateClient"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel for="currency" value="currency" class="sr-only" />
                <select
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    v-model="form.currency"
                >
                    <option value="€">€</option>
                    <option value="$">$</option>
                </select>
                <InputError :message="form.errors.currency" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel
                    for="hourly_rate"
                    value="hourly_rate"
                    class="sr-only"
                />
                <div class="relative w-3/4">
                    <span class="absolute right-2 top-[9px]"
                        >{{ form.currency }} / Hour</span
                    >
                    <TextInput
                        id="hourly_rate"
                        v-model="form.hourly_rate"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Hourly Rate"
                        @keyup.enter="updateClient"
                    />
                </div>
                <InputError :message="form.errors.hourly_rate" class="mt-2" />
            </div>
            <div class="mt-6">
                <InputLabel
                    for="hourly_rate_two"
                    value="hourly rate two"
                    class="sr-only"
                />
                <div class="relative w-3/4">
                    <span class="absolute right-2 top-[9px]"
                        >{{ form.currency }} / Hour</span
                    >
                    <TextInput
                        id="hourly_rate_two"
                        v-model="form.hourly_rate_two"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Second Hourly Rate"
                        @keyup.enter="updateClient"
                    />
                </div>
                <InputError
                    :message="form.errors.hourly_rate_two"
                    class="mt-2"
                />
            </div>

            <div class="mt-6">
                <InputLabel for="color" value="color" class="sr-only" />
                <ColorPicker
                    id="color"
                    :visible-formats="['hex']"
                    @color-change="updateColor"
                    :color="color"
                />
                <InputError :message="form.errors.color" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeEditModal">
                    Cancel
                </SecondaryButton>
                <SaveButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="updateClient"
                >
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

                <TextInput
                    id="name"
                    ref="deleteNameInput"
                    v-model="formDelete.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="Client Name"
                    @keyup.enter="deleteClient"
                />

                <InputError :message="formDelete.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeDeleteModal">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': formDelete.processing }"
                    :disabled="formDelete.processing"
                    @click="deleteClient"
                >
                    Delete Client
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingNotes" @close="closeNotes">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Notes</h2>

            <div class="mt-6" >
                <div ref="editorRef"></div>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeNotesModal">
                    Cancel
                </SecondaryButton>

                <SaveButton
                    class="ms-3"
                    :class="{ 'opacity-25': formNotes.processing }"
                    :disabled="formNotes.processing"
                    @click="saveNotes"
                >
                    Save
                </SaveButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SaveButton from "@/Components/SaveButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import Quill from "quill";
import { ColorPicker } from "vue-accessible-color-picker";
import helpers from "../../../Composables/helpers";
import { nextTick, ref, defineProps, onMounted } from "vue";
import 'quill/dist/quill.snow.css'; // Import Quill's styles

const color = ref("#ccc");
const editorRef = ref(null);


const props = defineProps({
    client: {
        type: Object,
    },
});

let form;
let formNotes;

const formDelete = useForm({
    name: "",
});

onMounted(() => {
    console.log("onmounted");
});

/************EDIT MODAL***********/
/*******************************/
/*******************************/
/*******************************/
const editNameInput = ref(null);
const confirmingEdition = ref(false);
const editModal = () => {
    form = useForm({
        name: props.client.name,
        hourly_rate: String(props.client.hourly_rate),
        hourly_rate_two: String(props.client.hourly_rate_two),
        currency: props.client.currency,
    });
    color.value = props.client.color;
    confirmingEdition.value = true;
    nextTick(() => editNameInput.value.focus());
};
const updateClient = () => {
    form.transform((data) => ({
        ...data,
        color: color.value,
    })).put(route("client.update", props.client.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal();
        },
        onError: () => editNameInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
const closeEditModal = () => {
    confirmingEdition.value = false;
};

/*********DELETE MODAL**********/
/*******************************/
/*******************************/
/*******************************/
const deleteNameInput = ref(null);
const confirmingDeletion = ref(false);
const deleteModal = () => {
    confirmingDeletion.value = true;
    nextTick(() => deleteNameInput.value.focus());
};
const deleteClient = () => {
    console.log(props.client.name, formDelete.name);
    if (props.client.name != formDelete.name) {
        formDelete.setError("name", "Name is incorrect.");
        return;
    }
    formDelete.clearErrors();

    formDelete.delete(route("client.destroy", props.client.id), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
        onError: () => deleteNameInput.value.focus(),
        onFinish: () => formDelete.reset(),
    });
};
const closeDeleteModal = () => {
    confirmingDeletion.value = false;
    formDelete.reset();
};

/*********NOTES MODAL***********/
/*******************************/
/*******************************/
/*******************************/
const editNameInput_ = ref(null);
const confirmingNotes = ref(false);
let quillInstance = null; // To store the Quill instance
const editorContent = ref('hello'); // To store and manage the content
const editNotesModal = () => {

    formNotes = useForm({
        notes: editorContent.value
    });
    
    confirmingNotes.value = true;
    
    nextTick(() => {
        if (editorRef.value) {
            quillInstance = new Quill(editorRef.value, {
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic"],
                        [{ list: "ordered" }, { list: "bullet" }],
                    ],
                },
            });
            // Prepopulate the editor with a value
            quillInstance.clipboard.dangerouslyPasteHTML(editorContent.value);
        } else {
            console.error("editorRef is null");
        }
    });
    //nextTick(() => editNameInput.value.focus());
};
// Function to capture the editor's value
const saveNotes = () => {
  if (quillInstance) {
    formNotes.transform((data) => ({
        ...data,
        name:quillInstance.root.innerHTML
    })).patch(route("client.updateNotes", props.client.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeNotes();
        },
        onError: () => {},
        onFinish: () => {},
    });
    //editorContent.value = quillInstance.root.innerHTML; // Capture the current editor content
    console.log('Editor Content:', editorContent.value);
  }
};
const updateClient_ = () => {
    form.transform((data) => ({
        ...data,
        color: color.value,
    })).put(route("client.update", props.client.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal();
        },
        onError: () => editNameInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
const closeNotesModal = () => {
    confirmingNotes.value = false;
};

const setPrioroty = (newPrioroty) => {
    axios
        .patch(route("client.prioroty", props.client.id), {
            prioroty: newPrioroty,
        })
        .then((res) => {
            if (res.data.success) {
                props.client.prioroty = newPrioroty;
            } else {
                //toaster.error(`Error`);
            }
        })
        .catch(function (error) {
            //toaster.error(error);
        });
};

const updateColor = (eventData) => {
    console.log(eventData.cssColor);
    color.value = eventData.cssColor;
};
</script>
<style>
@import url("vue-accessible-color-picker/styles");
</style>
