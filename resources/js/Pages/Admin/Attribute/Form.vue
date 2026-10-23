<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
    locales: { type: Array, default: () => ['en', 'id'] },
    groups: { type: Array, default: () => [] },
    submitLabel: { type: String, default: 'Save' },
})

defineEmits(['submit'])

const currentLocale = ref(props.locales[0] || 'en')

const nameError = computed(() =>
    props.form.errors[`name.${currentLocale.value}`]
    || props.form.errors.name
    || ''
)

const labelError = computed(() =>
    props.form.errors[`label.${currentLocale.value}`]
    || props.form.errors.label
    || ''
)

const groupName = (group) => {
    const name = group.name

    if (typeof name === 'string') {
        try {
            const parsed = JSON.parse(name)
            return parsed[currentLocale.value]
                || parsed.en
                || Object.values(parsed)[0]
                || ''
        } catch {
            return name
        }
    }

    return name?.[currentLocale.value]
        || name?.en
        || Object.values(name || {})[0]
        || ''
}
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <!-- Locale -->
            <div>
                <label class="form-label">Locale</label>
                <select v-model="currentLocale" class="form-input">
                    <option v-for="locale in locales" :key="locale" :value="locale">
                        {{ locale.toUpperCase() }}
                    </option>
                </select>
            </div>

            <!-- Group -->
            <div>
                <label for="group_id" class="form-label">
                    Attribute Group
                </label>
                <select id="group_id" v-model="form.group_id" class="form-input">
                    <option :value="null">No group</option>
                    <option v-for="group in groups" :key="group.id" :value="group.id">
                        {{ groupName(group) }}
                    </option>
                </select>
                <p v-if="form.errors.group_id" class="form-error">
                    {{ form.errors.group_id }}
                </p>
            </div>

            <!-- Name -->
            <div>
                <label class="form-label">
                    Name ({{ currentLocale.toUpperCase() }})
                </label>
                <input v-model="form.name[currentLocale]" type="text" required maxlength="255" placeholder="e.g. Weight"
                    class="form-input" />
                <p v-if="nameError" class="form-error">
                    {{ nameError }}
                </p>
            </div>

            <!-- Label -->
            <div>
                <label class="form-label">
                    Label ({{ currentLocale.toUpperCase() }})
                </label>
                <input v-model="form.label[currentLocale]" type="text" required maxlength="255"
                    placeholder="e.g. Product weight" class="form-input" />
                <p v-if="labelError" class="form-error">
                    {{ labelError }}
                </p>
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="form-label">Slug</label>
                <input id="slug" v-model="form.slug" type="text" required maxlength="255" placeholder="e.g. weight"
                    class="form-input" />
                <p v-if="form.errors.slug" class="form-error">
                    {{ form.errors.slug }}
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Stable identifier; use lowercase letters, numbers and hyphens.
                </p>
            </div>

            <!-- Unit -->
            <div>
                <label for="unit" class="form-label">Unit</label>
                <input id="unit" v-model="form.unit" type="text" maxlength="30" placeholder="e.g. g, mm, mAh"
                    class="form-input" />
                <p v-if="form.errors.unit" class="form-error">
                    {{ form.errors.unit }}
                </p>
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="form-label">Data Type</label>
                <select id="type" v-model="form.type" class="form-input">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="boolean">Boolean</option>
                    <option value="select">Select</option>
                </select>
                <p v-if="form.errors.type" class="form-error">
                    {{ form.errors.type }}
                </p>
            </div>

            <!-- Sort order -->
            <div>
                <label for="sort_order" class="form-label">
                    Sort Order
                </label>
                <input id="sort_order" v-model.number="form.sort_order" type="number" min="0" required
                    class="form-input" />
                <p v-if="form.errors.sort_order" class="form-error">
                    {{ form.errors.sort_order }}
                </p>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="form-label">
                    Description
                </label>
                <textarea id="description" v-model="form.description" rows="3" class="form-input"
                    placeholder="Explain what this attribute represents..." />
                <p v-if="form.errors.description" class="form-error">
                    {{ form.errors.description }}
                </p>
            </div>

            <!-- SVG -->
            <div class="md:col-span-2">
                <label for="icon_svg" class="form-label">
                    SVG Icon (optional)
                </label>
                <textarea id="icon_svg" v-model="form.icon_svg" rows="4" class="form-input font-mono text-sm"
                    placeholder="<svg ...>...</svg>" />
                <p v-if="form.errors.icon_svg" class="form-error">
                    {{ form.errors.icon_svg }}
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Only accept trusted SVG markup. Sanitize SVG before rendering it.
                </p>
            </div>

            <!-- Settings -->
            <div class="space-y-4 md:col-span-2">
                <label class="flex items-center gap-2">
                    <input v-model="form.is_filterable" type="checkbox" class="rounded border-gray-300" />
                    <span class="text-sm text-gray-700 dark:text-gray-200">
                        Filterable
                    </span>
                </label>

                <label class="flex items-center gap-2">
                    <input v-model="form.is_comparable" type="checkbox" class="rounded border-gray-300" />
                    <span class="text-sm text-gray-700 dark:text-gray-200">
                        Comparable
                    </span>
                </label>

                <label class="flex items-center gap-2">
                    <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300" />
                    <span class="text-sm text-gray-700 dark:text-gray-200">
                        Active
                    </span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3 border-t pt-5 dark:border-gray-700">
            <button type="submit" :disabled="form.processing"
                class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:opacity-50">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </button>

            <button type="button" @click="$inertia.visit(route('admin.attributes.index'))"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Cancel
            </button>
        </div>
    </form>
</template>

<style scoped>
.form-label {
    @apply mb-2 block text-sm font-medium text-gray-900 dark:text-white;
}

.form-input {
    @apply block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white;
}

.form-error {
    @apply mt-1 text-sm text-red-600;
}
</style>
