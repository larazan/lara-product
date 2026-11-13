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
import SearchDropdown from '../Components/SearchDropdown.vue';
import VerificationModal from '../Components/VerificationModal.vue';

const props = defineProps({
    page: Number,
    orders: Object,
    users: Object,
    plans: Object,
    search: Object
});

const showModal = ref(false);
const selectedOrder = ref(null)

const resetForm = () => {
    formOrder.invoice_number = generateInvoiceNumber();
    formOrder.reset()
    formOrder.clearErrors()
}

function verifyOrder(order) {
    selectedOrder.value = order
    showModal.value = true
}

const formOrder = useForm({
    userId: null,
    planId: null,
    invoice_number: generateInvoiceNumber(), //null,
    amount: null,
    payment_method: null,
    midtrans_order_id: null,
    snap_token: null,
    payment_type: null,
    status: null,
    paid_at: null,
    payload: null,
});

const statuses = [
    { label: "Pending", value: "pending" },
    { label: "Paid", value: "paid" },
    { label: "Failed", value: "failed" },
    { label: "Expired", value: "expired" },
];

const types = [
    { label: "bank transfer", value: "bank_transfer" },
    { label: "gopay", value: "gopay" },
]

const methods = [
    { label: "Midtrans", value: "midtrans" },
    { label: "Manual", value: "manual" },
]

const idOrder = ref('');
const idDeleteOrder = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;
}

// open edit modal
const openEditModal = (order) => {
    showEditModal.value = true;
    idOrder.value = order.id;

    formOrder.userId = order.user_id;
    formOrder.planId = order.plan_id;
    formOrder.invoice_number = order.invoice_number;
    formOrder.amount = order.amount;
    formOrder.payment_method = order.payment_method;
    formOrder.midtrans_order_id = order.midtrans_order_id;
    formOrder.snap_token = order.snap_token
    formOrder.payment_type = order.payment_type
    formOrder.status = order.status
    formOrder.paid_at = order.paid_at
    formOrder.payload = order.payload

}

