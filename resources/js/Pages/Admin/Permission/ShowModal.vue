<script setup>
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    show: Boolean,
    permission: Object,
})

const emit = defineEmits(['update:show'])

const closeModal = () => {
    emit('update:show', false)
}
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="lg">

        <div class="bg-white dark:bg-gray-800">

            <!-- Header -->
            <div class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                        ⚙️
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-300">
                        Permission {{ permission?.name }}
                    </h3>
                </div>

                <!-- Roles -->
                <div class="mt-5">
                    <div class="text-indigo-500 font-semibold mb-2">
                        Roles Attached with this permission
                    </div>

                    <div v-if="permission?.roles?.length">
                        <span
                            v-for="role in permission.roles"
                            :key="role.id"
                            class="px-2 py-1 mr-1 my-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                            {{ role.name }}
                        </span>
                    </div>

                    <em v-else>none</em>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end">
                <button
                    @click="closeModal"
                    class="rounded-md border px-4 py-2 text-sm bg-white dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-50"
                >
                    Close
                </button>
            </div>

        </div>

    </Modal>
</template>