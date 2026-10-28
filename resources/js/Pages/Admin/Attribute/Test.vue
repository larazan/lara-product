<script setup>
import { ref, computed, watch } from 'vue'
import { router, Head, useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import ToggleSwitch from '@/Pages/User/Components/ToggleSwitch.vue'
import ConfirmModal from '../Components/ConfirmModal.vue'
import PaginationMod from '../Components/PaginationMod.vue'
import ImageUploadMod from '../Components/ImageUploadMod.vue'
import MyEditor from '../Components/MyEditor.vue'
import { limitWords } from '@/Utils/text.js'

// ---------------------------------------------------------
// PROPS
// ---------------------------------------------------------

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
    attributes: {
        type: Object,
        default: () => ({ data: [] }),
    },
    groups: {
        type: Array,
        default: () => [],
    },
    search: {
        type: [String, Object],
        default: '',
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

const page = usePage()

// ---------------------------------------------------------
// LOCALES
// ---------------------------------------------------------

const locales = computed(() => {
    return props.locales?.length
        ? props.locales
        : ['en', 'id']
})

const currentLocale = ref(
    page.props.locale || locales.value[0] || 'en'
)

const emptyTranslations = () =>
    Object.fromEntries(
        locales.value.map(locale => [locale, ''])
    )

// ---------------------------------------------------------
// INITIAL FORM DATA
// ---------------------------------------------------------

const initialFormData = () => ({
    group_id: null,
    slug: '',
    name: emptyTranslations(),
    label: emptyTranslations(),
    icon_svg: '',
    description: '',
    unit: '',
    type: 'text',
    options: [],
    validation_rules: {
        min: null,
        max: null,
    },
    is_filterable: false,
    is_comparable: true,
    is_active: true,
    sort_order: 0,
})

// ---------------------------------------------------------
// FORM
// ---------------------------------------------------------

const formAttribute = useForm(initialFormData())

// ---------------------------------------------------------
// MODAL STATES
// ---------------------------------------------------------

const showEditModal = ref(false)
const showCreateModal = ref(false)
const showDeleteModal = ref(false)

const idAttribute = ref(null)
const idDeleteAttribute = ref(null)

// ---------------------------------------------------------
// RESET FORM
// ---------------------------------------------------------

const resetForm = () => {
    formAttribute.defaults(initialFormData())
    formAttribute.reset()
    formAttribute.clearErrors()

    currentLocale.value =
        page.props.locale || locales.value[0] || 'en'
}

// ---------------------------------------------------------
// CLOSE MODALS
// ---------------------------------------------------------

const closeModal = () => {
    showEditModal.value = false
    showCreateModal.value = false
    showDeleteModal.value = false

    idAttribute.value = null
    idDeleteAttribute.value = null

    resetForm()
}

// ---------------------------------------------------------
// TRANSLATION ERRORS
// ---------------------------------------------------------

const currentNameError = computed(() => {
    return formAttribute.errors[`name.${currentLocale.value}`]
        || formAttribute.errors.name
        || ''
})

const currentLabelError = computed(() => {
    return formAttribute.errors[`label.${currentLocale.value}`]
        || formAttribute.errors.label
        || ''
})

// ---------------------------------------------------------
// GROUP NAME
// ---------------------------------------------------------

const groupName = (group) => {
    if (!group?.name) return ''

    let translations = group.name

    if (typeof translations === 'string') {
        try {
            translations = JSON.parse(translations)
        } catch {
            return translations
        }
    }

    if (typeof translations === 'object') {
        return translations[currentLocale.value]
            || translations[locales.value[0]]
            || Object.values(translations)[0]
            || ''
    }

    return translations
}

// ---------------------------------------------------------
// SELECT OPTIONS
// ---------------------------------------------------------

const addOption = () => {
    formAttribute.options.push({
        value: '',
        label: emptyTranslations(),
    })
}

const removeOption = (index) => {
    if (formAttribute.options.length > 1) {
        formAttribute.options.splice(index, 1)
    }
}

// ---------------------------------------------------------
// OPEN CREATE MODAL
// ---------------------------------------------------------

const openAddModal = () => {
    resetForm()

    // Select input types start without options.
    // Admin can add options when choosing select/multiselect.
    showCreateModal.value = true
}

// ---------------------------------------------------------
// CREATE ATTRIBUTE
// ---------------------------------------------------------

const addAttribute = () => {
    formAttribute
        .transform(data => ({
            ...data,
            group_id: data.group_id || null,
        }))
        .post('/admin/attribute/create', {
            preserveScroll: true,

            onSuccess: (response) => {
                const message = response.props.flash?.success

                if (message) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: message,
                    })
                }

                closeModal()
            },
        })
}

