<template>
    <div :class="wrapperClass" @click="focusInput" @keydown.stop>
      <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>
  
      <div
        :class="['flex flex-wrap items-center gap-2 p-2 rounded-md border', containerClass, { 'border-red-500': error } ]"
        @click.stop
      >
        <!-- tags -->
        <template v-for="(tag, i) in innerTags" :key="tagKey(tag, i)">
          <div :class="['flex items-center space-x-2 px-2 py-1 rounded-full text-sm', tagClass]">
            <slot name="tag" :tag="tag">
              <span class="truncate max-w-xs">{{ tag }}</span>
            </slot>
            <button
              type="button"
              @click="removeTag(i)"
              class="text-xs leading-none opacity-70 hover:opacity-100"
              aria-label="Remove tag"
            >
              ×
            </button>
          </div>
        </template>
  
        <!-- input -->
        <input
          ref="inputRef"
          v-model="input"
          :placeholder="placeholder"
          :class="['flex-1 min-w-[120px] outline-none border-none focus:ring-0 p-1 text-sm', inputClass]"
          @keydown="onKeydown"
          @input="onInput"
          @blur="onBlur"
          :aria-describedby="error ? errorId : null"
        />
  
        <!-- suggestions dropdown -->
        <div v-if="showSuggestions && filteredSuggestions.length" class="absolute bg-white mt-1 shadow rounded z-50 w-full max-w-xl">
          <ul class="max-h-48 overflow-auto p-1">
            <li
              v-for="(s, idx) in filteredSuggestions"
              :key="s"
              @mousedown.prevent="selectSuggestion(s)"
              class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
            >
              <slot name="suggestion" :suggestion="s">{{ s }}</slot>
            </li>
          </ul>
        </div>
      </div>
  
      <p v-if="error" :id="errorId" class="mt-1 text-xs text-red-600">{{ error }}</p>
      <p v-else-if="help" class="mt-1 text-xs text-gray-500">{{ help }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch, nextTick } from 'vue'
  
  const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Add tag and press Enter' },
    label: { type: String, default: '' },
    suggestions: { type: Array, default: () => [] },
    allowDuplicates: { type: Boolean, default: false },
    allowNew: { type: Boolean, default: true },
    maxTags: { type: Number, default: Infinity },
    debounceMs: { type: Number, default: 150 },
    createOnComma: { type: Boolean, default: true },
    containerClass: { type: String, default: '' },
    tagClass: { type: String, default: 'bg-gray-100 text-gray-800' },
    inputClass: { type: String, default: '' },
    wrapperClass: { type: String, default: 'relative' },
    help: { type: String, default: '' },
    error: { type: String, default: '' },
  })
  
  const emit = defineEmits(['update:modelValue', 'add', 'remove', 'focus', 'blur'])
  
  const inputRef = ref(null)
  const input = ref('')
  const innerTags = ref(
  Array.isArray(props.modelValue) ? [...props.modelValue] : []
)

  let debounceTimer = null
  const showSuggestions = ref(false)
  
  const errorId = `tags-error-${Math.random().toString(36).slice(2, 9)}`
  
  watch(
    () => props.modelValue,
    (v) => {
      innerTags.value = Array.isArray(v) ? [...v] : []
    }
  )
  
  watch(input, () => {
    // control dropdown visibility with debounce
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
      showSuggestions.value = !!(props.suggestions && input.value.trim())
    }, props.debounceMs)
  })
  
  const filteredSuggestions = computed(() => {
    if (!props.suggestions || !input.value) return []
    const q = input.value.toLowerCase()
    return props.suggestions.filter((s) => s.toLowerCase().includes(q) && (props.allowDuplicates || !innerTags.value.includes(s)))
  })
  
  function focusInput() {
    inputRef.value && inputRef.value.focus()
    emit('focus')
  }
  
  function tagKey(tag, i) {
    return `${tag}-${i}`
  }
  
  function addTagFromInput(raw = null) {
    const value = (raw !== null ? raw : input.value).toString().trim()
    if (!value) return
    if (!props.allowDuplicates && innerTags.value.includes(value)) {
      input.value = ''
      return
    }
    if (innerTags.value.length >= props.maxTags) return
  
    innerTags.value.push(value)
    emit('update:modelValue', [...innerTags.value])
    emit('add', value)
    input.value = ''
    showSuggestions.value = false
  }
  
  function removeTag(index) {
    const removed = innerTags.value.splice(index, 1)[0]
    emit('update:modelValue', [...innerTags.value])
    emit('remove', removed)
  }
  
  function onKeydown(e) {
    if (e.key === 'Enter') {
      e.preventDefault()
      addTagFromInput()
      return
    }
    if (props.createOnComma && e.key === ',') {
      e.preventDefault()
      addTagFromInput()
      return
    }
    if (e.key === 'Backspace' && input.value === '') {
      // delete last tag
      if (innerTags.value.length) removeTag(innerTags.value.length - 1)
      return
    }
  }
  
  function onInput() {
    // keep input reactive; suggestions are handled by watcher
  }
  
  function selectSuggestion(s) {
    addTagFromInput(s)
    nextTick(() => focusInput())
  }
  
  function onBlur() {
    // optionally commit input to tags on blur
    if (input.value.trim() && props.allowNew) addTagFromInput()
    emit('blur')
  }
  </script>
  
  <style scoped>
  /* small helper to make suggestions positioned correctly */
  div.relative > .absolute {
    left: 0.5rem;
    right: 0.5rem;
  }
  </style>
  

  <!-- const tags = ref(['Vue', 'Inertia', 'Laravel']) -->
  <!-- <TagsInput
      v-model="tags"
      label="Tags"
      placeholder="Type and press Enter"
    /> -->