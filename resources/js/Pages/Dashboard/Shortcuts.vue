<template>
    <Head title="Shortcuts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Shortcuts</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <!-- Domain Selector -->
                    <div class="mb-6">
                        <label for="domain-select" class="block text-sm font-medium text-gray-300 mb-2">
                            Select Domain:
                        </label>
                        <select 
                            id="domain-select" 
                            v-model="selectedDomain"
                            class="block w-64 bg-gray-700 border-gray-600 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option v-for="domain in domains" :key="domain.value" :value="domain.value">
                                {{ domain.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Shortcuts Grid -->
                    <div class="flex flex-wrap justify-start gap-5 mb-6">
                        <div 
                            v-for="(shortcut, index) of shortcuts" 
                            :key="shortcut.id"
                            :draggable="true"
                            @dragstart="handleDragStart(index)"
                            @dragover="handleDragOver"
                            @drop="handleDrop(index)"
                            class="relative"
                        >
                            <ShortcutCard 
                                :shortcut="shortcut" 
                                :selectedDomain="selectedDomain"
                                @edit="editShortcut"
                                @delete="deleteShortcut"
                                :index="index" 
                            />
                        </div>

                        <!-- Add New Shortcut Card -->
                        <div
                            class="p-4 text-white bg-blue-500 min-w-[280px] min-h-[160px] w-fit rounded flex justify-center items-center shadow-2xl cursor-pointer hover:bg-blue-600 transition-colors"
                            @click="newShortcut"
                        >
                            <svg class="w-16 h-16 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Create/Edit Modal -->
                    <Modal :show="showModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-200 mb-6">
                                {{ isEditing ? 'Edit Shortcut' : 'Create New Shortcut' }}
                            </h2>
                            
                            <div class="space-y-6">
                                <!-- Name Field -->
                                <div>
                                    <InputLabel for="name" value="Name" class="text-gray-300" />
                                    <TextInput 
                                        id="name" 
                                        ref="nameInput" 
                                        v-model="form.name" 
                                        type="text"
                                        class="mt-1 block w-full" 
                                        placeholder="e.g., Plugins" 
                                        @keyup.enter="saveShortcut" 
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Link Field -->
                                <div>
                                    <InputLabel for="link" value="Link" class="text-gray-300" />
                                    <TextInput 
                                        id="link" 
                                        v-model="form.link" 
                                        type="text"
                                        class="mt-1 block w-full" 
                                        placeholder="e.g., /wp-admin/plugins.php" 
                                        @keyup.enter="saveShortcut" 
                                    />
                                    <InputError :message="form.errors.link" class="mt-2" />
                                </div>

                                <!-- Icon Selection -->
                                <div>
                                    <InputLabel for="icon" value="Icon" class="text-gray-300" />
                                    <div class="mt-2 grid grid-cols-6 gap-3">
                                        <button
                                            v-for="icon in availableIcons"
                                            :key="icon.name"
                                            type="button"
                                            @click="form.icon = icon.name"
                                            :class="[
                                                'p-3 rounded-lg border-2 transition-colors text-gray-300',
                                                form.icon === icon.name 
                                                    ? 'border-blue-500 bg-blue-900' 
                                                    : 'border-gray-600 hover:border-gray-500 bg-gray-700'
                                            ]"
                                        >
                                            <div v-html="icon.svg" class="w-6 h-6"></div>
                                        </button>
                                    </div>
                                    <InputError :message="form.errors.icon" class="mt-2" />
                                </div>

                                <!-- Color Picker -->
                                <div>
                                    <InputLabel for="color" value="Color" class="text-gray-300" />
                                    <ColorPicker 
                                        id="color" 
                                        :visible-formats="['hex']" 
                                        @color-change="updateColor"
                                        :color="form.color"
                                        class="mt-2"
                                    />
                                    <InputError :message="form.errors.color" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-3">
                                <SecondaryButton @click="closeModal">
                                    Cancel
                                </SecondaryButton>
                                <SaveButton 
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing" 
                                    @click="saveShortcut"
                                >
                                    {{ isEditing ? 'Update' : 'Create' }}
                                </SaveButton>
                            </div>
                        </div>
                    </Modal>

                    <!-- Delete Confirmation Modal -->
                    <Modal :show="showDeleteModal" @close="closeDeleteModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-200">
                                Are you sure you want to delete this shortcut?
                            </h2>
                            <p class="mt-1 text-sm text-gray-400">
                                This action cannot be undone.
                            </p>

                            <div class="mt-6 flex justify-end space-x-3">
                                <SecondaryButton @click="closeDeleteModal">
                                    Cancel
                                </SecondaryButton>
                                <DangerButton 
                                    :class="{ 'opacity-25': deleteForm.processing }"
                                    :disabled="deleteForm.processing" 
                                    @click="confirmDelete"
                                >
                                    Delete Shortcut
                                </DangerButton>
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
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { ColorPicker } from 'vue-accessible-color-picker';
import ShortcutCard from './Partials/ShortcutCard.vue';
import { nextTick, ref, computed } from 'vue';

const props = defineProps({
    shortcuts: {
        type: Array,
        default: () => []
    }
});

// State
const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const nameInput = ref(null);
const selectedDomain = ref('https://texasdebrazil.com');
const draggedItem = ref(null);
const shortcutToDelete = ref(null);

// Hardcoded domains as requested
const domains = ref([
    { label: 'TDB main', value: 'https://texasdebrazil.com' },
    { label: 'TDB test', value: 'https://tdbtest.texasdebrazil.com' },
    { label: 'TDB local', value: 'http://tdb-dev.local' },
    { label: 'smart parke', value: 'http://smartparke.com' },
    { label: 'smart parke westlake', value: 'http://westlake.smartparke.com' },
    { label: 'smart parke laguna', value: 'http://laguna.smartparke.com' },
]);

// Available icons (using Font Awesome style SVGs)
const availableIcons = ref([
    {
        name: 'link',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path></svg>'
    },
    {
        name: 'plugin',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path></svg>'
    },
    {
        name: 'dashboard',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>'
    },
    {
        name: 'settings',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>'
    },
    {
        name: 'users',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>'
    },
    {
        name: 'posts',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>'
    },
    {
        name: 'media',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>'
    },
    {
        name: 'themes',
        svg: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zM10.5 2A1.5 1.5 0 009 3.5v11.8a3.001 3.001 0 102 0V3.5A1.5 1.5 0 0010.5 2z" clip-rule="evenodd"></path></svg>'
    }
]);

// Forms
const form = useForm({
    name: '',
    link: '',
    color: '#3B82F6',
    icon: 'link',
});

const deleteForm = useForm({});

// Computed
const shortcutsList = computed(() => props.shortcuts);

// Methods
const newShortcut = () => {
    isEditing.value = false;
    form.reset();
    form.color = '#3B82F6';
    form.icon = 'link';
    showModal.value = true;
    nextTick(() => nameInput.value?.focus());
};

const editShortcut = (shortcut) => {
    isEditing.value = true;
    form.name = shortcut.name;
    form.link = shortcut.link;
    form.color = shortcut.color;
    form.icon = shortcut.icon;
    form.id = shortcut.id;
    showModal.value = true;
    nextTick(() => nameInput.value?.focus());
};

const saveShortcut = () => {
    if (isEditing.value) {
        form.put(route('shortcuts.update', form.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => nameInput.value?.focus(),
        });
    } else {
        form.post(route('shortcuts.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => nameInput.value?.focus(),
        });
    }
};

const deleteShortcut = (shortcut) => {
    shortcutToDelete.value = shortcut;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    deleteForm.delete(route('shortcuts.destroy', shortcutToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
    });
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    shortcutToDelete.value = null;
};

const updateColor = (eventData) => {
    form.color = eventData.cssColor;
};

// Drag and Drop
const handleDragStart = (index) => {
    draggedItem.value = index;
};

const handleDragOver = (event) => {
    event.preventDefault();
};

const handleDrop = (index) => {
    if (draggedItem.value === null) return;
    
    const shortcuts = [...shortcutsList.value];
    const draggedShortcut = shortcuts[draggedItem.value];
    const targetShortcut = shortcuts[index];
    
    // Swap orders
    const draggedOrder = draggedShortcut.order;
    const targetOrder = targetShortcut.order;
    
    const reorderData = {
        shortcuts: [
            { id: draggedShortcut.id, order: targetOrder },
            { id: targetShortcut.id, order: draggedOrder }
        ]
    };
    
    axios.patch(route('shortcuts.order'), reorderData)
        .then(() => {
            // Update local state
            draggedShortcut.order = targetOrder;
            targetShortcut.order = draggedOrder;
        })
        .catch(error => {
            console.error('Error reordering shortcuts:', error);
        });
    
    draggedItem.value = null;
};
</script>

<style>
@import url('vue-accessible-color-picker/styles');
</style>
