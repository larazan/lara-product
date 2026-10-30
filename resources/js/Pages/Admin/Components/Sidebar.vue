<script setup>
import { onMounted, ref } from 'vue'
import { initFlowbite } from 'flowbite'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const openDropdown = ref(null)

const isActive = (href) => page.url.startsWith(href)

// 🎯 Menu Sidebar
const menu = [
  {
    label: 'Dashboard',
    icon: `<path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>`,
    href: '/admin/dashboard',
  },
  {
    label: 'Attributes',
    icon: `<path clip-rule="evenodd" fill-rule="evenodd" d="M.99 5.24A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25l.01 9.5A2.25 2.25 0 0116.76 17H3.26A2.267 2.267 0 011 14.74l-.01-9.5zm8.26 9.52v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.615c0 .414.336.75.75.75h5.373a.75.75 0 00.627-.74zm1.5 0a.75.75 0 00.627.74h5.373a.75.75 0 00.75-.75v-.615a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625zm6.75-3.63v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75zM17.5 7.5v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75z"></path>`,
    children: [
      { label: 'Categories', href: '/admin/categories' },
      { label: 'Attributes', href: '/admin/attributes' },
      { label: 'Attribute Group', href: '/admin/attribute-groups' },
      { label: 'Category Attribute', href: '/admin/category-attributes' },
    ],
  },
  {
    label: 'Products',
    icon: `<path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>`,
    children: [
      { label: 'Products', href: '/admin/products' },
      { label: 'Brands', href: '/admin/brands' },
      { label: 'Companies', href: '/admin/companies' },
    ],
  },
  {
    label: 'Articles',
    icon: `<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>`,
    children: [
      { label: 'Articles', href: '/admin/articles' },
      { label: 'Category', href: '/admin/category-articles' },
    ],
  },
  {
    label: 'Pages',
    icon: `<path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"></path>`,
    children: [
      { label: 'Faqs', href: '/admin/faqs' },
      { label: 'Settings', href: '/admin/settings' },
    ],
  },
  {
    label: 'Users',
    icon: `<path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>`,
    children: [
      { label: 'All Users', href: '/admin/users' },
      { label: 'Create User', href: '/users/create' },
      { label: 'Role', href: '/admin/roles' },
      { label: 'Permission', href: '/admin/permissions' },
    ],
  },
  {
    label: 'Advertising',
    icon: `<path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>`,
    href: '/admin/advertisings',
  },
  {
    label: 'Reports',
    icon: `<path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>`,
    href: '/admin/reports',
  },
  {
    label: 'Contacts',
    icon: `<path d="M17 6h-2V5h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2h-.541A5.965 5.965 0 0 1 14 10v4a1 1 0 1 1-2 0v-4c0-2.206-1.794-4-4-4-.075 0-.148.012-.22.028C7.686 6.022 7.596 6 7.5 6A4.505 4.505 0 0 0 3 10.5V16a1 1 0 0 0 1 1h7v3a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-3h5a1 1 0 0 0 1-1v-6c0-2.206-1.794-4-4-4Zm-9 8.5H7a1 1 0 1 1 0-2h1a1 1 0 1 1 0 2Z"/>`,
    href: '/admin/contacts',
  },
]

onMounted(() => {
  initFlowbite()
})
</script>

<template>
  <aside id="sidebar"
    class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
    aria-label="Sidebar">
    <div
      class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
      <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
        <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
          <ul class="pb-2 space-y-2">
            <!-- Mobile Search Form -->
            <li>
              <form action="#" method="GET" class="lg:hidden mb-2">
                <label for="mobile-search" class="sr-only">Search</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                    </svg>
                  </div>
                  <input type="text" name="search" id="mobile-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200"
                    placeholder="Search">
                </div>
              </form>
            </li>

            <!-- Menu Iteration -->
            <template v-for="item in menu" :key="item.label">
              <!-- Dropdown Menu -->
              <li v-if="item.children">
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group 
                    hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                  @click="openDropdown === item.label ? openDropdown = null : openDropdown = item.label">
                  <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 
                      dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20"
                    v-html="item.icon" />
                  <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ item.label }}</span>
                  <svg class="w-6 h-6 transform transition-transform duration-200"
                    :class="{ 'rotate-180': openDropdown === item.label || item.children.some(c => isActive(c.href)) }"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </button>

                <ul class="py-2 space-y-2 overflow-hidden transition-all duration-300"
                  v-show="openDropdown === item.label || item.children.some(c => isActive(c.href))">
                  <li v-for="child in item.children" :key="child.href">
                    <Link :href="child.href" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 
                        group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                      :class="{ 'bg-gray-100 dark:bg-gray-700 font-semibold text-primary-600 dark:text-white': isActive(child.href) }">
                      {{ child.label }}
                    </Link>
                  </li>
                </ul>
              </li>

              <!-- Single Link Menu -->
              <li v-else>
                <Link :href="item.href" class="flex items-center p-2 text-base text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 
                     dark:hover:bg-gray-700"
                  :class="{ 'bg-gray-100 dark:bg-gray-700 font-semibold': isActive(item.href) }">
                  <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 
                      dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" v-html="item.icon" />
                  <span class="ml-3">{{ item.label }}</span>
                </Link>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
  </aside>
  <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
</template>