<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmModal from '../Components/ConfirmModal.vue';
import PaginationMod from '../Components/PaginationMod.vue';
import { ref, reactive, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { formatUS } from '@/Utils/text';
import RoleShowModal from './RoleShowModal.vue';
import EditRoleModal from './EditRoleModal.vue';
import AddRoleModal from './AddRoleModal.vue';

const props = defineProps({
  roles: Object,
  allPermissions: Array,
  search: String,
  page: Number,
  groups: Object,
  users: Object,
});

const formRole = reactive({
  name: '',
  roles: [],
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);

const showModal = ref(false)
const showAddModal = ref(false)
const showEditRoleModal = ref(false)
const selectedRole = ref(null)

const openModal = (role) => {
  selectedRole.value = role
  showModal.value = true
}

const openEdit = (role) => {
  selectedRole.value = role
  showEditRoleModal.value = true
}

const idRole = ref('');
const idDeleteRole = ref('');

const resetForm = () => {
  formRole.name = '';
  formRole.roles = [];
};

const openAddModal = () => {
  resetForm();
  showCreateModal.value = true;
};

const addRole = () => {
  router.post('/admin/roles', formRole);
  resetForm();
  closeModal();
};

const openEditModal = (role) => {
  formRole.name = role.name;
  formRole.roles = role.roles.map((p) => p.id);
  idRole.value = role.id;
  showEditModal.value = true;
};

const updateRole = () => {
  router.put(`/admin/roles/${idRole.value}`, formRole);
  closeModal();
};

const openDeleteModal = (id) => {
  idDeleteRole.value = id;
  showDeleteModal.value = true;
};

const deleteRole = () => {
  router.delete(`/admin/roles/${idDeleteRole.value}`);
  closeModal();
};

const closeModal = () => {
  showCreateModal.value = false;
  showEditModal.value = false;
  showDeleteModal.value = false;
};

const search = ref(props.search ?? '');
watch(search, (value) => {
  router.get('/admin/roles', { q: value }, { preserveState: true, replace: true });
});

const pageTo = (url) => {
  router.get(url, search.value ? { q: search.value } : {}, { preserveState: true });
};
</script>

<template>
  <AdminLayout>

    <Head>
      <title>Roles</title>
    </Head>

    <!-- <pre>{{ props.groups }}</pre>
    <pre>{{ props.users }}</pre> -->

    <!-- Top bar -->
    <div
      class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
      <div class="w-full mb-1">
        <div class="mb-4">
          <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Roles</h1>
        </div>

        <div class="sm:flex">
          <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 dark:divide-gray-700">
            <div class="lg:pr-3">
              <label class="sr-only">Search</label>
              <div class="relative mt-1 lg:w-64 xl:w-96">
                <input v-model="localSearch" type="text" placeholder="Search roles"
                  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
              </div>
            </div>
          </div>

          <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
            <button @click="showAddModal = true"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800">
              Add Role
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
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Role</th>
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Permission</th>
                  <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>

              <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <tr v-for="(role, index) in props.roles.data" :key="index"
                  class="hover:bg-gray-100 dark:hover:bg-gray-700">
                  <td class="w-4 p-4">
                    <div class="flex items-center">
                      <input type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50" />
                      <div class="px-2 text-sm font-semibold text-gray-600">
                        {{ ((props.roles.current_page - 1) * props.roles.per_page) + index + 1 }}
                      </div>
                    </div>
                  </td>

                  <td
                    class="max-w-sm p-4 overflow-hidden text-base font-medium text-gray-800 truncate dark:text-gray-400">
                    {{ role.name }}
                  </td>
                  <td class="p-4 text-sm text-gray-700 whitespace-nowrap dark:text-white">
                    <div class="flex flex-wrap">
                      <span v-for="(permission, id) in role.permissions" :key="id"
                        class="px-2 py-1 mr-1 my-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                        {{ permission }}
                      </span>
                    </div>
                  </td>

                  <td class="p-4 flex items-center space-x-2 whitespace-nowrap">
                    <button @click="openModal(role)" type="button"
                      class="inline-flex items-center px-2 md:px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="size-4 md:mr-2">
                        <path fill-rule="evenodd"
                          d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                          clip-rule="evenodd" />
                      </svg>
                      <span class="hidden md:block">Show</span>
                    </button>
                    <!-- edit -->
                    <button @click="openEdit(role)" type="button"
                      class="inline-flex items-center px-3 py-2 gap-1 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800">
                      <svg class="w-4 h-4 md:mr-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                        </path>
                        <path fill-rule="evenodd"
                          d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                          clip-rule="evenodd"></path>
                      </svg>
                      <span class="block">Edit</span>
                    </button>
                    <!-- Quick assign roles: opens edit modal for that role -->
                    <!-- <button @click="openEditModal(role)" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800">
                      Edit
                    </button> -->

                    <button @click="openDeleteModal(role.id)"
                      class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
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

    <!-- <div class="p-4">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="p-4 text-left text-xs font-medium">Role</th>
            <th class="p-4 text-left text-xs font-medium">Permissions</th>
            <th class="p-4 text-left text-xs font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in props.roles.data" :key="role.id" class="border-b dark:border-gray-700">
            <td class="p-4 font-bold">{{ role.name }}</td>
            <td class="p-4">
              <span v-for="perm in role.roles" :key="perm.id" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded mr-1">{{ perm.name }}</span>
            </td>
            <td class="p-4 space-x-2">
              <button @click="openEditModal(role)" class="px-3 py-1 text-sm text-white bg-primary-700 rounded">Edit</button>
              <button @click="openDeleteModal(role.id)" class="px-3 py-1 text-sm text-white bg-red-700 rounded">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div> -->

    <PaginationMod :meta="props.roles" :query="{ search }" :limit="props.page" />

    <!-- Create Role Modal -->
    <Modal :show="showCreateModal" maxWidth="xl">
      <div class="bg-white dark:bg-gray-800 p-6 rounded">
        <h2 class="text-xl mb-4 dark:text-white">Add Role</h2>
        <form @submit.prevent="addRole">
          <label class="block mb-2 dark:text-white">Name</label>
          <input v-model="formRole.name" class="w-full p-2 border rounded mb-4 dark:bg-gray-700 dark:text-white" />

          <label class="block mb-2 dark:text-white">Permissions</label>
          <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto">
            <div v-for="perm in props.roles.data" :key="perm.id" class="flex items-center space-x-2">
              <input type="checkbox" :value="perm.id" v-model="formRole.roles" />
              <span class="dark:text-white">{{ perm.name }}</span>
            </div>
          </div>

          <div class="flex justify-end space-x-2 mt-6">
            <button type="button" @click="closeModal" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 text-white bg-primary-700 rounded">Save</button>
          </div>
        </form>
      </div>
    </Modal>

    <!-- Edit Role Modal -->
    <Modal :show="showEditModal" maxWidth="xl">
      <div class="bg-white dark:bg-gray-800 p-6 rounded">
        <h2 class="text-xl mb-4 dark:text-white">Edit Role</h2>
        <form @submit.prevent="updateRole">
          <label class="block mb-2 dark:text-white">Name</label>
          <input v-model="formRole.name" class="w-full p-2 border rounded mb-4 dark:bg-gray-700 dark:text-white" />

          <label class="block mb-2 dark:text-white">Permissions</label>
          <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto">
            <div v-for="perm in props.roles.data" :key="perm.id" class="flex items-center space-x-2">
              <input type="checkbox" :value="perm.id" v-model="formRole.roles" />
              <span class="dark:text-white">{{ perm.name }}</span>
            </div>
          </div>

          <div class="flex justify-end space-x-2 mt-6">
            <button type="button" @click="closeModal" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 text-white bg-primary-700 rounded">Update</button>
          </div>
        </form>
      </div>
    </Modal>

    <AddRoleModal v-model:show="showAddModal" :allPermissions="allPermissions" />

    <RoleShowModal v-model:show="showModal" :role="selectedRole" />

    <EditRoleModal v-model:show="showEditRoleModal" :role="selectedRole" :allPermissions="allPermissions" />

    <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this role?" @confirm="deleteRole"
      @close="closeModal" />
  </AdminLayout>
</template>
