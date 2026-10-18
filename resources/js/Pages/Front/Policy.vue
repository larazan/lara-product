<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <FrontLayout>

        <Head title="Privacy Policy" />

        <section class="max-w-2xl mx-auto  dark:bg-gray-800 pt-2 md:pt-4 pb-4 border-b">
            <div class="max-w-5xl mx-auto px-2">
                <div class="bg-white dark:bg-gray-900 rounded-xl w-full shadow px-2 py-3 md:p-3">

                    <div class="px-3 py-6">

                        <h1 class="text-3xl font-bold text-gray-900 mb-6">
                            Privacy Policy
                        </h1>

                        <p class="text-gray-600 mb-6">
                            Last updated: {{ new Date().getFullYear() }}
                        </p>

                        <!-- Introduction -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                1. Introduction
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                Your privacy is important to us. This Privacy Policy explains
                                how we collect, use, and protect your information when you use
                                our quiz platform.
                            </p>
                        </section>

                        <!-- Information We Collect -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                2. Information We Collect
                            </h2>
                            <ul class="list-disc pl-6 text-gray-600 space-y-2">
                                <li>Personal information such as name, username, and email address.</li>
                                <li>Account information when you register or update your profile.</li>
                                <li>Quiz activity, scores, and progress.</li>
                                <li>Technical information such as browser type, IP address, and device information.</li>
                            </ul>
                        </section>

                        <!-- How We Use Information -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                3. How We Use Your Information
                            </h2>
                            <ul class="list-disc pl-6 text-gray-600 space-y-2">
                                <li>To create and manage your account.</li>
                                <li>To provide quiz services and track progress.</li>
                                <li>To improve the functionality and experience of our platform.</li>
                                <li>To communicate updates, support messages, or service notifications.</li>
                            </ul>
                        </section>

                        <!-- Cookies -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                4. Cookies and Tracking
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                Our website may use cookies and similar technologies to improve
                                user experience, remember preferences, and analyze platform usage.
                                You can control cookies through your browser settings.
                            </p>
                        </section>

                        <!-- Data Sharing -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                5. Sharing Your Information
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We do not sell or rent your personal information to third parties.
                                Your data may only be shared when required by law or when necessary
                                to provide essential platform services.
                            </p>
                        </section>

                        <!-- Data Security -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                6. Data Security
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We take reasonable security measures to protect your information
                                from unauthorized access, alteration, or disclosure.
                            </p>
                        </section>

                        <!-- User Rights -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                7. Your Rights
                            </h2>
                            <ul class="list-disc pl-6 text-gray-600 space-y-2">
                                <li>You may update or correct your account information at any time.</li>
                                <li>You may request deletion of your account.</li>
                                <li>You may contact us if you have questions about your personal data.</li>
                            </ul>
                        </section>

                        <!-- Changes -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                8. Changes to This Policy
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We may update this Privacy Policy from time to time.
                                Continued use of the platform means you accept the updated policy.
                            </p>
                        </section>

                        <!-- Contact -->
                        <section>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                9. Contact Us
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                If you have questions about this Privacy Policy, please contact us
                                through our support or contact page.
                            </p>
                        </section>

                    </div>


                </div>
            </div>
        </section>
    </FrontLayout>
</template>