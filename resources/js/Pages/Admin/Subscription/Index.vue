<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
// import ToggleSwitch from '../Components/ToggleSwitch.vue';
import ToggleSwitch from '@/Pages/User/Components/ToggleSwitch.vue';
import { ref, reactive, watch, computed } from 'vue';
import { router, Head, useForm } from '@inertiajs/vue3';
import ConfirmModal from '../Components/ConfirmModal.vue';
import PaginationMod from '../Components/PaginationMod.vue';
import ImageUploadMod from '../Components/ImageUploadMod.vue';
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { formatUS } from '@/Utils/text';
import { limitChars } from '@/Utils/text';
import CancelSubscriptionModal from '../Components/CancelSubscriptionModal.vue';

const props = defineProps({
    page: Number,
    subscriptions: Object,
    users: Object,
    orders: Object,
    plans: Object,
    search: Object
});

const resetForm = () => {
    formSubscription.reset()
    formSubscription.clearErrors()
}

const formSubscription = useForm({
    userId: null,
    planId: null,
    orderId: null,
    startDate: null,
    endDate: null,
    // status: null,
});

const idSubscription = ref('');
const idDeleteSubscription = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;
}

// open edit modal
const openEditModal = (subscription) => {
    showEditModal.value = true;
    idSubscription.value = subscription.id;

    formSubscription.userId = subscription.user_id;
    formSubscription.planId = subscription.plan_id;
    formSubscription.orderId = subscription.order_id;
    formSubscription.startDate = subscription.start_date;
    formSubscription.endDate = subscription.end_date;
    formSubscription.status = subscription.status
}

// update method
const updateSubscription = () => {
    formSubscription
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/subscription/update/' + idSubscription.value, {
            forceFormData: true,
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    position: 'top-end',
                    title: page.props.flash.success,
                })
                closeModal()
                resetForm()
            },
        })
}



// open add modal
const openAddModal = () => {
    resetForm();

    showCreateModal.value = true;
}

// add method
const addSubscription = () => {
    formSubscription.post('/admin/subscription/create', {
        forceFormData: true,
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })
            closeModal()
            resetForm()
        },
    })
}


// open delete modal
const openDeleteModal = (id) => {
    showDeleteModal.value = true;
    idDeleteSubscription.value = id;
}

// delete method
const deleteSubscription = async () => {
    try {
        await router.delete('/admin/subscription/delete/' + idDeleteSubscription.value, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                closeModal();
            },
        });
    } catch (error) {
        console.log(error);

    }
}

// search
const search = ref(props.search ?? '');

watch(search, (value) => {
    router.get('/admin/subscriptions', { q: value }, { preserveState: true, replace: true })
});


// delete selected
const selected = ref([])

// Select all logic
const allSelected = computed(() => {
    if (!props.subscriptions?.data) return false

    return (
        props.subscriptions.data.length > 0 &&
        selected.value.length === props.subscriptions.data.length
    )
})


const toggleAll = (e) => {
    if (!props.subscriptions?.data) return

    selected.value = e.target.checked
        ? props.subscriptions.data.map(r => r.id)
        : []
}

// Delete selected
const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.subscription.bulk-delete'), {
        data: { ids: selected.value },
        preserveScroll: true,
        onSuccess: () => {
            selected.value = []
        },
    })
}

const showCancelModal = ref(false);
const selectedSubscription = ref(null);

const openCancelModal = (subscription) => {
    selectedSubscription.value = subscription;
    showCancelModal.value = true;
};

</script>

