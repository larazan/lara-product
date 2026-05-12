<template>
    <transition name="fade">
      <button
        v-if="visible"
        @click="scrollTop"
        class="fixed p-2 md:p-3 z-20 rounded-full bg-[#1972f5] bottom-4 right-3 hover:opacity-90 md:bottom-5 md:right-5 cursor-pointer"
      >
      <svg aria-hidden="true" focusable="false" data-prefix="fas" class="h-5 w-5 text-white" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
      <path fill="currentColor" d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"></path>
    </svg>
      </button>
    </transition>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from "vue";
  import { router } from "@inertiajs/vue3";
  
  const visible = ref(false);
  const threshold = 300; // px before showing button
  
  // show button only when scrolling down
  const handleScroll = () => {
    visible.value = window.scrollY > threshold;
  };
  
  onMounted(() => {
    window.addEventListener("scroll", handleScroll);
  
    // scroll to top after inertia navigation
    router.on("success", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  });
  
  onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
  });
  
  // on button click
  const scrollTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };
  </script>
  
  <style>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  </style>
  