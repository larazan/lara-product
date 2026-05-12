<script setup>
import Modal from '@/Components/Modal.vue';

  const props = defineProps({
    show: Boolean,
    message: {
      type: String,
      default: "Are you sure you want to delete this item?",
    },
    confirmText: {
      type: String,
      default: "Yes, I'm sure",
    },
    cancelText: {
      type: String,
      default: "No, cancel",
    },
    maxWidth: {
      type: String,
      default: "md",
    },
    closeable: {
      type: Boolean,
      default: true,
    },
  })
  
  const emit = defineEmits(["confirm", "close"])
  
  function confirm() {
    emit("confirm")
  }
  
  function handleClose() {
    emit("close")
  }
  </script>

<template>
    <Modal :show="show" :maxWidth="maxWidth" :closeable="closeable" @close="handleClose">
      <div class="relative w-full h-full">
        <div class="relative bg-white shadow dark:bg-gray-800 rounded-lg">
          <!-- Header -->
          <div class="flex justify-end p-2">
            <button
              @click="handleClose"
              type="button"
              class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                     rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                     dark:hover:bg-gray-700 dark:hover:text-white"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 
                  1 0 111.414 1.414L11.414 10l4.293 4.293a1 
                  1 0 01-1.414 1.414L10 11.414l-4.293 
                  4.293a1 1 0 01-1.414-1.414L8.586 
                  10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
  
          <!-- Body -->
          <div class="p-6 pt-0 text-center justify-center">
            <div class="flex mx-auto w-20 h-20 justify-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-20 text-red-600"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m11.25 11.25.041-.02a.75.75 0 0 1 
                     1.063.852l-.708 2.836a.75.75 0 0 0 
                     1.063.853l.041-.021M21 12a9 9 0 1 
                     1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
                />
              </svg>
            </div>
  
            <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">
              {{ message }}
            </h3>
  
            <button
              @click="confirm"
              type="button"
              class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 
                     focus:ring-red-300 font-medium rounded-lg text-base 
                     inline-flex items-center px-3 py-2.5 text-center mr-2 
                     dark:focus:ring-red-800"
            >
              {{ confirmText }}
            </button>
            <button
              @click="handleClose"
              type="button"
              class="justify-center text-gray-500 items-center bg-white 
                     hover:bg-gray-100 focus:ring-4 focus:outline-none 
                     focus:ring-primary-300 rounded-lg border border-gray-200 
                     text-sm font-medium px-5 py-2.5 hover:text-gray-900 
                     focus:z-10 dark:bg-gray-700 dark:text-gray-300 
                     dark:border-gray-500 dark:hover:text-white 
                     dark:hover:bg-gray-600 dark:focus:ring-gray-600"
            >
              {{ cancelText }}
            </button>
          </div>
        </div>
      </div>
    </Modal>
  </template>
  