// ---------------------------------------------------------
// OPEN EDIT MODAL
// ---------------------------------------------------------

const openEditModal = (attribute) => {
    idAttribute.value = attribute.id

    const payload = {
        group_id: attribute.group_id ?? null,
        slug: attribute.slug ?? '',

        name: Object.fromEntries(
            locales.value.map(locale => [
                locale,
                attribute.name?.[locale] ?? '',
            ])
        ),

        label: Object.fromEntries(
            locales.value.map(locale => [
                locale,
                attribute.label?.[locale] ?? '',
            ])
        ),

        icon_svg: attribute.icon_svg ?? '',
        description: attribute.description ?? '',
        unit: attribute.unit ?? '',
        type: attribute.type ?? 'text',

        options: Array.isArray(attribute.options)
            ? attribute.options.map(option => ({
                value: option.value ?? '',
                label: Object.fromEntries(
                    locales.value.map(locale => [
                        locale,
                        option.label?.[locale] ?? '',
                    ])
                ),
            }))
            : [],

        validation_rules: {
            min: attribute.validation_rules?.min ?? null,
            max: attribute.validation_rules?.max ?? null,
        },

        is_filterable: Boolean(attribute.is_filterable),
        is_comparable: attribute.is_comparable ?? true,
        is_active: attribute.is_active ?? true,
        sort_order: attribute.sort_order ?? 0,
    }

    // Set the selected attribute as the new form defaults.
    formAttribute.defaults(payload)
    formAttribute.reset()
    formAttribute.clearErrors()

    currentLocale.value =
        page.props.locale || locales.value[0] || 'en'

    showCreateModal.value = false
    showEditModal.value = true
}

// ---------------------------------------------------------
// UPDATE ATTRIBUTE
// ---------------------------------------------------------

const updateAttribute = () => {
    if (!idAttribute.value) return

    formAttribute
        .transform(data => ({
            ...data,
            group_id: data.group_id || null,
            _method: 'put',
        }))
        .post('/admin/attribute/update/' + idAttribute.value, {
            preserveScroll: true,

            onSuccess: (response) => {
                const message = response.props.flash?.success

                if (message) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: message,
                    })
                }

                closeModal()
            },
        })
}

// ---------------------------------------------------------
// OPEN DELETE MODAL
// ---------------------------------------------------------

const openDeleteModal = (id) => {
    idDeleteAttribute.value = id
    showDeleteModal.value = true
}

// ---------------------------------------------------------
// DELETE ATTRIBUTE
// ---------------------------------------------------------

const deleteAttribute = () => {
    if (!idDeleteAttribute.value) return

    router.delete(
        '/admin/attribute/delete/' + idDeleteAttribute.value,
        {
            preserveScroll: true,

            onSuccess: (response) => {
                const message = response.props.flash?.success

                if (message) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: message,
                    })
                }

                closeModal()
            },
        }
    )
}

// ---------------------------------------------------------
// SEARCH
// ---------------------------------------------------------

const search = ref(
    typeof props.search === 'string'
        ? props.search
        : props.search?.q ?? ''
)

watch(search, (value) => {
    router.get(
        '/admin/attributes',
        { q: value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
})

// ---------------------------------------------------------
// SELECT ALL
// ---------------------------------------------------------

const selected = ref([])

const allSelected = computed(() => {
    const rows = props.attributes?.data ?? []

    return rows.length > 0
        && rows.every(row => selected.value.includes(row.id))
})

const toggleAll = (event) => {
    const rows = props.attributes?.data ?? []

    selected.value = event.target.checked
        ? rows.map(row => row.id)
        : []
}

// ---------------------------------------------------------
// BULK DELETE
// ---------------------------------------------------------

const deleteSelected = () => {
    if (!selected.value.length) return

    if (!window.confirm(`Delete ${selected.value.length} items?`)) {
        return
    }

    router.delete(route('admin.attributes.bulk-delete'), {
        data: {
            ids: selected.value,
        },
        preserveScroll: true,

        onSuccess: () => {
            selected.value = []
        },
    })
}
</script>
