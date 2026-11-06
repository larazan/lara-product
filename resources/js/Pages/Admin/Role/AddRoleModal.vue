<script setup>
import { reactive } from 'vue'
import Modal from '@/Components/Modal.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    allPermissions: Array
})

const emit = defineEmits(['update:show'])

const form = reactive({
    name: '',
    permissions: []
})

const closeModal = () => {
    emit('update:show', false)

    // reset form
    form.name = ''
    form.permissions = []
}

const save = () => {
    router.post('/roles', form, {
        onSuccess: () => {
            closeModal()
        }
    })
}
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="lg">

        <div class="bg-white dark:bg-gray-800">

            <!-- Header -->
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold dark:text-gray-300">
                    Add New Role
                </h3>

                <!-- Role Name -->
                <div class="mt-4">
                    <label class="text-xs font-bold">Role Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Enter role name"
                        class="w-full mt-1 rounded border p-2 dark:bg-gray-700"
                    />
                </div>

                <!-- Permissions -->
                <div class="mt-5">
                    <label class="text-xs font-bold">Attach Permissions</label>

                    <div class="max-h-60 overflow-y-auto mt-2 border rounded p-2">
                        <label
                            v-for="permission in allPermissions"
                            :key="permission.id"
                            class="flex items-center gap-2 py-1"
                        >
                            <input
                                type="checkbox"
                                :value="permission.name"
                                v-model="form.permissions"
                            />
                            {{ permission.name }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end gap-2">
                <button
                    @click="closeModal"
                    class="border px-4 py-2 rounded"
                >
                    Cancel
                </button>

                <button
                    @click="save"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                >
                    Save
                </button>
            </div>

        </div>

    </Modal>
</template>