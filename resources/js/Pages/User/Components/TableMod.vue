<template>
    <FrontLayout>
        <div class="px-6 py-8">
            <h1 class="text-3xl font-semibold mb-6">
                Questions for: {{ quiz.title }}
            </h1>

            <!-- Add Question Button -->
            <button
                @click="openModal()"
                class="px-4 py-2 mb-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow"
            >
                Add Question
            </button>

            <!-- Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-gray-700">#</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Question</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Type</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Options</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(q, index) in questions"
                            :key="q.id"
                            class="border-b hover:bg-gray-50 transition"
                        >
                            <!-- Index -->
                            <td class="px-4 py-3 text-gray-600">
                                {{ index + 1 }}
                            </td>

                            <!-- Question Text -->
                            <td class="px-4 py-3 font-medium">
                                {{ q.question_text }}
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3 capitalize">
                                {{ q.type.replace('_', ' ') }}
                            </td>

                            <!-- Options -->
                            <td class="px-4 py-3">
                                <template v-if="q.options.length">
                                    <ul class="list-disc pl-5 space-y-1 text-gray-700">
                                        <li
                                            v-for="op in q.options"
                                            :key="op.id"
                                            class="flex items-center gap-2"
                                        >
                                            <span>{{ op.label ? op.label + '.' : '' }} {{ op.text }}</span>

                                            <span
                                                :class="op.is_correct
                                                    ? 'text-green-600 font-bold'
                                                    : 'text-red-500'"
                                            >
                                                ({{ op.is_correct ? 'Correct' : 'Wrong' }})
                                            </span>
                                        </li>
                                    </ul>
                                </template>

                                <span v-else class="text-gray-400 italic">
                                    no options
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right flex gap-2 justify-end">
                                <!-- Edit -->
                                <button
                                    @click="openModal(q)"
                                    class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow text-xs"
                                >
                                    Edit
                                </button>

                                <!-- Delete -->
                                <Link
                                    as="button"
                                    method="delete"
                                    :href="`/questions/${q.id}`"
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded shadow text-xs"
                                >
                                    Delete
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <!-- <QuestionModal
                v-if="showModal"
                :show="showModal"
                :question="editData"
                :quiz="quiz"
                @close="showModal = false"
            /> -->
        </div>
    </FrontLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
// import QuestionModal from "@/Components/QuestionModal.vue";

const props = defineProps({
    quiz: Object,
    questions: Array,
});

const showModal = ref(false);
const editData = ref(null);

function openModal(question = null) {
    editData.value = question;
    showModal.value = true;
}
</script>
