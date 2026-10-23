<script setup>
import Modal from "@/Components/Modal.vue";
import NewModal from "@/Components/NewModal.vue";
import MyEditor from "@/Pages/Admin/Components/MyEditor.vue";
import OptionEditor from "@/Pages/Admin/Components/OptionEditor.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import ImageUploadMod from "@/Pages/Admin/Components/ImageUploadMod.vue";
import SimpleToggle from "./SimpleToggle.vue";
import ImageAnswerUploader from "./ImageAnswerUploader.vue";
import QuestionTypeDropdown from "./QuestionTypeDropdown.vue";

const props = defineProps({
    modelValue: Boolean,
    question: Object,
    quiz: Object,
    types: Object,
});

const emit = defineEmits(["update:modelValue"]);

const difficulties = [
    { label: 'Easy', value: 'easy' },
    { label: 'Medium', value: 'medium' },
    { label: 'Hard', value: 'hard' },
];


// For Create or Edit
const form = useForm({
    id: null,
    // quiz_id: props.quiz.id,
    question_text: "",
    type: null,
    points: 0,
    time_limit_seconds: 10,
    difficulty: null,
    explanation: null,
    hint: null,
    image: null,
    old_image_url: null,
    //
    scoring_enabled: false,
    correct_point: 1,
    wrong_point: 0,

    // MCQ only
    options: [
        { label: "A", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
        { label: "B", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
    ],

    // True/False
    correct_tf: "true",

    // Input text
    correct_text: "",
});

const resetForm = () => {
    form.id = null;
    form.question_text = "";
    form.type = null;
    form.points = 0;
    form.time_limit_seconds = 10;
    form.difficulty = null;
    form.image = null;
    form.old_image_url = null;
    form.explanation = null;
    form.hint = null;

    form.options = [
        { label: "A", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
        { label: "B", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
    ];

    form.correct_text = "";

    // Reset tab kembali ke 'question'
    activeTab.value = 'question';
}

// Load when editing
watch(
    () => props.question,
    (q) => {
        if (!q) {
            form.reset();
            return;
        }

        form.id = q.id;
        form.question_text = q.question_text;
        form.type = q.type.id;
        form.points = q.points;
        form.time_limit_seconds = q.time_limit_seconds;
        form.difficulty = q.difficulty;
        form.explanation = q.explanation;
        form.hint = q.hint;
        //
        form.scoring_enabled = q.scoring_enabled,
            form.correct_point = q.correct_point,
            form.wrong_point = q.wrong_point,
            //
            form.options = q.options?.length
                ? q.options.map(o => ({
                    id: o.id,
                    label: o.label,
                    text: o.text,
                    is_correct: Boolean(o.is_correct),
                }))
                : [
                    { label: "A", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
                    { label: "B", text: "", point: "", is_correct: false, image_path: null, dimension_value: "" },
                ];

        if (q.type === "true_false") {
            form.correct_tf = q.correct_tf ? "true" : "false";
        }

        if (q.type === "input_text") {
            form.correct_text = q.correct_text;
        }
    },
    { immediate: true }
);

// Validation Errors
const hasErrors = computed(() => Object.keys(form.errors).length > 0);

// Add/remove options
function addOption() {
    form.options.push({
        label: "",
        text: "",
        is_correct: false,
    });
}

function removeOption(index) {
    form.options.splice(index, 1);
}

function normalizeForm() {
    form.question_text = form.question_text ?? '';
    form.type = form.type ?? '';
    form.points = Number(form.points) || 0;
    form.time_limit_seconds = Number(form.time_limit_seconds) || 10;
}

function selectCorrectOption(selectedIndex) {
    form.options.forEach((op, index) => {
        op.is_correct = index === selectedIndex;
    });
}

// console.log(form);

// validate
const localErrors = ref({})

function validateForm() {
    localErrors.value = {}

    if (!form.question_text || form.question_text.trim() === '') {
        localErrors.value.question_text = 'Question text is required.'
    }

    if (!form.type) {
        localErrors.value.type = 'Question type is required.'
    }

    if (form.points < 0) {
        localErrors.value.points = 'Points must be 0 or greater.'
    }

    // MULTIPLE CHOICE VALIDATION
    if (form.type === 2) {
        if (!form.options.length) {
            localErrors.value.options = 'At least one option is required.'
        }

        const hasCorrect = form.options.some(op => op.is_correct)

        if (!hasCorrect) {
            localErrors.value.correct = 'Select at least one correct answer.'
        }

        form.options.forEach((op, index) => {
            if (!op.text || op.text.trim() === '') {
                localErrors.value[`option_${index}`] =
                    `Option ${index + 1} text is required.`
            }
        })
    }

    // TRUE / FALSE VALIDATION
    if (form.type === 4) {
        if (!form.correct_tf) {
            localErrors.value.correct_tf = 'Please select True or False.'
        }
    }

    // INPUT TEXT VALIDATION
    if (form.type === 1) {
        if (!form.correct_text || form.correct_text.trim() === '') {
            localErrors.value.correct_text = 'Correct answer is required.'
        }
    }

    // CLICKABLE VALIDATION
    if (form.type === 3) {
        if (!form.options.length) {
            localErrors.value.options = 'At least one option is required.'
        }

        const hasCorrect = form.options.some(op => op.is_correct)

        if (!hasCorrect) {
            localErrors.value.correct = 'Select at least one correct answer.'
        }

        const hasImage = form.options.some(op => op.image_path)

        if (!hasImage) {
            localErrors.value.image_path = 'Upload image is required.'
        }

    }

    // SURVEY VALIDATION
    if (form.type === 5) {
        if (!form.options.length) {
            localErrors.value.options = 'At least one option is required.'
        }

        form.options.forEach((op, index) => {
            if (!op.text || op.text.trim() === '') {
                localErrors.value[`option_${index}`] =
                    `Option ${index + 1} text is required.`
            }
        })
    }

    return Object.keys(localErrors.value).length === 0
}

// Submit
function submit() {
    normalizeForm();

    if (!validateForm()) return

    if (form.id) {
        form.put(route('questions.update', {
            quiz: props.quiz.data.slug,
            question: form.id
        }), {
            preserveScroll: true,
            forceFormData: true, // 👈 REQUIRED
            onSuccess: close,
        });
        // form.put(`/questions/${form.id}`, {
        //     preserveScroll: true,
        //     onSuccess: close,
        // });
    } else {
        form.post(`/quizzes/${props.quiz.data.slug}/questions`, {
            preserveScroll: true,
            forceFormData: true, // 👈 REQUIRED
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                close();
                resetForm();
            }
        });
    }
}

function close() {
    activeTab.value = 'question'; // Reset tab saat modal ditutup
    emit("update:modelValue", false);
}

function submitAndContinue() {
    if (form.id) return; // only for CREATE mode

    normalizeForm()

    if (!validateForm()) return

    form.post(`/quizzes/${props.quiz.data.slug}/questions`, {
        preserveScroll: true,
        forceFormData: true, // 👈 REQUIRED
        preserveState: true, // VERY IMPORTANT
        onSuccess: (page) => {
            showNotification(page.props.flash.success)
            resetForm()
        }
    });
}

// tabs
const activeTab = ref('question')

const tabs = [
    { key: 'question', label: 'Question' },
    { key: 'explanation', label: 'Explanation' },
    { key: 'hint', label: 'Hint' },
]

const notification = ref(null)

function showNotification(message) {
    notification.value = message
    setTimeout(() => {
        notification.value = null
    }, 3000)
}
</script>


<template>
    <NewModal :modelValue="modelValue" @update:modelValue="close" maxWidth="2xl">
        <div class="p-3 md:p-6 w-full max-w-2xl">

            <!-- Title -->
            <div class="flex justify-between mb-1 items-center">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ form.id ? "Edit Question" : "Add Question" }}
                    </h2>
                </div>
                <button @click="close">
                    <span class="flex justify-center p-1 rounded-lg text-gray-600 bg-gray-50 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Errors -->
            <div v-if="hasErrors" class="mb-2 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    <li v-for="(v, k) in form.errors" :key="k">
                        {{ v }}
                    </li>
                </ul>
            </div>

            <!-- validate error -->
            <div v-if="Object.keys(localErrors).length" class="mb-2 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    <li v-for="(v, k) in localErrors" :key="k">
                        {{ v }}
                    </li>
                </ul>
            </div>

            <!-- notification -->
            <div v-if="notification"
                class="mb-3 p-3 bg-green-100 border border-green-300 text-green-700 rounded text-sm">
                {{ notification }}
            </div>

            <!-- Tabs -->
            <div class="flex flex-col pt-2 gap-2  w-full">
                <!-- Tabs header -->
                <div
                    class="bg-gray-100 text-gray-600 inline-flex h-9 items-center border border-gray-200 rounded-lg p-1 w-full shadow-sm">
                    <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                        class="inline-flex w-full items-center justify-center rounded-md px-2 py-1 text-sm font-medium transition"
                        :class="activeTab === tab.key
                            ? 'bg-white text-gray-800  shadow'
                            : 'opacity-70 hover:opacity-100'">
                        {{ tab.label }}
                    </button>
                </div>

                <!-- Tab content -->
                <div class="mt-2">
                    <div v-if="activeTab === 'question'">
                        <div>
                            <div class="mb-4 flex items-center gap-2 w-4/6 md:w-1/2">
                                <label class="block mb-1 text-sm text-gray-900 dark:text-white">{{
                                    $t('dashboard.fields.type') }}</label>
                                <!-- <select v-model="form.type"
                                    class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option disable value="">Select type</option>
                                    <option v-for="type in props.types" :key="type.id" :value="type.id">{{ type.name }}</option>
                                </select> -->
                                <QuestionTypeDropdown v-model="form.type" :types="props.types" />
                            </div>
                            <div class="mb-4 flex gap-2 w-full">

                                <div class="w-4/12 md:w-2/6 col-span-6 sm:col-span-3">
                                    <label for="difficulty" class="block mb-1 text-sm text-gray-900 dark:text-white">{{
                                        $t('dashboard.fields.difficulty') }}</label>
                                    <select v-model="form.difficulty" id="difficulty"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disable value="">Select difficulty</option>
                                        <option v-for="d in difficulties" :key="d.value" :value="d.value">
                                            {{ d.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="w-4/12 md:w-1/6">
                                    <label class="block mb-1 text-sm text-gray-900 dark:text-white">{{
                                        $t('dashboard.fields.points') }}</label>
                                    <input v-model="form.points" type="number"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                </div>
                                <div class="w-4/12 md:w-1/6">
                                    <label class="block mb-1 text-sm text-gray-900 dark:text-white">{{
                                        $t('dashboard.fields.time') }}</label>
                                    <input v-model="form.time_limit_seconds" type="number"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                </div>
                            </div>

                            <!-- Question Text -->
                            <div class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">{{ $t('dashboard.fields.question_text')
                                    }}</label>
                                <MyEditor v-model="form.question_text" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <ImageUploadMod v-model="form.image" label="Image" :error="form.errors?.image"
                                    :preview-url="form.old_image_url" />
                            </div>

                            <!-- toogle -->
                            <div class="col-span-6 flex py-3 justify-end">
                                <div class="flex items-center py-2 space-x-1">
                                    <div class="flex flex-col flex-grow">
                                        <div class="text-sm font-normal text-gray-500 dark:text-gray-400">{{
                                            $t('dashboard.scoring.enable_correct_only') }}</div>
                                    </div>
                                    <SimpleToggle v-model="form.scoring_enabled" />
                                </div>
                            </div>
                            <!-- scoring -->
                            <div v-if="form.scoring_enabled" class="flex  pb-4 space-x-3">
                                <div class="w-6/12">
                                    <label class="block mb-1 text-sm text-gray-700 dark:text-white">{{
                                        $t('dashboard.scoring.points_correct') }}</label>
                                    <input v-model="form.correct_point" type="number"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                </div>
                                <div class="w-6/12">
                                    <label class="block mb-1 text-sm text-gray-700 dark:text-white">{{
                                        $t('dashboard.scoring.points_incorrect') }}</label>
                                    <input v-model="form.wrong_point" type="number"
                                        class="bg-white border border-gray-400 text-gray-900 text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                </div>
                            </div>

                            <!-- Options for MULTIPLE CHOICE only -->
                            <div v-if="form.type === 2" class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">{{ $t('dashboard.fields.options')
                                    }}</label>

                                <div v-for="(op, index) in form.options" :key="index"
                                    class="flex gap-2 mb-3 items-center">
                                    <input v-model="op.label" placeholder="Label (A,B,C...)"
                                        class="hidden w-12 border rounded text-sm text-slate-800 px-2 py-2 " />

                                    <!-- <input v-model="op.text" placeholder="Option text"
                                        class="flex-1 border rounded text-sm text-slate-800 px-2 py-2 placeholder:text-gray-500 focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" /> -->

                                    <OptionEditor v-model="op.text" class="flex-1" />

                                    <input v-model="op.point" type="number"
                                        class="w-12 border border-gray-500 rounded text-sm text-slate-800 px-2 py-2 focus:ring-primary-500 focus:border-primary-500" />

                                    <div class="flex items-center space-x-1">
                                        <!-- <input type="checkbox" v-model="op.is_correct" class="" /> -->
                                        <input type="checkbox" :checked="op.is_correct"
                                            @change="selectCorrectOption(index)" />
                                        <span class="text-xs text-gray-600">Correct</span>
                                    </div>

                                    <button @click="removeOption(index)" class="text-red-500 hover:underline text-sm"
                                        v-if="form.options.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-5">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <button @click="addOption"
                                    class="px-3 py-1 bg-green-500 rounded border border-green-600 text-white hover:bg-green-600 text-sm">
                                    + Add Option
                                </button>
                            </div>

                            <!-- Options for Clickable CHOICE only -->
                            <div v-if="form.type === 3" class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">{{ $t('dashboard.fields.options')
                                    }}</label>

                                <div v-for="(op, index) in form.options" :key="index"
                                    class="flex gap-2 mb-3 items-center">

                                    <ImageAnswerUploader v-model="op.image_path" label="Gambar Pilihan"
                                        :error="form.errors.image_path" />

                                    <input v-model="op.text" placeholder="Option text"
                                        class="flex-1 border rounded text-sm text-slate-800 px-2 py-2 placeholder:text-gray-500 focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />

                                    <input v-model="op.point" type="number"
                                        class="w-12 border border-gray-500 rounded text-sm text-slate-800 px-2 py-2 focus:ring-primary-500 focus:border-primary-500" />

                                    <div class="flex items-center space-x-1">
                                        <!-- <input type="checkbox" v-model="op.is_correct" class="" /> -->
                                        <input type="checkbox" :checked="op.is_correct"
                                            @change="selectCorrectOption(index)" />
                                        <span class="text-xs text-gray-600">Correct</span>
                                    </div>

                                    <button @click="removeOption(index)" class="text-red-500 hover:underline text-sm"
                                        v-if="form.options.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-5">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <button @click="addOption"
                                    class="px-3 py-1 bg-green-500 rounded border border-green-600 text-white hover:bg-green-600 text-sm">
                                    + Add Option
                                </button>
                            </div>

                            <!-- TRUE FALSE OPTIONS -->
                            <div v-if="form.type === 4" class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">Correct Answer</label>

                                <select v-model="form.correct_tf"
                                    class="w-full border text-sm text-slate-800 rounded px-2 py-1">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>

                            <!-- Input Text Answer -->
                            <div v-if="form.type === 1" class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">Correct Answer</label>
                                <input v-model="form.correct_text" type="text"
                                    class="w-full border text-sm text-slate-800 rounded px-2 py-1" />
                            </div>

                            <!-- Survey -->
                            <div v-if="form.type === 5" class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">Survey Option</label>

                                <div v-for="(op, index) in form.options" :key="index"
                                    class="flex gap-2 mb-3 items-center">
                                    <input v-model="op.label" placeholder="Label (A,B,C...)"
                                        class="hidden w-12 border rounded text-sm text-slate-800 px-2 py-2 " />

                                    <div class="flex-1 flex-col space-y-0">
                                        <span class="text-xs text-gray-500">Option text</span>
                                        <input v-model="op.text" placeholder="Option text"
                                            class=" border rounded text-sm text-slate-800 px-2 py-2 placeholder:text-gray-500 focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                    </div>

                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs text-gray-500">Point</span>
                                        <input v-model="op.point" type="number"
                                            class="w-12 rounded border border-gray-500  text-sm text-slate-800 px-2 py-2 " />
                                    </div>

                                    <!-- dimension -->
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs text-gray-500">Dimension</span>
                                        <input v-model="op.dimension_value" type="text"
                                            class="w-20  rounded border border-gray-500 text-sm text-slate-800 px-2 py-2 " />
                                    </div>

                                    <button @click="removeOption(index)"
                                        class="mt-4 text-red-500 hover:underline text-sm"
                                        v-if="form.options.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-5">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <button @click="addOption"
                                    class="px-3 py-1 bg-green-500 rounded border border-green-600 text-white hover:bg-green-600 text-sm">
                                    + Add Option
                                </button>
                            </div>

                        </div>
                    </div>

                    <div v-if="activeTab === 'explanation'">
                        <div>
                            <div class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">{{ $t('dashboard.fields.explanation')
                                    }}</label>
                                <MyEditor v-model="form.explanation" />
                            </div>
                        </div>
                    </div>

                    <div v-if="activeTab === 'hint'">
                        <div>
                            <div class="mb-4">
                                <label class="block mb-1 text-sm text-slate-800">{{ $t('dashboard.fields.hint')
                                    }}</label>
                                <textarea type="text" v-model="form.hint" id="hint" rows="4"
                                    class="shadow-sm bg-white border border-gray-300 text-gray-900 sm:text-sm rounded focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Type -->


            <!-- Buttons -->
            <div class="w-full flex justify-between md:justify-end gap-1 md:gap-2 mt-3 md:mt-6 mb-2">
                <button @click="close"
                    class="flex px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg">
                    Cancel
                </button>

                <button v-if="!form.id" @click="submitAndContinue"
                    class="flex px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Save & Create Another
                </button>

                <button @click="submit"
                    class="flex px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    {{ form.id ? "Update" : "Create" }}
                </button>
            </div>
        </div>
    </NewModal>
</template>
