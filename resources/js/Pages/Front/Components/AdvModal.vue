<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const show = ref(false)
const ad = ref(null)
const loading = ref(false)

// for premium
// onMounted(() => {
//     if (!page.props.auth.user?.is_premium) {
//         fetchAd()
//     }
// })

// per one session
// onMounted(() => {
//     if (!sessionStorage.getItem('adShown')) {
//         fetchAd()
//         sessionStorage.setItem('adShown', 'true')
//     }
// })

const fetchAd = async () => {
    try {
        loading.value = true
        const { data } = await axios.get('/ads/random')

        // ✅ Only show if valid ad exists
        if (data && data.id) {
            ad.value = data
            show.value = true

            // wait until DOM rendered
            await nextTick()
            trackView(data.id)
        } else {
            show.value = false
        }

    } catch (error) {
        show.value = false
        console.error(error)
    } finally {
        loading.value = false
    }
}

const close = () => {
    show.value = false
}

const trackView = async (adId) => {
    try {
        await axios.post(`/ads/${adId}/view`)
        console.log('View tracked')
    } catch (error) {
        console.error('View error:', error)
    }
}

const trackClick = async (adId) => {
    try {
        await axios.post(`/ads/${adId}/click`)
        console.log('Click tracked')
    } catch (error) {
        console.error('Click error:', error)
    }
}

onMounted(() => {
    fetchAd()
})
</script>

<template>
    <!-- ✅ Modal only rendered if ad exists -->
    <Modal :show="show" @close="close" maxWidth="lg">
        
<!--  -->
        <div class="relative bg-white overflow-auto custom-scrollbar max-w-4xl md:w-full w-full  max-h-84 rounded shadow-lg">
          
          <section  class="ad-container overflow-hidden2 h-full shadow-2xl md:grid md:grid-cols-1">
            
            <div v-if="ad.original" class="ad-img" data-ad-id="{{ ad.id }}" >
              <img
                v-if="ad.original"
                :src="`/storage/${ad.original}`" 
                alt="adv"
                class="h-52 w-full object-cover md:h-full"
              />
            </div>

            <div v-else class=" h-40 banner text-gray-600 text-[10px] bg-gray-200  dark:bg-black dark:text-white  lg:mx-0">
                <span class="text-center upperase block py-1">ADVERTISEMENT</span>
                <div class="banner-inner flex justify-center items-center  exposer"
                    id="gpt-ad-div-exposer-placeholder"></div>
            </div>

            <button class="absolute flex top-1 right-1 rounded-full bg-gray-100 px-1 py-1 hover:bg-gray-200 cursor-pointer" v-on:click="close">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width=3
                stroke="currentColor"
                class="w-5 h-5 text-[#1d494e]"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
            
            <div class=" p-4 text-center sm:p-6 md:col-span-2 lg:p-8">
              <!-- <p class="md:text-2xl font-semibold uppercase md:tracking-widest2 text-black pally-bold">
                {{ ad.title }}
              </p> -->

              <h2 class="mt-3 font-black capitalize">
                <span class="text-lg text-black md:text-2xl">
                  Take 20% off Your Order
                </span>

                <span class="mt-2 block text-sm text-black">
                  On your next order over $50
                </span>
              </h2>

              <Link
                class="ad-link mt-4 md:mt-6 inline-block w-full rounded-full bg-black py-4 text-sm font-bold capitalize tracking-widest text-white"
                v-if="ad.original"
                :href="ad.url"
                @click="trackClick(ad.id)"
                target="_blank"
                data-ad-id="{{ ad.id }}" 
              >
                Go to Website
              </Link>

              <Link
                class="ad-link mt-4 md:mt-6 inline-block w-full rounded-full bg-black py-4 text-sm font-bold capitalize tracking-widest text-white"
                v-else
                :href="route('advertisement.index')"
                @click="trackClick(ad.id)"
              >
                Advertise with us
              </Link>

              <p class="mt-4 md:mt-6 text-xs font-medium uppercase text-gray-600">
                Offer valid until 24th March, 2021 *
              </p>
            </div>
          </section>
          
        </div>
    </Modal>
</template>
