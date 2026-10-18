<script setup>
    import { ref } from 'vue'
    import { router, usePage } from '@inertiajs/vue3'
    
    const props = defineProps({
      likeableId: Number,
      likeableType: String,
      initialLiked: Boolean,
      initialCount: Number,
    })
    
    const liked = ref(props.initialLiked)
    const count = ref(props.initialCount)
    
    const toggleLike = () => {
      router.post(
        route('like.store'),
        {
          likeable_id: props.likeableId,
          likeable_type: props.likeableType,
        },
        {
          preserveScroll: true,
          onSuccess: () => {
            liked.value = !liked.value
            count.value += liked.value ? 1 : -1
          },
        }
      )
    }
</script>
    

<template>
    <div class="flex ml-2 items-center space-x-2 cursor-pointer" @click="toggleLike">
        <div class="flex px-2 md:px-2 py-2  bg-gray-100 rounded-full hover:bg-gray-200">
            <!-- Heart Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" :fill="liked ? 'red' : 'none'" :stroke="liked ? 'red' : 'gray'"
                stroke-width="2" viewBox="0 0 24 24" class="hidden w-6 h-6 transition-all duration-200">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 
                0-3.597 1.126-4.312 2.733C11.597 4.876 9.935 
                3.75 8 3.75 5.411 3.75 3.312 5.765 
                3.312 8.25c0 7.22 8 12 8 12s8-4.78 
                8-12z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" :fill="liked ? 'red' : 'none'" :stroke="liked ? 'red' : 'gray'" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 transition-all duration-200">
  <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
</svg>

        </div>

        <span class="text-gray-700 dark:text-gray-300">
            {{ count }}
        </span>
    </div>
</template>


<!-- <LikeButton
    :likeable-id="quiz.id"
    likeable-type="App\\Models\\Quiz"
    :initial-liked="liked"
    :initial-count="quiz.likes_count"
/> -->
