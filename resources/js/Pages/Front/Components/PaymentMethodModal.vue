<script setup>
import { ref } from "vue"
import { router, useForm } from "@inertiajs/vue3"
import Modal from "@/Components/Modal.vue"

const props = defineProps({
    show: Boolean,
})

const emit = defineEmits(["close"])

const file = ref(null)
const loading = ref(false)
const progress = ref(0)


</script>

<template>

    <Modal :show="show" @close="$emit('close')" maxWidth="md">

        <div class="bg-white p-6">

            <h2 class="text-lg font-semibold mb-4">
                Pilih metode pembayaran
            </h2>

        <div class="space-y-5">
            <div class="space-y-2.5">    
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"
                        data-slot="icon" class="h-4 w-4 text-blue-500">
                        <path
                            d="M2.273 5.625A4.483 4.483 0 0 1 5.25 4.5h13.5c1.141 0 2.183.425 2.977 1.125A3 3 0 0 0 18.75 3H5.25a3 3 0 0 0-2.977 2.625ZM2.273 8.625A4.483 4.483 0 0 1 5.25 7.5h13.5c1.141 0 2.183.425 2.977 1.125A3 3 0 0 0 18.75 6H5.25a3 3 0 0 0-2.977 2.625ZM5.25 9a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3v-6a3 3 0 0 0-3-3H15a.75.75 0 0 0-.75.75 2.25 2.25 0 0 1-4.5 0A.75.75 0 0 0 9 9H5.25Z">
                        </path>
                    </svg>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Dompet Digital</p>
                </div>

                <div class="space-y-2">
                    <button type="button"
                        class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-emerald-500 bg-emerald-50/50 shadow-sm dark:bg-emerald-500/10">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                <img alt="GoPay logo" class="h-full w-full object-contain"
                                    src="/frontend/images/Gopay.svg" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">GoPay</p>
                            </div>
                        </div>
                        <div
                            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-emerald-500 bg-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true" data-slot="icon" class="h-4 w-4 text-white">
                                <path fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button><button type="button"
                        class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                <img alt="ShopeePay logo" class="h-full w-full object-contain"
                                    src="/frontend/images/ShopeePay.svg" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">ShopeePay</p>
                            </div>
                        </div>
                        <div
                            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                        </div>
                    </button><button type="button"
                        class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                <img alt="Dana logo" class="h-full w-full object-contain" src="/frontend/images/dana.svg" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Dana</p>
                            </div>
                        </div>
                        <div
                            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                        </div>
                    </button><button type="button"
                        class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                <img alt="QRIS logo" class="h-full w-full object-contain" src="/frontend/images/QRIS.svg" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">QRIS</p>
                            </div>
                        </div>
                        <div
                            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                        </div>
                    </button><button type="button"
                        class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                <img alt="OVO logo" class="h-full w-full object-contain" src="/frontend/images/ovo.svg" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">OVO</p>
                            </div>
                        </div>
                        <div
                            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                        </div>
                    </button>
                </div>
            </div>

            <div class="space-y-2.5">
                    <div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" aria-hidden="true" data-slot="icon" class="h-4 w-4 text-blue-500">
                            <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z"></path>
                            <path fill-rule="evenodd"
                                d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                                clip-rule="evenodd"></path>
                            <path
                                d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z">
                            </path>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Bank Transfer</p>
                    </div>
                    <div class="space-y-2"><button type="button"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                    <img alt="Mandiri logo" class="h-full w-full object-contain"
                                        src="/frontend/images/Mandiri.svg"></div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Mandiri</p>
                                </div>
                            </div>
                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                            </div>
                        </button><button type="button"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                    <img alt="BRI logo" class="h-full w-full object-contain" src="/frontend/images/BRI.svg">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">BRI</p>
                                </div>
                            </div>
                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                            </div>
                        </button><button type="button"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                    <img alt="BCA logo" class="h-full w-full object-contain" src="/frontend/images/BCA.svg">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">BCA</p>
                                </div>
                            </div>
                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                            </div>
                        </button><button type="button"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-4 py-3 text-left transition-all duration-200 border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-lg bg-white p-2 shadow-sm ring-1 ring-gray-100 dark:bg-neutral-200 dark:ring-0">
                                    <img alt="BNI logo" class="h-full w-full object-contain" src="/frontend/images/BNI.svg">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">BNI</p>
                                </div>
                            </div>
                            <div
                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all border-gray-300 dark:border-neutral-600">
                            </div>
                        </button></div>
            </div>
        </div>

        </div>
    </Modal>
</template>