<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmModal from '../Components/ConfirmModal.vue';
import PaginationMod from '../Components/PaginationMod.vue';
import { ref, reactive, watch, computed } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { formatUS } from '@/Utils/text';
import ShowModal from './ShowModal.vue';

const props = defineProps({
  page: Number,
  permissions: Object,   // paginated permissions (permissions.data)
  search: Object,
});

// form state for create/edit
const formPermission = reactive({
  name: '',
  guard_name: 'web',
});

// local UI state
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showModal = ref(false)
const selectedPermission = ref(null)

const openModal = (permission) => {
    selectedPermission.value = permission
    showModal.value = true
}

const isEdit = ref(false);
const currentId = ref(null);
const idDeletePermission = ref(null);

const resetForm = () => {
  formPermission.name = '';
  formPermission.guard_name = 'web';
};

// Open modals
const openAddModal = () => {
  resetForm();
  isEdit.value = false;
  showCreateModal.value = true;
};

const openEditModal = (permission) => {
  resetForm();
  isEdit.value = true;
  currentId.value = permission.id;
  formPermission.name = permission.name;
  formPermission.guard_name = permission.guard_name ?? 'web';
  showEditModal.value = true;
};

const openDeleteModal = (id) => {
  idDeletePermission.value = id;
  showDeleteModal.value = true;
};

const closeModal = () => {
  showCreateModal.value = false;
  showEditModal.value = false;
  showDeleteModal.value = false;
  showModal.value = false
};

// CRUD actions
const addPermission = () => {
  // POST /admin/permissions/store
  router.post('/admin/permissions/store', formPermission, {
    onSuccess: () => {
      closeModal();
      Swal.fire({ icon: 'success', title: 'Permission created', timer: 1400, showConfirmButton: false });
    }
  });
};

const updatePermission = () => {
  // PUT /admin/permissions/update/:id
  router.put('/admin/permissions/update/' + currentId.value, formPermission, {
    onSuccess: () => {
      closeModal();
      Swal.fire({ icon: 'success', title: 'Permission updated', timer: 1400, showConfirmButton: false });
    }
  });
};

const deletePermission = () => {
  router.delete('/admin/permissions/delete/' + idDeletePermission.value, {}, {
    onSuccess: () => {
      closeModal();
      Swal.fire({ toast: true, icon: 'success', position: 'top-end', title: 'Deleted', showConfirmButton: false, timer: 1500 });
    }
  });
};

// Search and pagination (same pattern you used in categories)
const localSearch = ref(props.search ?? '');
watch(localSearch, (value) => {
  router.get('/admin/permissions', { q: value }, { preserveState: true, replace: true });
});


// computed: flattened permission list from props.permissions (for inline role/user assignment)
const flattenedPermissions = computed(() => props.permissions?.data || []);

</script>

<template>
  <AdminLayout>
    <Head><title>Permissions</title></Head>

    <!-- Top bar -->
    <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
      <div class="w-full mb-1">
        <div class="mb-4">
          <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Permissions</h1>
        </div>

        <div class="sm:flex">
          <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 dark:divide-gray-700">
            <div class="lg:pr-3">
              <label class="sr-only">Search</label>
              <div class="relative mt-1 lg:w-64 xl:w-96">
                <input v-model="localSearch" type="text" placeholder="Search permissions" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
              </div>
            </div>
          </div>

          <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
            <button @click="openAddModal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800">
              Add Permission
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="flex flex-col p-4">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
          <div class="overflow-hidden shadow">
            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="p-4">
                    <div class="flex items-center">
                      <input id="checkbox-all" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50" />
                      <label for="checkbox-all" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Name</th>
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Date</th>
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>

              <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <tr v-for="(permission, index) in flattenedPermissions" :key="index" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                  <td class="w-4 p-4">
                    <div class="flex items-center">
                      <input type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50" />
                      <div class="px-2 text-sm font-semibold text-gray-600">
                        {{ ((props.permissions.current_page - 1) * props.permissions.per_page) + index + 1 }}
                      </div>
                    </div>
                  </td>

                  <td class="max-w-sm p-4 overflow-hidden text-base font-medium text-gray-800 truncate dark:text-gray-400">
                    {{ permission.name }}
                  </td>

                  <td class="p-4 text-sm text-gray-700 whitespace-nowrap dark:text-white">
                    {{ formatUS(permission.created_at ?? '-') }}
                  </td>

                  <td class="p-4 flex items-center space-x-2 whitespace-nowrap">
                    <button @click="openModal(permission)" type="button"
                    class="inline-flex items-center px-2 md:px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 md:mr-2">
  <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
</svg>
                        <span class="hidden md:block">Show</span>
                    </button>
                    <!-- Quick assign roles: opens edit modal for that permission -->
                    <button @click="openEditModal(permission)" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800">
                      Edit
                    </button>

                    <button @click="openDeleteModal(permission.id)" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <PaginationMod :meta="props.permissions" :query="{ search: localSearch }" :limit="props.page" />

    <!-- Create / Edit Modal -->
    <Modal :show="showCreateModal || showEditModal" maxWidth="xl">
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
          <h3 class="text-xl font-semibold dark:text-white">{{ isEdit ? 'Edit Permission' : 'Add Permission' }}</h3>
          <button @click="closeModal" type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="currentColor" view-box="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
          </button>
        </div>

        <form @submit.prevent="isEdit ? updatePermission() : addPermission()">
          <div class="p-6 space-y-6">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permission Name</label>
                <input v-model="formPermission.name" type="text" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="create-post" required />
              </div>
            </div>

          </div>

          <div class="flex gap-2 items-center justify-end p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
            <button @click="closeModal" type="button" class="text-gray-500 bg-white border border-gray-200 rounded-lg px-5 py-2.5">Cancel</button>
            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 rounded-lg px-5 py-2.5">Save</button>
          </div>
        </form>
      </div>
    </Modal>

    <ShowModal
       v-model:show="showModal"
        :permission="selectedPermission"
    />

    <!-- Delete Confirmation -->
    <ConfirmModal
      :show="showDeleteModal"
      message="Are you sure you want to delete this permission?"
      @confirm="deletePermission"
      @close="closeModal"
    />
  </AdminLayout>
</template>
