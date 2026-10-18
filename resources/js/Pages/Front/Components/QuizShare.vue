<script setup>
    const props = defineProps({
      shareUrl: String,
      title: String,
    })
    </script>
    
    <template>
      <div class="flex items-center space-x-3">
        <!-- WhatsApp -->
        <a
          :href="`https://wa.me/?text=${encodeURIComponent(title + ' ' + shareUrl)}`"
          target="_blank"
          rel="noopener"
          class="px-3 py-2 bg-green-500 text-white rounded"
        >
          WhatsApp
        </a>
    
        <!-- Facebook -->
        <a
          :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`"
          target="_blank"
          rel="noopener"
          class="px-3 py-2 bg-blue-600 text-white rounded"
        >
          Facebook
        </a>
    
        <!-- X (Twitter) -->
        <a
          :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(shareUrl)}`"
          target="_blank"
          rel="noopener"
          class="px-3 py-2 bg-black text-white rounded"
        >
          X
        </a>
    
        <!-- Copy link -->
        <button
          @click="navigator.clipboard.writeText(shareUrl)"
          class="px-3 py-2 border rounded"
        >
          Copy link
        </button>
      </div>
    </template>
    
    <!-- <QuizShare
    :share-url="shareUrl"
    :title="quiz.title"
  /> -->