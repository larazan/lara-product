<script setup>
import Navbar from '@/Pages/Front/Components/Navbar.vue';
import Sidebar from '@/Pages/Front/Components/Sidebar.vue';
import Footer from '@/Pages/Front/Components/Footer.vue';

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import ScrollToTop from '@/Components/ScrollToTop.vue';

// // initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

onMounted(() => {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
    const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
    // const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');
    const toggleSidebarMobileSearch = document.getElementById('toggleSidebarMobileSearch');

    const toggleSidebarMobile = () => {
        sidebar.classList.toggle('hidden');
        sidebarBackdrop.classList.toggle('hidden');
        // toggleSidebarMobileHamburger.classList.toggle('hidden');
        // toggleSidebarMobileClose.classList.toggle('hidden');
    };

    // check before addEventListener
    toggleSidebarMobileSearch?.addEventListener('click', toggleSidebarMobile);
    toggleSidebarMobileEl?.addEventListener('click', toggleSidebarMobile);
    sidebarBackdrop?.addEventListener('click', toggleSidebarMobile);
});

onMounted(() => {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');

    if (!themeToggleBtn) return; // safety check

    // Change the icons based on previous settings
    if (
        localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) &&
        window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        themeToggleLightIcon?.classList.remove('hidden');
    } else {
        themeToggleDarkIcon?.classList.remove('hidden');
    }

    let event = new Event('dark-mode');

    themeToggleBtn.addEventListener('click', () => {
        themeToggleDarkIcon?.classList.toggle('hidden');
        themeToggleLightIcon?.classList.toggle('hidden');

        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }

        document.dispatchEvent(event);
    });
});


</script>
<template>
    <div class="flex flex-col min-h-screen bg-white dark:bg-gray-900">
        <Navbar />
        <Sidebar />
        <div class="flex pt-14 md:pt-14 overflow-hidden min-h-screen bg-gray-50 dark:bg-gray-900">
            <div id="main-content" class="relative w-full h-full overflow-x-hidden overflow-y-auto bg-[#F6F7FB]  dark:bg-gray-900">
                <main>

                    <slot />
                </main>
            </div>
        </div>
        <ScrollToTop />
        <Footer />
    </div>
</template>