<template>

    <AdminLayout>

        <Head>
            <title>Subscriptions</title>
        </Head>
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <!-- <pre>{{ props.subscriptions }}</pre> -->
            <div class="w-full mb-1">
                <div class="mb-4">
                    <!-- breadcrumb -->

                    <!-- end breadcrumb -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All subscriptions</h1>
                </div>
                <div class="sm:flex">
                    <div
                        class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <div class="lg:pr-3">
                            <label for="subscriptions-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input v-model="search" type="text" name="email" id="subscriptions-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search for subscriptions">
                            </div>
                        </div>
                        <div class="flex pl-0 mt-3 space-x-1 sm:pl-2 sm:mt-0">

                            <button @click="deleteSelected" :disabled="!selected.length"
                                class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>


                        </div>
                    </div>
                    <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                        <button @click="openAddModal" type="button"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Add subscription
                        </button>
                        <a href="#"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input :checked="allSelected" @change="toggleAll" id="checkbox-all"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Plan
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Payment Method
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Order
                                        <!-- invoice_number
                                        midtrans_order_id 
                                        -->
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Duration
                                        <!-- start end -->
                                    </th>

                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Payment
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Paid At
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Verified At
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                <tr v-for="(subscription, index) in props.subscriptions.data" :key="subscription.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${subscription.id}`" :value="subscription.id"
                                                v-model="selected" type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${subscription.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.subscriptions.current_page - 1) *
                                                    props.subscriptions.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">
                                        <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{
                                                subscription.user.first_name }} {{ subscription.user.last_name }}</div>
                                            <div class="text-sm text-gray-700 font-medium">@{{
                                                subscription.user.username }}</div>
                                            <div class="text-sm font-normal text-gray-500 dark:text-gray-400">{{
                                                subscription.user.email }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ subscription.plan.name }}</td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ subscription.order.payment_method }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <div>{{ subscription.order.invoice_number }}</div>
                                        <div>{{ subscription.order.midtrans_order_id }}</div>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm text-center font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <div class="text-base">{{ subscription.plan.duration_days }} days</div>
                                        <div>{{ formatUS(subscription.start_date) }} - {{
                                            formatUS(subscription.end_date) }}</div>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>Rp. {{ subscription.order.amount }}</div>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-600 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ formatUS(subscription.order.paid_at) }}</td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ formatUS(subscription.order.verified_at) }}</td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <span class="px-2 py-1 rounded-lg text-xs text-white" :class="{
                                            'bg-green-600': subscription.status === 'active',
                                            'bg-red-600': subscription.status === 'inactive',
                                        }">{{ subscription.status }}</span>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-600 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ formatUS(subscription.created_at) }}</td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="openCancelModal(subscription)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                                            Hentikan
                                        </button>
                                        <button @click="openEditModal(subscription)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button @click="openDeleteModal(subscription.id)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <PaginationMod :meta="props.subscriptions" :query="{ search: search }" :limit="props.page" />

        <!-- Edit Subscription Modal -->
        <Modal :show="showEditModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Edit subscription
                        </h3>
                        <button @click="closeModal()" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" view-box="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form @submit.prevent="updateSubscription">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="plan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Plan
                                    </label>
                                    <select v-model="formSubscription.planId" id="plan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select plan</option>
                                        <option v-for="plan in props.plans" :key="plan.id" :value="plan.id">{{ plan.name
                                            }}</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="user"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        User
                                    </label>
                                    <select v-model="formSubscription.userId" id="user"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select user</option>
                                        <option v-for="user in props.users" :key="user.id" :value="user.id">{{
                                            user.first_name }} {{ user.last_name }}</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="start"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Start date
                                    </label>
                                    <VueDatePicker v-model="formSubscription.startDate"
                                        :formats="{ preview: 'yyyy-MM-dd', input: 'yyyy-MM-dd' }"
                                        model-type="yyyy-MM-dd" placeholder="Pick a date" />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="end"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        End date
                                    </label>
                                    <VueDatePicker v-model="formSubscription.endDate"
                                        :formats="{ preview: 'yyyy-MM-dd', input: 'yyyy-MM-dd' }"
                                        model-type="yyyy-MM-dd" placeholder="Pick a date" />
                                </div>


                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex gap-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                            <button
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="submit">Update</button>
                            <button @click="closeModal"
                                class=" justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                                type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>


        <!-- Add Subscription Modal -->
        <Modal :show="showCreateModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Add new subscription
                        </h3>
                        <button @click="closeModal" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" view-box="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="#" @submit.prevent="addSubscription">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="plan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Plan
                                    </label>
                                    <select v-model="formSubscription.planId" id="plan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select plan</option>
                                        <option v-for="plan in props.plans" :key="plan.id" :value="plan.id">{{ plan.name
                                            }}</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="user"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        User
                                    </label>
                                    <select v-model="formSubscription.userId" id="user"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select user</option>
                                        <option v-for="user in props.users" :key="user.id" :value="user.id">{{
                                            user.first_name }} {{ user.last_name }}</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="start"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Start date
                                    </label>
                                    <VueDatePicker v-model="formSubscription.startDate"
                                        :formats="{ preview: 'yyyy-MM-dd', input: 'yyyy-MM-dd' }"
                                        model-type="yyyy-MM-dd" placeholder="Pick a date" />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="end"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        End date
                                    </label>
                                    <VueDatePicker v-model="formSubscription.endDate"
                                        :formats="{ preview: 'yyyy-MM-dd', input: 'yyyy-MM-dd' }"
                                        model-type="yyyy-MM-dd" placeholder="Pick a date" />
                                </div>


                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex gap-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                            <button
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="submit">Submit</button>
                            <button @click="closeModal"
                                class=" justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                                type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>

        <!-- Delete Subscription Modal -->
        <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this subscription?"
            @confirm="deleteSubscription" @close="closeModal" />

        <!-- Component Modal -->
        <CancelSubscriptionModal :show="showCancelModal" :subscription="selectedSubscription"
            @close="showCancelModal = false" />

    </AdminLayout>
</template>