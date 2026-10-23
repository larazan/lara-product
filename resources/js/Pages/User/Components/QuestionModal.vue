<script setup>
import Modal from "@/Components/Modal.vue";
import MyEditor from "@/Pages/Admin/Components/MyEditor.vue";
import ImageUploadMod from "@/Pages/Admin/Components/ImageUploadMod.vue";
import { useForm } from "@inertiajs/vue3";
import { watch, computed } from "vue";

const props = defineProps({
    show: Boolean,
    question: Object, // REQUIRED
    quiz: Object,
    types: Object,
});

const emit = defineEmits(["close"]);

const difficulties = [
    { label: "Easy", value: "easy" },
    { label: "Medium", value: "medium" },
    { label: "Hard", value: "hard" },
];

const form = useForm({
    id: null,
    question_text: "",
    type: null,
    points: 0,
    time_limit_seconds: 0,
    difficulty: null,
    image: null,
    old_image_url: null,

    options: [],
    correct_tf: "true",
    correct_text: "",
});

// Populate form when modal opens
watch(
    () => props.question,
    (q) => {
        if (!q) return;

        form.id = q.id;
        form.question_text = q.question_text;
        form.type = q.type.id;
        form.points = q.points;
        form.time_limit_seconds = q.time_limit_seconds;
        form.difficulty = q.difficulty;
        form.old_image_url = q.image_url ?? null;

        form.options = q.options?.length
            ? q.options.map(o => ({
                id: o.id,
                label: o.label,
                text: o.text,
                point: o.point ?? 0,
                is_correct: Boolean(o.is_correct),
            }))
            : [];

        form.correct_tf = q.correct_tf ? "true" : "false";
        form.correct_text = q.correct_text ?? "";
    },
    { immediate: true }
);

const hasErrors = computed(() => Object.keys(form.errors).length > 0);

function addOption() {
    form.options.push({
        label: "",
        text: "",
        point: 0,
        is_correct: false,
    });
}

function removeOption(index) {
    form.options.splice(index, 1);
}

function submit() {
    form.put(
        route("questions.update", {
            quiz: props.quiz.data.slug,
            question: form.id,
        }),
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: "Question updated",
                });
                emit("close");
            },
        }
    );
}
</script>

<template>
    <Modal :show="show" @close="$emit('close')" maxWidth="xl">
        <div class="p-6 w-full max-w-2xl">

            <h2 class="text-lg font-semibold mb-4">Edit Question</h2>

            <!-- Errors -->
            <div v-if="hasErrors" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    <li v-for="(v, k) in form.errors" :key="k">{{ v }}</li>
                </ul>
            </div>

            <!-- Type / Difficulty / Points / Time -->
            <!-- (UNCHANGED FROM YOUR ORIGINAL TEMPLATE) -->

            <!-- Question Text -->
            <div class="mb-4">
                <label class="block mb-1 text-sm">Question Text</label>
                <MyEditor v-model="form.question_text" />
            </div>

            <!-- Image -->
            <ImageUploadMod
                v-model="form.image"
                label="Image"
                :preview-url="form.old_image_url"
                :error="form.errors?.image"
            />

            <!-- MULTIPLE CHOICE -->
            <div v-if="form.type === 2" class="mb-4">
                <label class="block mb-1 text-sm">Options</label>

                <div
                    v-for="(op, index) in form.options"
                    :key="index"
                    class="flex gap-2 mb-2 items-center"
                >
                    <input v-model="op.label" class="w-12 border rounded px-2 py-1" />
                    <input v-model="op.text" class="flex-1 border rounded px-2 py-1" />
                    <input v-model="op.point" type="number" class="w-16 border rounded px-2 py-1" />
                    <input type="checkbox" v-model="op.is_correct" />
                    <button @click="removeOption(index)" class="text-red-500">✕</button>
                </div>

                <button @click="addOption" class="text-sm text-green-600">
                    + Add option
                </button>
            </div>

            <!-- TRUE / FALSE -->
            <div v-if="form.type === 4">
                <select v-model="form.correct_tf">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            </div>

            <!-- INPUT TEXT -->
            <div v-if="form.type === 5">
                <input v-model="form.correct_text" type="text" class="w-full border rounded px-2 py-1" />
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 mt-6">
                <button @click="$emit('close')" class="px-3 py-2 bg-gray-200 rounded">
                    Cancel
                </button>

                <button
                    @click="submit"
                    class="px-3 py-2 bg-indigo-600 text-white rounded"
                >
                    Update
                </button>
            </div>

        </div>
    </Modal>
</template>
