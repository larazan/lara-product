<!-- resources/js/Components/ReportModal.vue -->
<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    show: Boolean,
    reportableId: Number,
    reportableType: String,
})

const emit = defineEmits(['close'])

const form = useForm({
    reportable_id: props.reportableId,
    reportable_type: props.reportableType,
    reason: '',
    message: '',
})

// Sinkronkan data form setiap kali modal dibuka kembali
watch(() => props.show, (isShown) => {
    if (isShown) {
        form.clearErrors()
        form.reset()
        form.reportable_id = props.reportableId
        form.reportable_type = props.reportableType
    }
})

const reasons = [
    'Spam',
    'Harassment',
    'Inappropriate Content',
    'Incorrect Information',
    'Other'
]

function validate() {
    form.clearErrors()
    let valid = true
    if (!form.reason) {
        form.setError('reason', 'Please select a reason')
        valid = false
    }
    if (form.message && form.message.length > 500) {
        form.setError('message', 'Message must be less than 500 characters')
        valid = false
    }
    return valid
}

function submit() {
    if (!validate()) return

    form.post('/reports', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            emit('close')

            // SweetAlert Notifikasi Berhasil
            Swal.fire({
                icon: 'success',
                title: 'Report Submitted',
                text: 'Thank you. Your report has been submitted successfully.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            })
        }
    })
}
</script>

<template>
    <Modal :show="props.show" @close="emit('close')" maxWidth="md">
        <div class="p-6 space-y-4">
            <h2 class="text-lg font-semibold dark:text-white">Report Content</h2>

            <!-- Reason -->
            <div>
                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200">Reason</label>

                <select v-model="form.reason" id="type"
                    class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option disabled value="">Select type</option>
                    <option v-for="r in reasons" :key="r" :value="r">{{ r }}</option>
                </select>
                <div v-if="form.errors.reason" class="text-red-600 text-sm mt-1">
                    {{ form.errors.reason }}
                </div>
            </div>

            <!-- Message -->
            <div>
                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200">Additional Message
                    (Optional)</label>
                <textarea v-model="form.message"
                    class="mt-1 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    rows="4"></textarea>
                <div class="flex justify-between text-xs mt-1">
                    <div v-if="form.errors.message" class="text-red-600">
                        {{ form.errors.message }}
                    </div>

                    <div class="text-gray-400 ml-auto">
                        {{ form.message ? form.message.length : 0 }}/500
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <button type="button" @click="emit('close')"
                    class="justify-center text-gray-500 items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancel
                </button>
                <button type="button" @click="submit" :disabled="form.processing"
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg disabled:opacity-50">
                    {{ form.processing ? 'Submitting...' : 'Submit Report' }}
                </button>
            </div>
        </div>
    </Modal>
</template>