<script setup>
    import { inject, computed } from 'vue'
    
    const props = defineProps({
      name: {
        type: String,
        required: true,
      },
    })
    
    const activeTab = inject('activeTab')
    const isActive = computed(() => activeTab.value === props.name)
    </script>
    
    <template>
      <div v-show="isActive">
        <slot />
      </div>
    </template>
    