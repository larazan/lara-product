<script setup>
import { watch, onBeforeUnmount } from 'vue'
import { Editor, useEditor, EditorContent } from '@tiptap/vue-3'
import { Mathematics } from '@tiptap/extension-mathematics'
import StarterKit from '@tiptap/starter-kit'

import BoldIcon from 'vue-material-design-icons/FormatBold.vue'
import ItalicIcon from 'vue-material-design-icons/FormatItalic.vue'
import ListIcon from 'vue-material-design-icons/FormatListBulleted.vue'
import OrderedListIcon from 'vue-material-design-icons/FormatListNumbered.vue'
import FunctionIcon from 'vue-material-design-icons/Function.vue'

import 'katex/dist/katex.min.css'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])

const editor = new Editor({
    content: props.modelValue,

    extensions: [
        StarterKit.configure({
            heading: false,
            blockquote: false,
            codeBlock: false,
            horizontalRule: false
        }),
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

    editorProps: {
        attributes: {
            class: 'min-h-[42px] px-3 py-2 text-sm outline-none'
        }
    },

    onUpdate({ editor }) {
        emit('update:modelValue', editor.getHTML())
    }
})

watch(
    () => props.modelValue,
    (value) => {
        if (value !== editor.getHTML()) {
            editor.commands.setContent(value || '', false)
        }
    }
)

const insertInlineMath = () => {
    const latex = prompt('Insert LaTeX:')
    if (!latex) return

    editor
        .chain()
        .focus()
        .insertInlineMath({ latex })
        .run()
}

onBeforeUnmount(() => {
    editor.destroy()
})
</script>

<template>
<div class="w-full border rounded bg-white">

    <!-- toolbar -->
    <div class="border-b px-2 py-1 md:py-1 flex gap-1 bg-gray-50">

        <!--  -->
        <button type="button" @click="editor.chain().focus().toggleBold().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('bold') }" class="p-1">
            <BoldIcon title="Bold" :size="15" />
        </button>
        <button type="button" @click="editor.chain().focus().toggleItalic().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('italic') }" class="p-1">
            <ItalicIcon title="Italic" :size="15" />
        </button>
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('bulletList') }" class="p-1">
            <ListIcon title="Bullet List" :size="15" />
        </button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('orderedList') }" class="p-1">
            <OrderedListIcon title="Ordered List" :size="15" />
        </button>
        <button @click="insertInlineMath" class="p-1 disabled:text-gray-400">
            <FunctionIcon title="Insert Math" :size="15" />
        </button>

    </div>

    <!-- content -->
    <EditorContent :editor="editor" />

</div>
</template>