<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import { Mathematics } from '@tiptap/extension-mathematics'
import { watch } from 'vue'

import BoldIcon from 'vue-material-design-icons/FormatBold.vue'
import ItalicIcon from 'vue-material-design-icons/FormatItalic.vue'
import UnderlineIcon from 'vue-material-design-icons/FormatUnderline.vue'
import H1Icon from 'vue-material-design-icons/FormatHeader1.vue'
import H2Icon from 'vue-material-design-icons/FormatHeader2.vue'
import ListIcon from 'vue-material-design-icons/FormatListBulleted.vue'
import OrderedListIcon from 'vue-material-design-icons/FormatListNumbered.vue'
import BlockquoteIcon from 'vue-material-design-icons/FormatQuoteClose.vue'
import CodeIcon from 'vue-material-design-icons/CodeTags.vue'
import HorizontalRuleIcon from 'vue-material-design-icons/Minus.vue'
import UndoIcon from 'vue-material-design-icons/Undo.vue'
import RedoIcon from 'vue-material-design-icons/Redo.vue'
import FunctionIcon from 'vue-material-design-icons/Function.vue'

import 'katex/dist/katex.min.css'

const props = defineProps({
    modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue,
    onUpdate: ({ editor }) => {
        // console.log(editor.getHTML())
        emit('update:modelValue', editor.getHTML())
    },
    extensions: [
        StarterKit,
        Underline,
        Mathematics.configure({
            inlineOptions: {
                onClick: (node, pos) => {
                    // you can do anything on click, e.g. open a dialog to edit the math node
                    // or just a prompt to edit the LaTeX code for a quick prototype
                    const katex = prompt('Enter new calculation:', node.attrs.latex)
                    if (katex) {
                        editor.chain().setNodeSelection(pos).updateInlineMath({ latex: katex }).focus().run()
                    }
                },
            },
            blockOptions: {
                onClick: (node, pos) => {
                    // you can do anything on click, e.g. open a dialog to edit the math node
                    // or just a prompt to edit the LaTeX code for a quick prototype
                    const katex = prompt('Enter new calculation:', node.attrs.latex)
                    if (katex) {
                        editor.chain().setNodeSelection(pos).updateBlockMath({ latex: katex }).focus().run()
                    }
                },
            },
            katexOptions: {
                // optional options for the KaTeX renderer
                throwOnError: false,
                macros: {
                    '\\R': '\\mathbb{R}',
                    '\\N': '\\mathbb{N}',
                },
            },
        }),
    ],
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    },
    editorProps: {
        attributes: {
            class:
                'border border-gray-400 px-3 py-2 min-h-[12rem] max-h-[12rem] overflow-y-auto outline-none prose max-w-none',
        },
    },
})

watch(
  () => props.modelValue,
  (value) => {
    if (!editor.value) return

    if (editor.value.getHTML() !== value) {
      editor.value.commands.setContent(value || '', false)
    }
  }
)

// 
const insertInlineMath = () => {
    const latex = prompt('Insert LaTeX:')
    if (!latex) return

    editor
        .chain()
        .focus()
        .insertInlineMath({ latex })
        .run()
}
</script>

<style>
    .tiptap-editor .ProseMirror {
        min-height: 60px;   /* change height */
        max-height: 100px;   /* optional */
        overflow-y: auto;    /* scroll */
        /* padding: 8px; */
    }
    
</style>

<template>
    <div>
        <section v-if="editor"
            class="buttons text-gray-700 flex items-center flex-wrap gap-x-1 border-t border-l border-r border-gray-400 p-1">
            <button type="button" @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('bold') }" class="p-.5">
                <BoldIcon title="Bold" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('italic') }" class="p-.5">
                <ItalicIcon title="Italic" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleUnderline().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('underline') }" class="p-.5">
                <UnderlineIcon title="Underline" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{
                'bg-gray-200 rounded': editor.isActive('heading', { level: 1 }),
            }" class="p-.5">
                <H1Icon title="H1" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{
                'bg-gray-200 rounded': editor.isActive('heading', { level: 2 }),
            }" class="p-.5">
                <H2Icon title="H2" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('bulletList') }" class="p-.5">
                <ListIcon title="Bullet List" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('orderedList') }" class="p-.5">
                <OrderedListIcon title="Ordered List" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('blockquote') }" class="p-.5">
                <BlockquoteIcon title="Blockquote" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().toggleCode().run()"
                :class="{ 'bg-gray-200 rounded': editor.isActive('code') }" class="p-.5">
                <CodeIcon title="Code" :size="17" />
            </button>
            <button @click="insertInlineMath" class="p-1 disabled:text-gray-400">
                <FunctionIcon title="Insert Math" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().setHorizontalRule().run()" class="p-.5">
                <HorizontalRuleIcon title="Horizontal Rule" :size="17" />
            </button>
            <button type="button" class="p-1 disabled:text-gray-400" @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().chain().focus().undo().run()">
                <UndoIcon title="Undo" :size="17" />
            </button>
            <button type="button" @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().chain().focus().redo().run()" class="p-1 disabled:text-gray-400">
                <RedoIcon title="Redo" :size="17" />
            </button>
        </section>
        <EditorContent :editor="editor" class="tiptap-editor" />
    </div>
</template>