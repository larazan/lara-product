<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from "vue";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    modelValue: [String, Number, Object, null],
    valueKey: {
        type: String,
        default: "id",
    },
    placeholder: {
        type: String,
        default: "Select item",
    },
    searchPlaceholder: {
        type: String,
        default: "Search...",
    },
});

const emit = defineEmits(["update:modelValue"]);

const dropdownOpen = ref(false);
const dropdownRef = ref(null);
const search = ref("");
const searchInput = ref(null);
const highlightedIndex = ref(-1); // Indeks untuk navigasi keyboard

const selectedValue = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const selectedItem = computed(() => {
    if (selectedValue.value === null || selectedValue.value === undefined) return null;
    return props.items.find(
        (item) => (typeof item === "object" ? item[props.valueKey] : item) == selectedValue.value
    );
});

const filteredItems = computed(() => {
    if (!search.value) return props.items;
    const query = search.value.toLowerCase();

    return props.items.filter((item) => {
        if (typeof item === "object" && item !== null) {
            return Object.values(item).some((val) =>
                String(val).toLowerCase().includes(query)
            );
        }
        return String(item).toLowerCase().includes(query);
    });
});

// Reset indeks sorotan saat daftar hasil filter berubah
watch(filteredItems, () => {
    highlightedIndex.value = -1;
});

const selectItem = (value) => {
    selectedValue.value = value;
    dropdownOpen.value = false;
    search.value = "";
    highlightedIndex.value = -1;
};

const toggleDropdown = async () => {
    dropdownOpen.value = !dropdownOpen.value;
    if (dropdownOpen.value) {
        await nextTick();
        searchInput.value?.focus();
    } else {
        search.value = "";
        highlightedIndex.value = -1;
    }
};

// Penanganan Navigasi Keyboard
const handleKeyDown = (event) => {
    if (!dropdownOpen.value) {
        if (event.key === "Enter" || event.key === "ArrowDown" || event.key === " ") {
            event.preventDefault();
            toggleDropdown();
        }
        return;
    }

    // Total opsi: 1 opsi reset (null) + jumlah filteredItems
    const totalOptions = filteredItems.value.length + 1;

    switch (event.key) {
        case "ArrowDown":
            event.preventDefault();
            highlightedIndex.value = (highlightedIndex.value + 1) % totalOptions;
            break;
        case "ArrowUp":
            event.preventDefault();
            highlightedIndex.value = (highlightedIndex.value - 1 + totalOptions) % totalOptions;
            break;
        case "Enter":
            event.preventDefault();
            if (highlightedIndex.value === 0) {
                selectItem(null); // Pilihan pertama adalah reset (null)
            } else if (highlightedIndex.value > 0) {
                const item = filteredItems.value[highlightedIndex.value - 1];
                const val = typeof item === "object" ? item[props.valueKey] : item;
                selectItem(val);
            }
            break;
        case "Escape":
            event.preventDefault();
            dropdownOpen.value = false;
            search.value = "";
            highlightedIndex.value = -1;
            break;
    }
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
        search.value = "";
        highlightedIndex.value = -1;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div ref="dropdownRef" class="relative w-full">
        <!-- Tombol Utama Dropdown -->
        <button type="button" @click="toggleDropdown" @keydown="handleKeyDown"
            class="w-full flex items-center justify-between bg-white border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            <span class="truncate">
                <slot name="selection" :item="selectedItem">
                    {{ selectedItem ? (typeof selectedItem === 'object' ? selectedItem.name || selectedItem.username ||
                        selectedItem[valueKey] : selectedItem) : placeholder }}
                </slot>
            </span>

            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Menu Dropdown -->
        <div v-if="dropdownOpen" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg">
            <div class="p-2 border-b">
                <input ref="searchInput" v-model="search" @keydown="handleKeyDown" type="text"
                    :placeholder="searchPlaceholder"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <ul class="max-h-64 overflow-y-auto">
                <!-- Opsi Reset / Kosong (Index 0) -->
                <li @click="selectItem(null)" @mouseenter="highlightedIndex = 0"
                    class="px-4 py-2 cursor-pointer text-gray-500 text-sm transition-colors"
                    :class="{ 'bg-gray-100': highlightedIndex === 0 }">
                    {{ placeholder }}
                </li>

                <!-- Looping Item (Index 1 dst) -->
                <li v-for="(item, index) in filteredItems" :key="typeof item === 'object' ? item[valueKey] : item"
                    @click="selectItem(typeof item === 'object' ? item[valueKey] : item)"
                    @mouseenter="highlightedIndex = index + 1" class="px-4 py-2 cursor-pointer transition-colors"
                    :class="{
                        'bg-blue-50 font-medium': selectedValue == (typeof item === 'object' ? item[valueKey] : item),
                        'bg-gray-100': highlightedIndex === index + 1
                    }">
                    <slot name="item" :item="item">
                        <div class="text-sm font-semibold">
                            {{ typeof item === 'object' ? item.name || item.username : item }}
                        </div>
                    </slot>
                </li>

                <!-- Jika Data Kosong -->
                <li v-if="filteredItems.length === 0" class="px-4 py-3 text-center text-gray-400 text-sm">
                    No items found
                </li>
            </ul>
        </div>
    </div>
</template>

<!-- <SearchDropdown 
    v-model="selectedProductId" 
    :items="productsList" 
    value-key="id"
    placeholder="Choose a product..."
    search-placeholder="Search products..."
>
    <template #item="{ item }">
        <div class="text-sm font-semibold text-green-700">{{ item.name }}</div>
        <div class="text-[10px] text-gray-500">Price: {{ item.price }}</div>
    </template>
</SearchDropdown> -->