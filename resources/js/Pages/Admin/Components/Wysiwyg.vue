<script setup>
import { EditorContent, useEditor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { Placeholder } from '@tiptap/extensions'
import { Mathematics } from '@tiptap/extension-mathematics'
import 'katex/dist/katex.min.css'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Placeholder.configure({
      placeholder: 'Write something...',
    }),
    Mathematics.configure({
      inlineOptions: {
        // optional options for the inline math node
      },
      blockOptions: {
        // optional options for the block math node
      },
      katexOptions: {
        // optional options for the KaTeX renderer
      },
    }),
  ],
  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML())
  },
})
</script>

<template>
  <div class="border rounded-lg p-3">
    <!-- Toolbar -->
    <div class="flex gap-2 mb-2 border-b pb-2">
      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'bg-gray-200': editor.isActive('bold') }"
      >
        Bold
      </button>

      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'bg-gray-200': editor.isActive('italic') }"
      >
        Italic
      </button>

      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().setParagraph().run()"
        :class="{ 'bg-gray-200': editor.isActive('paragraph') }"
      >
        P
      </button>

      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'bg-gray-200': editor.isActive('heading', { level: 2 }) }"
      >
        H2
      </button>

      <!-- Insert Inline Math -->
      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().insertMathInline().run()"
      >
        Inline Math
      </button>

      <!-- Insert Block Math -->
      <button
        class="px-2 py-1 rounded border"
        @click="editor.chain().focus().insertMathBlock().run()"
      >
        Block Math
      </button>
    </div>

    <!-- Content -->
    <EditorContent :editor="editor" class="min-h-[200px]" />
  </div>
</template>
