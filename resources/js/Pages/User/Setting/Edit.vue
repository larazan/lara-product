<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// State untuk tab aktif
const activeTab = ref('profile');
</script>

<template>

    <Head title="Setting" />

    <FrontLayout>
        <section class="max-w-2xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-lg md:text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $t('settings.edit_profile') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola informasi profil, keamanan kata sandi, dan pengaturan akun Anda.
                </p>
            </div>

            <!-- Tab Buttons Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="-mb-px flex space-x-6 sm:space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'profile'" :class="[
                        activeTab === 'profile'
                            ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400 font-semibold'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        'whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-colors duration-150 ease-in-out flex items-center gap-2'
                    ]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Informasi
                    </button>

                    <button @click="activeTab = 'password'" :class="[
                        activeTab === 'password'
                            ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400 font-semibold'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        'whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-colors duration-150 ease-in-out flex items-center gap-2'
                    ]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Kata Sandi
                    </button>

                    <button @click="activeTab = 'danger'" :class="[
                        activeTab === 'danger'
                            ? 'border-red-600 text-red-600 dark:border-red-400 dark:text-red-400 font-semibold'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        'whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-colors duration-150 ease-in-out flex items-center gap-2'
                    ]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Zona Bahaya
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Tab Profil -->
                <div v-show="activeTab === 'profile'"
                    class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                        class="max-w-2xl" />
                </div>

                <!-- Tab Password -->
                <div v-show="activeTab === 'password'"
                    class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <UpdatePasswordForm class="max-w-2xl" />
                </div>

                <!-- Tab Delete User -->
                <div v-show="activeTab === 'danger'"
                    class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-sm border border-red-100 dark:border-red-900/30">
                    <DeleteUserForm class="max-w-2xl" />
                </div>
            </div>
        </section>
    </FrontLayout>
</template>