<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <FrontLayout>

        <Head title="Terms and conditions" />

        <section class="max-w-2xl mx-auto  dark:bg-gray-800 pt-2 md:pt-4 pb-4 border-b">
            <div class="max-w-5xl mx-auto px-2">
                <div class="bg-white dark:bg-gray-900 rounded-xl w-full shadow px-2 py-3 md:p-3">


                    <div class=" px-3 py-6">

                        <h1 class="text-3xl font-bold text-gray-900 mb-6">
                            Terms and Conditions
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
                                Welcome to our quiz platform. By accessing or using our website,
                                you agree to comply with and be bound by these Terms and Conditions.
                                If you do not agree with any part of these terms, you should not use
                                our services.
                            </p>
                        </section>

                        <!-- User Accounts -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                2. User Accounts
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                To access certain features of the platform, you may need to create
                                an account. You are responsible for maintaining the confidentiality
                                of your account credentials and for all activities that occur under
                                your account.
                            </p>
                        </section>

                        <!-- Use of Platform -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                3. Acceptable Use
                            </h2>
                            <ul class="list-disc pl-6 text-gray-600 space-y-2">
                                <li>You must not use the platform for unlawful purposes.</li>
                                <li>You must not attempt to hack, disrupt, or misuse the platform.</li>
                                <li>You must not submit harmful or misleading content.</li>
                                <li>You must respect other users and their contributions.</li>
                            </ul>
                        </section>

                        <!-- Content -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                4. Content and Quizzes
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                The quizzes and content available on this platform are provided
                                for educational and entertainment purposes. While we strive to
                                ensure accuracy, we do not guarantee that all information is
                                completely accurate or up to date.
                            </p>
                        </section>

                        <!-- Intellectual Property -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                5. Intellectual Property
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                All content on this website, including quizzes, designs,
                                logos, and text, is the property of the platform unless
                                otherwise stated. Unauthorized reproduction or distribution
                                is prohibited.
                            </p>
                        </section>

                        <!-- Termination -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                6. Account Termination
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We reserve the right to suspend or terminate user accounts
                                that violate these terms or engage in harmful behavior.
                            </p>
                        </section>

                        <!-- Liability -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                7. Limitation of Liability
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                The platform is provided "as is". We are not liable for any
                                damages resulting from the use or inability to use the service.
                            </p>
                        </section>

                        <!-- Changes -->
                        <section class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                8. Changes to Terms
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We may update these Terms and Conditions at any time.
                                Continued use of the platform means you accept the
                                updated terms.
                            </p>
                        </section>

                        <!-- Contact -->
                        <section>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                9. Contact Us
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                If you have any questions about these Terms and Conditions,
                                please contact us through our support page.
                            </p>
                        </section>

                    </div>


                </div>
            </div>
        </section>
    </FrontLayout>
</template>