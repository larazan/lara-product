<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { reactive } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const errors = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
});

function validate() {
    errors.name = '';
    errors.email = '';
    errors.subject = '';
    errors.message = '';

    let valid = true;

    if (!form.name || form.name.length < 2) {
        errors.name = 'Nama minimal 2 karakter';
        valid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.email || !emailRegex.test(form.email)) {
        errors.email = 'Masukkan alamat email yang valid';
        valid = false;
    }

    if (!form.subject || form.subject.length < 3) {
        errors.subject = 'Subjek minimal 3 karakter';
        valid = false;
    }

    if (!form.message || form.message.length < 10) {
        errors.message = 'Pesan minimal 10 karakter';
        valid = false;
    }

    if (form.message.length > 1000) {
        errors.message = 'Pesan tidak boleh lebih dari 1000 karakter';
        valid = false;
    }

    return valid;
}

function submit() {
    if (!validate()) return;
    form.post(route('contact.send'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>

    <Head title="Contact Us" />

    <FrontLayout>
        <section class="min-h-screen bg-slate-50/50 py-5 md:py-10 dark:bg-gray-900 transition-colors duration-200">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Section Header -->
                <div class="max-w-2xl mx-auto text-center mb-12 md:mb-16">

                    <h1 class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white md:text-4xl tracking-tight">
                        {{ $t('contact.contact') || 'Ada Pertanyaan? Kami Siap Membantu' }}
                    </h1>
                    <p class="mt-3 text-base text-slate-600 dark:text-gray-400">
                        {{ $t('contact.contact_desc') }}
                    </p>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                    <!-- Contact Info Panel (4 Cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        <div
                            class="rounded-3xl bg-indigo-600 p-8 text-white shadow-xl shadow-indigo-500/10 dark:bg-indigo-950/40 dark:border dark:border-indigo-800/50 relative overflow-hidden">
                            <!-- Decorative Circle Background -->
                            <div
                                class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-white/10 blur-2xl pointer-events-none">
                            </div>

                            <h2 class="text-2xl font-bold tracking-tight mb-6">
                                {{ $t('contact.help') || 'Informasi Kontak' }}
                            </h2>

                            <div class="space-y-6">
                                <!-- Location Info -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold">{{ $t('contact.ready') || 'Alamat Kantor' }}
                                        </h3>
                                        <p class="mt-1 text-sm text-indigo-100 dark:text-gray-300 leading-relaxed">
                                            401 Broadway, 24th Floor. Church View, London
                                        </p>
                                    </div>
                                </div>

                                <!-- Phone Info -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293a12.035 12.035 0 01-7.143-7.143l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold">{{ $t('contact.reach') || 'Telepon' }}</h3>
                                        <p class="mt-1 text-sm text-indigo-100 dark:text-gray-300">
                                            +1 (310) 2568-4578
                                        </p>
                                    </div>
                                </div>

                                <!-- Email Info -->
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold">{{ $t('contact.assist') || 'Email' }}</h3>
                                        <p class="mt-1 text-sm text-indigo-100 dark:text-gray-300">
                                            support@yourdomain.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form Panel (7 Cols) -->
                    <div class="lg:col-span-7">
                        <div
                            class="rounded-3xl bg-white p-6 sm:p-8 dark:bg-gray-800 shadow-xl shadow-slate-200/50 dark:shadow-none ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all">

                            <!-- Flash Notification -->
                            <transition name="fade">
                                <div v-if="$page.props.flash?.success"
                                    class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 p-4 text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20">
                                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium">{{ $page.props.flash.success }}</span>
                                </div>
                            </transition>

                            <form @submit.prevent="submit" class="space-y-5">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Name Field -->
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                                            {{ $t('contact.name') || 'Nama Lengkap' }}
                                        </label>
                                        <input v-model="form.name" type="text" id="name" placeholder="John Doe"
                                            class="block w-full rounded-xl border-0 bg-slate-50 dark:bg-gray-900 py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-gray-700 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm transition-all"
                                            :class="{ 'ring-red-500 focus:ring-red-500': errors.name }" />
                                        <p v-if="errors.name" class="mt-1.5 text-xs text-red-500 font-medium">{{
                                            errors.name }}</p>
                                    </div>

                                    <!-- Email Field -->
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                                            {{ $t('contact.email') || 'Alamat Email' }}
                                        </label>
                                        <input v-model="form.email" type="email" id="email"
                                            placeholder="johndoe@example.com"
                                            class="block w-full rounded-xl border-0 bg-slate-50 dark:bg-gray-900 py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-gray-700 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm transition-all"
                                            :class="{ 'ring-red-500 focus:ring-red-500': errors.email }" />
                                        <p v-if="errors.email" class="mt-1.5 text-xs text-red-500 font-medium">{{
                                            errors.email }}</p>
                                    </div>
                                </div>

                                <!-- Subject Field -->
                                <div>
                                    <label for="subject"
                                        class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                                        {{ $t('contact.subject') || 'Subjek Pesan' }}
                                    </label>
                                    <input v-model="form.subject" type="text" id="subject"
                                        placeholder="Bagaimana kami bisa membantu Anda?"
                                        class="block w-full rounded-xl border-0 bg-slate-50 dark:bg-gray-900 py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-gray-700 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm transition-all"
                                        :class="{ 'ring-red-500 focus:ring-red-500': errors.subject }" />
                                    <p v-if="errors.subject" class="mt-1.5 text-xs text-red-500 font-medium">{{
                                        errors.subject }}</p>
                                </div>

                                <!-- Message Field -->
                                <div>
                                    <label for="message"
                                        class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                                        {{ $t('contact.message') || 'Pesan Anda' }}
                                    </label>
                                    <textarea v-model="form.message" id="message" rows="5" maxlength="1000"
                                        placeholder="Tuliskan pertanyaan atau kendala Anda di sini..."
                                        class="block w-full rounded-xl border-0 bg-slate-50 dark:bg-gray-900 py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-gray-700 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm transition-all"
                                        :class="{ 'ring-red-500 focus:ring-red-500': errors.message }"></textarea>

                                    <div class="mt-2 flex items-center justify-between text-xs">
                                        <p v-if="errors.message" class="text-red-500 font-medium">{{ errors.message }}
                                        </p>
                                        <span class="text-slate-400 dark:text-gray-500 ml-auto">
                                            {{ form.message.length }}/1000
                                        </span>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button :disabled="form.processing" type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 transition-all cursor-pointer">
                                    <svg v-if="form.processing" class="h-4 w-4 animate-spin text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span>{{ $t('contact.send') || 'Kirim Pesan' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>