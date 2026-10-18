<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

const show = ref(false)
const ad = ref(null)
const loading = ref(false)

const fetchAd = async () => {
    try {
        loading.value = true
        const { data } = await axios.get('/ads/leaderboard')

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
    <div v-if="show">
        <div class="relative flex mb-10 pt-0" >
            <button class="absolute flex top-0 right-0 rounded2 bg-gray-100 px-1 py-1 hover:bg-gray-200 cursor-pointer"
            v-on:click="close" >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width=3
                    stroke="currentColor" class="w-4 h-4 text-[#1d494e]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="w-full">
                <a 
                    :href="ad.url"
                    @click="trackClick(ad.id)"
                    target="_blank"
                    data-ad-id="{{ ad.id }}" 
                    class="ad-link"
                >
                    <img 
                        alt="adv" 
                        v-if="ad.original"
                        :src="`/storage/${ad.original}`" 
                        class="w-full h-[90px] object-cover md:h-full" 
                    />
                </a>
            </div>
        </div>
    </div>
   
    <div v-else class=" h-20 banner text-gray-600 text-[10px] bg-gray-200 mb-10 dark:bg-black dark:text-white  lg:mx-0">
        <span class="text-center upperase block py-1">ADVERTISEMENT</span>
        <div class="banner-inner flex justify-center items-center min-h-[600px] exposer"
            id="gpt-ad-div-exposer-placeholder"></div>
    </div>
</template>