// update method
const updateOrder = () => {
    formOrder
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/order/update/' + idOrder.value, {
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
const addOrder = () => {
    formOrder.post('/admin/order/create', {
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
    idDeleteOrder.value = id;
}

// delete method
const deleteOrder = async () => {
    try {
        await router.delete('/admin/order/delete/' + idDeleteOrder.value, {
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
    router.get('/admin/orders', { q: value }, { preserveState: true, replace: true })
});


// delete selected
const selected = ref([])

// Select all logic
const allSelected = computed(() => {
    if (!props.orders?.data) return false

    return (
        props.orders.data.length > 0 &&
        selected.value.length === props.orders.data.length
    )
})


const toggleAll = (e) => {
    if (!props.orders?.data) return

    selected.value = e.target.checked
        ? props.orders.data.map(r => r.id)
        : []
}

// Delete selected
const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.order.bulk-delete'), {
        data: { ids: selected.value },
        preserveScroll: true,
        onSuccess: () => {
            selected.value = []
        },
    })
}

//
watch(
    () => formOrder.planId,
    (planId) => {
        const selectedPlan = props.plans.find(
            plan => plan.id == planId
        )

        if (!selectedPlan) {
            formOrder.amount = ''
            return
        }

        const price = Number(selectedPlan.price)
        const discount = Number(selectedPlan.discount || 0)

        formOrder.amount =
            price - (price * discount / 100)
    }
)

// Fungsi untuk menghasilkan string acak alfanumerik
function generateInvoiceNumber(length = 10) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return `INV-${result}`;
}

// Fungsi handler untuk set nilai ke form
function setRandomInvoice() {
    formOrder.invoice_number = generateInvoiceNumber();
}
</script>

<template>

    <AdminLayout>

        <Head>
            <title>Orders</title>
        </Head>
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <!-- <pre>{{ props.orders }}</pre> -->
            <div class="w-full mb-1">
                <div class="mb-4">
                    <!-- breadcrumb -->

                    <!-- end breadcrumb -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All orders</h1>
                </div>
                <div class="sm:flex">
                    <div
                        class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <div class="lg:pr-3">
                            <label for="orders-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input v-model="search" type="text" name="email" id="orders-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search for orders">
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
                            Add order
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
                                        Payment method
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Plan
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Invoice
                                        <!-- invoice_number
                                        midtrans_order_id 
                                        -->
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Amount
                                    </th>

                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Payment
                                        <!-- payment_type 
                                          -->
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Paid at
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                <tr v-for="(order, index) in props.orders.data" :key="order.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${order.id}`" :value="order.id" v-model="selected"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${order.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.orders.current_page - 1) * props.orders.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">
                                        <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{
                                                order.user.first_name }} {{ order.user.last_name }}</div>
                                            <div class="text-sm text-gray-700 font-medium">@{{ order.user.username }}
                                            </div>
                                            <div class="text-sm font-normal text-gray-500 dark:text-gray-400">{{
                                                order.user.email }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ order.payment_method }}</td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ order.plan.name }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <div>{{ order.invoice_number }}</div>
                                        <div>{{ order.midtrans_order_id }}</div>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ order.amount }}</td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>{{ order.payment_type }}</div>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <span class="px-2 py-1 rounded-lg text-xs text-white" :class="{
                                            'bg-green-600': order.status === 'paid',
                                            'bg-red-600': order.status === 'failed',
                                            'bg-orange-600': order.status === 'pending',
                                            'bg-gray-600': order.status === 'expired',
                                        }">{{ order.status }}</span>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-600 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ formatUS(order.paid_at) }}</td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="verifyOrder(order)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-teal-500 hover:bg-teal-600 focus:ring-4 focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            Verify
                                        </button>
                                        <button @click="openEditModal(order)" type="button"
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

                                        <button @click="openDeleteModal(order.id)" type="button"
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

        <PaginationMod :meta="props.orders" :query="{ search: search }" :limit="props.page" />

        <!-- Edit Order Modal -->
        <Modal :show="showEditModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Edit order
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
                    <form @submit.prevent="updateOrder">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="plan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Plan
                                    </label>
                                    <select v-model="formOrder.planId" id="plan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select plan</option>
                                        <option v-for="plan in props.plans" :key="plan.id" :value="plan.id">{{ plan.name
                                        }}</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="method"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Payment Method
                                    </label>
                                    <select v-model="formOrder.payment_method" id="method"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select payment method</option>
                                        <option v-for="m in methods" :key="m.value" :value="m.value">
                                            {{ m.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="user"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        User
                                    </label>
                                    <SearchDropdown v-model="formOrder.userId" :users="props.users" />
                                </div>

                                <!-- <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Invoice Number
                                    </label>
                                    <input type="text" v-model="formOrder.invoice_number" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div> -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block mb-1 text-sm font-medium text-slate-800 dark:text-white">
                                        Invoice Number
                                    </label>

                                    <div class="flex gap-2">
                                        <input v-model="formOrder.invoice_number" type="text"
                                            placeholder="INV-XXXXXXXXXX"
                                            class="flex-1 bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />

                                        <button type="button" @click="setRandomInvoice"
                                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 rounded text-sm flex items-center gap-1 transition"
                                            title="Generate Random Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            <span>Generate</span>
                                        </button>
                                    </div>

                                    <!-- Error display jika ada -->
                                    <span v-if="formOrder.errors.invoice_number" class="text-xs text-red-500 mt-1">
                                        {{ formOrder.errors.invoice_number }}
                                    </span>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="amount"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Amount
                                    </label>
                                    <input type="number" v-model="formOrder.amount" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Midtrans Order Id
                                    </label>
                                    <input type="text" v-model="formOrder.midtrans_order_id" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Payment Type
                                    </label>
                                    <select v-model="formOrder.payment_type" id="type"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select payment type</option>
                                        <option v-for="p in types" :key="p.value" :value="p.value">
                                            {{ p.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="status"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Status
                                    </label>
                                    <select v-model="form.status" id="status"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select status</option>
                                        <option v-for="d in statuses" :key="d.value" :value="d.value">
                                            {{ d.label }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="paid"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Paid At
                                    </label>
                                    <VueDatePicker v-model="formOrder.paid_at"
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


        <!-- Add Order Modal -->
        <Modal :show="showCreateModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Add new order
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
                    <form action="#" @submit.prevent="addOrder">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="plan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Plan
                                    </label>
                                    <select v-model="formOrder.planId" id="plan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select plan</option>
                                        <option v-for="plan in props.plans" :key="plan.id" :value="plan.id">{{ plan.name
                                        }}</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="method"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Payment Method
                                    </label>
                                    <select v-model="formOrder.payment_method" id="method"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select payment method</option>
                                        <option v-for="m in methods" :key="m.value" :value="m.value">
                                            {{ m.label }}
                                        </option>
                                    </select>

                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="user"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        User
                                    </label>
                                    <SearchDropdown v-model="formOrder.userId" :users="props.users" />
                                    <!-- <select v-model="formOrder.userId" id="user"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled value="">Select user</option>
                                        <option v-for="user in props.users" :key="user.id"
                                            :value="user.id">{{ user.first_name }} {{ user.last_name }}</option>
                                    </select> -->
                                </div>

                                <!-- <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Invoice Number
                                    </label>
                                    <input type="text" v-model="formOrder.invoice_number" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div> -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block mb-1 text-sm font-medium text-slate-800 dark:text-white">
                                        Invoice Number
                                    </label>

                                    <div class="flex gap-2">
                                        <input v-model="formOrder.invoice_number" type="text"
                                            placeholder="INV-XXXXXXXXXX"
                                            class="flex-1 bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />

                                        <button type="button" @click="setRandomInvoice"
                                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 rounded text-sm flex items-center gap-1 transition"
                                            title="Generate Random Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            <span>Generate</span>
                                        </button>
                                    </div>

                                    <!-- Error display jika ada -->
                                    <span v-if="formOrder.errors.invoice_number" class="text-xs text-red-500 mt-1">
                                        {{ formOrder.errors.invoice_number }}
                                    </span>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="amount"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Amount
                                    </label>
                                    <input type="number" v-model="formOrder.amount" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Midtrans Order Id
                                    </label>
                                    <input type="text" v-model="formOrder.midtrans_order_id" id=""
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="invoice"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Payment Type
                                    </label>
                                    <select v-model="formOrder.payment_type" id="type"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select payment type</option>
                                        <option v-for="p in types" :key="p.value" :value="p.value">
                                            {{ p.label }}
                                        </option>
                                    </select>

                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="status"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Status
                                    </label>
                                    <select v-model="formOrder.status" id="status"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select status</option>
                                        <option v-for="d in statuses" :key="d.value" :value="d.value">
                                            {{ d.label }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="paid"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Paid At
                                    </label>
                                    <VueDatePicker v-model="formOrder.paid_at"
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

        <!-- Delete Order Modal -->
        <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this order?"
            @confirm="deleteOrder" @close="closeModal" />

        <VerificationModal :show="showModal" :order="selectedOrder" @close="showModal = false" />

    </AdminLayout>
</template>