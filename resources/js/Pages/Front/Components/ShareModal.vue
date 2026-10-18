<!-- resources/js/Components/ReportModal.vue -->
<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    show: Boolean,
    link: String,
})

const emit = defineEmits(['close'])

const copied = ref(false)

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(props.link)
        copied.value = true

        setTimeout(() => {
        copied.value = false
        }, 1500)
    } catch (e) {
        alert('Copy failed')
    }
}

    // 👉 Share handlers
const shareFacebook = () => {
  const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(props.link)}`
  window.open(url, '_blank', 'noopener,noreferrer')
}

const shareX = () => {
  const text = 'Check out this quiz!'
  const url = `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.link)}&text=${encodeURIComponent(text)}`
  window.open(url, '_blank', 'noopener,noreferrer')
}

const shareWhatsApp = () => {
  window.open(`https://wa.me/?text=${encodeURIComponent(props.link)}`)
}

</script>
    
    <template>
        <Modal :show="props.show" @close="emit('close')" maxWidth="xl">
            <div class="relative p-6 space-y-4">
                <button @click="emit('close')"
                    class="absolute top-2 right-2 p-2 text-indigo-500 rounded-full cursor-pointer lg:hidden hover:text-indigo-700 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg id="toggleSidebarMobileClose" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <h2 class="text-lg font-semibold">Share this Quiz</h2>
                <div class=" flex gap-2">
                    <input readonly
                        class="bg-gray-100 border border-gray-200 text-gray-700 font-bold text-sm md:text-base rounded-lg placeholder:text-gray-400 placeholder:font-semibold focus:ring-primary-500 focus:border-primary-500 block w-full px-4 py-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                      :value="props.link" >
                    <button @click="copyToClipboard"
                        class="flex w-32 justify-center items-center text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg md:rounded-full text-sm px-3 md:px-5 py-2.5 text-center ml-1 md:ml-3">
                        <span v-if="!copied">Copy link</span>
                        <span v-else>Copied!</span>
                    </button>
                </div>

                <a
                    :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(props.link)}`"
                    target="_blank"
                    rel="noopener" 
                    class="inline-flex w-full py-3 gap-2 rounded-full border border-blue-500 justify-center items-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-md font-semibold">
                    <span class="w-6 h-6 flex justify-center items-center rounded-full bg-blue-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="h-3">
                            <path fill="currentColor"
                                d="m279.14 288 14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z">
                            </path>
                        </svg>
                    </span>
                    Share on Facebook
                </a>
                
                <a
                    :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent('Check out this quiz!')}&url=${encodeURIComponent(props.link)}`"
                    target="_blank"
                    rel="noopener" 
                    class="inline-flex w-full py-3 gap-2 rounded-full border border-gray-500 justify-center items-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-md font-semibold">
                    <span class="w-4 h-4">
                        <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 512 512">
                            <path
                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z">
                            </path>
                        </svg>
                    </span>
                    Share on X
                </a>
                
                <a
                    :href="`https://wa.me/?text=${encodeURIComponent('Check this Quiz' + ' ' + props.link)}`"
                    target="_blank"
                    rel="noopener" 
                    class="inline-flex w-full py-3 gap-2 rounded-full border border-[#128c7e] justify-center items-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-md font-semibold">
                    <span class="w-6 h-6 flex justify-center items-center rounded-full bg-[#128c7e] text-white">
                        <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 448 512">
                                        <path
                                            d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
                                        </path>
                                    </svg>
                    </span>
                    Share on WhatsApp
                </a>
               
            </div>
        </Modal>
    </template>
    

<!-- <ShareModal 
        :show="showShare"
        @close="showShare = false"
    />  -->