<script setup>
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    show: Boolean,
    role: Object,
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
                        🛡️
                    </div>
                    <!-- <pre>{{ props.role }}</pre> -->
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-300">
                        Role {{ role?.name }}
                    </h3>
                </div>

                <!-- Permissions -->
                <div class="mt-5 max-h-[300px] overflow-y-auto">
                    <div class="text-indigo-500 font-semibold mb-2">
                        Permissions Attached with this role
                    </div>

                    <div v-if="role?.permissions?.length" class="flex flex-wrap">
                        <span v-for="(permission, id) in role.permissions" :key="id"
                            class="px-2 py-1 mr-1 my-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                            {{ permission }}
                        </span>
                    </div>

                    <em v-else>none</em>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end">
                <button @click="closeModal"
                    class="rounded-md border px-4 py-2 text-sm bg-white dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-50">
                    Close
                </button>
            </div>

        </div>

    </Modal>
</template>