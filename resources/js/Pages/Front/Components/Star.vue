<script setup>
    import { computed } from 'vue'
    
    const props = defineProps({
      rating: {
        type: Number,
        required: true,
      },
      max: {
        type: Number,
        default: 5,
      },
      size: {
        type: Number,
        default: 20, // px
      },
      showValue: {
        type: Boolean,
        default: false,
      },
    })
    
    const stars = computed(() => {
      const full = Math.floor(props.rating)
      const half = props.rating % 1 >= 0.5
      const empty = props.max - full - (half ? 1 : 0)
    
      return { full, half, empty }
    })
    </script>
    
    <template>
      <div class="flex items-center ">
        <!-- FULL STARS -->
        <svg
          v-for="i in stars.full"
          :key="'full-' + i"
          :width="size"
          :height="size"
          class="text-[#ffc600]"
          fill="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"
          />
        </svg>
    
        <!-- HALF STAR -->
        <svg
          v-if="stars.half"
          :width="size"
          :height="size"
          viewBox="0 0 24 24"
        >
          <defs>
            <linearGradient id="half-star">
              <stop offset="50%" stop-color="#ffc600" />
              <stop offset="50%" stop-color="#d1d5db" />
            </linearGradient>
          </defs>
          <path
            fill="url(#half-star)"
            d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"
          />
        </svg>
    
        <!-- EMPTY STARS -->
        <svg
          v-for="i in stars.empty"
          :key="'empty-' + i"
          :width="size"
          :height="size"
          class="text-[#d1d5db]"
          fill="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"
          />
        </svg>
    
        <!-- OPTIONAL VALUE -->
        <span v-if="showValue" class="ml-2 text-sm text-gray-600">
          {{ rating.toFixed(1) }}
        </span>
      </div>
    </template>
    