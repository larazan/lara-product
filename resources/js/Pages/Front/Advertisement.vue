<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <FrontLayout>

        <Head title="Advertisement" />

        <section class="max-w-2xl mx-auto  dark:bg-gray-800 pt-2 px-2 md:pt-4 pb-4 border-b">

            <main class="flex bg-white min-h-screen rounded-md shadow px-3 ">
                <div class="container mx-auto my-3">
                    <div class="max-w-[85rem] px-4 md:px-6 lg:px-8 mx-auto">
                        <div class="max-w-3xl py-4 mx-auto space-y-6">
                            <h1 class="leading-9 text-2xl text-gray-900 font-bold">Advertise with us</h1>


                            <p class="text-gray-700">
                                Would you like to give your business a boost? Advertise in our website, newsletter and
                                on our social media.
                                On our website we have popup option, it show almost in every page.
                                Here's an example:
                            </p>

                            <div>
                                <h1 class="text-lg md:text-2xl text-gray-900 font-medium">1. Popup</h1>

                                <div class="w-60">
                                    <img src="/frontend/images/adv-home.webp"
                                        class="w-full border p-1 border-purple-100">
                                </div>

                                <div class="flex py-6">
                                    <div class="w-full border border-gray-400 rounded overflow-x-auto">
                                        <table class="w-full divide-y divide-gray-400">
                                            <tbody class="divide-y divide-gray-400 text-sm bg-gray-100 text-slate-700">
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Dimension</td>
                                                    <td class="px-4 py-2">400x500 pixel</td>
                                                </tr>
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Show on page</td>
                                                    <td class="px-4 py-2">homepage</td>
                                                </tr>
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Stats</td>
                                                    <td class="px-4 py-2">
                                                        <div class="">Site visitor: 6500+ a day</div>
                                                        <div class="">Click Rate: 5-6%</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <h2 class="text-lg font-medium text-[#FF6D42]">Book Now</h2>

                                    <div class="grid sm:grid-cols-2 gap-10">
                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                3 Month ($2000 USD)
                                            </p>
                                            <a href="https://book.stripe.com/8wMfZ6a9w8yX3oA7sw"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>

                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                6 Month ($3500 USD)
                                            </p>
                                            <a href="https://buy.stripe.com/6oEcMUftQbL9bV6fZ1"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h1 class="text-lg md:text-2xl text-gray-900 font-medium">2. Banner</h1>

                                <div class="w-60">
                                    <img src="/frontend/images/adv-detail.webp"
                                        class="w-full border p-1 border-purple-100">
                                </div>

                                <div class="flex py-6">
                                    <div class="w-full border border-gray-400 rounded overflow-x-auto">
                                        <table class="w-full divide-y divide-gray-400">
                                            <tbody class="divide-y divide-gray-400 text-sm bg-gray-100 text-slate-700">
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Dimension</td>
                                                    <td class="px-4 py-2">728x90 pixel</td>
                                                </tr>
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Show on page</td>
                                                    <td class="px-4 py-2">category page, series page, customize page,
                                                        article page</td>
                                                </tr>
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Stats</td>
                                                    <td class="px-4 py-2">
                                                        <div class="">Site visitor: 6500+ a day</div>
                                                        <div class="">Click Rate: 5-6%</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <h2 class="text-lg font-medium text-[#FF6D42]">Book Now</h2>

                                    <div class="grid sm:grid-cols-2 gap-10">
                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                3 Month ($2000 USD)
                                            </p>
                                            <a href="https://book.stripe.com/8wMfZ6a9w8yX3oA7sw"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>

                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                6 Month ($3500 USD)
                                            </p>
                                            <a href="https://buy.stripe.com/6oEcMUftQbL9bV6fZ1"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h1 class="text-lg md:text-2xl text-gray-900 font-medium">3. Newsletter</h1>
                                <p class="text-gray-700">
                                    We send out 4 newsletter emails a month. Our simple Newsletter advertisement program
                                    allows you to
                                    place an image-based banner inside our newsletter.
                                    Here's an example:
                                </p>

                                <div class="w-full">
                                    <img src="/frontend/img/ads-sample.jpg" class="w-full border p-1 border-purple-100">
                                </div>

                                <div class="flex py-6">
                                    <div class="w-full border border-gray-400 rounded overflow-x-auto">
                                        <table class="w-full divide-y divide-gray-400">
                                            <tbody class="divide-y divide-gray-400 text-sm bg-gray-100 text-slate-700">
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Dimension</td>
                                                    <td class="px-4 py-2">728x90 pixel</td>
                                                </tr>
                                                <tr class="divide-x divide-gray-400">
                                                    <td class="px-4 py-2">Stats</td>
                                                    <td class="px-4 py-2">
                                                        <div class="">Subscriber Count: 6500+</div>
                                                        <div class="">Open Rate: ~54%</div>
                                                        <div class="">Click Rate: 5-6%</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <h2 class="text-lg font-medium text-[#FF6D42]">Book Now</h2>
                                    <p class="text-gray-700">
                                        Our pricing is incredibly simple: $250 USD per issue. However, if you book for a
                                        month (4
                                        issues bundle), you'll enjoy a significant discount—only $800 USD.
                                        Secure your slots now before they run out.
                                    </p>

                                    <div class="grid sm:grid-cols-2 gap-10">
                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                Single Issue ($250 USD)
                                            </p>
                                            <a href="https://book.stripe.com/8wMfZ6a9w8yX3oA7sw"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>

                                        <div class="border p-4 rounded">
                                            <p class="text-center text-lg font-bold text-gray-700">
                                                Monthly Bundle ($800 USD)
                                            </p>
                                            <a href="https://buy.stripe.com/6oEcMUftQbL9bV6fZ1"
                                                class="mt-4 block w-full rounded-md bg-blue-600 px-3 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600  transition duration-500">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="flex flex-col pt-4 text-gray-800 gap-3">
                                <p>In order to book an sponsorship, please send us an email at <strong
                                        class="yellow_highlight">hello@<span
                                            style="display:none;">xyz</span>worldhistory.org</strong> and we will get
                                    back to you shortly.</p>
                                <p>Millon Facts reserves the right to reject sponsorships and promotions. We generally
                                    do not accept promotions&nbsp;in the following categories:</p>
                                <ul class="list-disc pl-6">
                                    <li>Nothing illegal</li>
                                    <li>No adult content, must be family-friendly</li>
                                    <li>No alcohol, tobacco, drugs, religion or politics</li>
                                    <li>No essay writing services</li>
                                    <li>No guest or sponsored posts</li>
                                    <li>No paid link insertions</li>
                                </ul>
                                <p><strong>All prices on this page are indicative and may be subject to change in
                                        accordance with market rates and seasonal effects on readership.</strong></p>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </section>
    </FrontLayout>
</template>