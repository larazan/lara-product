<script setup>
import { computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    ingredient: {
        type: Object,
        default: null,
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
});

const isEdit = computed(() => !!props.ingredient);

const form = useForm({
    name: {
        en: props.ingredient?.name?.en ?? '',
        id: props.ingredient?.name?.id ?? '',
    },
    inci_name: props.ingredient?.inci_name ?? '',
    safety_rating: props.ingredient?.safety_rating ?? '',
    description: {
        en: props.ingredient?.description?.en ?? '',
        id: props.ingredient?.description?.id ?? '',
    },
});

const submit = () => {
    if (isEdit.value) {
        form.put(route('admin.ingredients.update', props.ingredient.id));
    } else {
        form.post(route('admin.ingredients.store'));
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">

        <!-- Name -->
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-4 text-base font-semibold text-gray-800">
                Ingredient Name
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="locale in locales" :key="locale">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Name ({{ locale.toUpperCase() }})
                        <span class="text-red-500">*</span>
                    </label>

                    <input v-model="form.name[locale]" type="text" :placeholder="`Ingredient name (${locale})`" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:border-indigo-500 focus:outline-none focus:ring-1
                               focus:ring-indigo-500" />

                    <p v-if="form.errors[`name.${locale}`]" class="mt-1 text-xs text-red-500">
                        {{ form.errors[`name.${locale}`] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- INCI and Safety Rating -->
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-4 text-base font-semibold text-gray-800">
                Ingredient Information
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        INCI Name
                    </label>

                    <input v-model="form.inci_name" type="text" placeholder="Example: Niacinamide" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:border-indigo-500 focus:outline-none focus:ring-1
                               focus:ring-indigo-500" />

                    <p class="mt-1 text-xs text-gray-500">
                        International standardized ingredient name.
                    </p>

                    <p v-if="form.errors.inci_name" class="mt-1 text-xs text-red-500">
                        {{ form.errors.inci_name }}
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Safety Rating
                    </label>

                    <select v-model="form.safety_rating" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:border-indigo-500 focus:outline-none focus:ring-1
                               focus:ring-indigo-500">
                        <option value="">Not rated</option>
                        <option v-for="rating in 5" :key="rating" :value="rating">
                            {{ rating }} / 5
                        </option>
                    </select>

                    <p class="mt-1 text-xs text-gray-500">
                        Optional rating scale from 1 to 5.
                    </p>

                    <p v-if="form.errors.safety_rating" class="mt-1 text-xs text-red-500">
                        {{ form.errors.safety_rating }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-4 text-base font-semibold text-gray-800">
                Description
            </h3>

            <div class="space-y-4">
                <div v-for="locale in locales" :key="locale">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Description ({{ locale.toUpperCase() }})
                    </label>

                    <textarea v-model="form.description[locale]" rows="4" :placeholder="`Description (${locale})`"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:border-indigo-500 focus:outline-none focus:ring-1
                               focus:ring-indigo-500"></textarea>

                    <p v-if="form.errors[`description.${locale}`]" class="mt-1 text-xs text-red-500">
                        {{ form.errors[`description.${locale}`] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <Link :href="route('admin.ingredients.index')" class="rounded-lg border border-gray-300 px-4 py-2 text-sm
                       font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </Link>

            <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium
                       text-white hover:bg-indigo-700 disabled:cursor-not-allowed
                       disabled:opacity-50">
                {{ form.processing
                    ? 'Saving...'
                    : isEdit
                        ? 'Update Ingredient'
                        : 'Create Ingredient'
                }}
            </button>
        </div>

    </form>
</template>



<!-- <IngredientForm :locales="locales" /> -->
