<script setup>
    import { inject, computed } from 'vue'
    
    const props = defineProps({
      name: {
        type: String,
        required: true,
      },
    })
    
    const activeTab = inject('activeTab')
    const setActiveTab = inject('setActiveTab')
    
    const isActive = computed(() => activeTab.value === props.name)
    </script>
    
    <template>
      <button
        @click="setActiveTab(name)"
        :class="[
          'px-4 py-2 text-sm font-medium border-b-2 transition',
          isActive
            ? 'border-indigo-600 text-indigo-600'
            : 'border-transparent text-gray-500 hover:text-gray-700'
        ]"
      >
        <slot />
      </button>
    </template>
    