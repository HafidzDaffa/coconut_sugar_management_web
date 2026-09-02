<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    roles: {
        type: Array,
        default: () => [],
    },
    branches: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({
            search: '',
            role_id: '',
            branch_id: '',
            status: '',
        }),
    },
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});

// Filters state
const searchQuery = ref(props.filters.search || '');
const selectedRole = ref(props.filters.role_id || '');
const selectedBranch = ref(props.filters.branch_id || '');
const selectedStatus = ref(props.filters.status || '');

// Modals state
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const showDetailModal = ref(false);
const selectedUser = ref(null);
const showDeleteModal = ref(false);
const deletingUser = ref(null);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

// Form
const form = useForm({
    employee_code: '',
    name: '',
    email: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    role_id: '',
    branch_id: '',
    gender: 'male',
    address: '',
    status: 'active',
});

// Metric stats computed
const totalUsers = computed(() => props.users.length);
const activeUsers = computed(() => props.users.filter(u => u.status === 'active').length);
const inactiveUsers = computed(() => props.users.filter(u => u.status !== 'active').length);
const totalRoles = computed(() => props.roles.length);

const applyFilters = () => {
    router.get('/users', {
        search: searchQuery.value,
        role_id: selectedRole.value,
        branch_id: selectedBranch.value,
        status: selectedStatus.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    searchQuery.value = '';
    selectedRole.value = '';
    selectedBranch.value = '';
    selectedStatus.value = '';
    applyFilters();
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    showPassword.value = false;
    showPasswordConfirmation.value = false;
    form.reset();
    form.clearErrors();
    form.status = 'active';
    form.gender = 'male';
    if (props.roles.length > 0) {
        form.role_id = props.roles[0].id;
    }
    showModal.value = true;
};

const openEditModal = (user) => {
    isEditing.value = true;
    editingId.value = user.id;
    showPassword.value = false;
    showPasswordConfirmation.value = false;
    form.clearErrors();
    form.employee_code = user.employee_code || '';
    form.name = user.name;
    form.email = user.email;
    form.phone_number = user.phone_number || '';
    form.password = '';
    form.password_confirmation = '';
    form.role_id = user.role_id || '';
    form.branch_id = user.branch_id || '';
    form.gender = user.gender || 'male';
    form.address = user.address || '';
    form.status = user.status || 'active';
    showModal.value = true;
};

const openDetailModal = (user) => {
    selectedUser.value = user;
    showDetailModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedUser.value = null;
};

const saveUser = () => {
    if (isEditing.value) {
        form.put(`/users/${editingId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/users', {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (user) => {
    deletingUser.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingUser.value = null;
};

const deleteUser = () => {
    if (!deletingUser.value) return;
    router.delete(`/users/${deletingUser.value.id}`, {
        onSuccess: () => closeDeleteModal(),
    });
};

const getRoleBadgeClass = (code) => {
    switch (code) {
        case 'superadmin':
            return 'role-badge--superadmin';
        case 'admin':
            return 'role-badge--admin';
        case 'qc':
            return 'role-badge--qc';
        case 'ics':
            return 'role-badge--ics';
        case 'expansi':
            return 'role-badge--expansi';
        case 'csr':
            return 'role-badge--csr';
        case 'pk':
            return 'role-badge--pk';
        case 'pb':
            return 'role-badge--pb';
        default:
            return 'role-badge--default';
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const d = new Date(dateString);
    return d.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <AppLayout title="User Management">
        <div class="user-management">
            <!-- Flash Message -->
            <div v-if="flash.success" class="alert-banner alert-banner--success">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ flash.success }}</span>
            </div>

            <div v-if="flash.error" class="alert-banner alert-banner--error">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ flash.error }}</span>
            </div>

            <!-- Page Header -->
            <div class="header-section">
                <div>
                    <h2 class="header-title">Users Directory</h2>
                    <p class="header-desc">Manage system operators, branch personnel, roles, and access control.</p>
                </div>
                <button @click="openCreateModal" class="btn-primary" id="btn-add-user">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add New User
                </button>
            </div>

            <!-- Stats Row -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon--blue">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Total Users</p>
                        <p class="stat-value">{{ totalUsers }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--green">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Active Accounts</p>
                        <p class="stat-value text-emerald">{{ activeUsers }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--amber">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Inactive / Suspended</p>
                        <p class="stat-value text-amber">{{ inactiveUsers }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--purple">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Configured Roles</p>
                        <p class="stat-value">{{ totalRoles }}</p>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="filters-card">
                <div class="filters-row">
                    <div class="search-box">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="search-icon">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="applyFilters"
                            type="text"
                            placeholder="Search by name, email, employee code, phone..."
                            class="filter-input"
                            id="input-search-user"
                        />
                    </div>

                    <select v-model="selectedRole" @change="applyFilters" class="filter-select" id="select-filter-role">
                        <option value="">All Roles</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>

                    <select v-model="selectedBranch" @change="applyFilters" class="filter-select" id="select-filter-branch">
                        <option value="">All Branches</option>
                        <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                            {{ branch.name }}
                        </option>
                    </select>

                    <select v-model="selectedStatus" @change="applyFilters" class="filter-select" id="select-filter-status">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>

                    <button @click="applyFilters" class="btn-filter-action" id="btn-apply-filter">
                        Filter
                    </button>

                    <button
                        v-if="searchQuery || selectedRole || selectedBranch || selectedStatus"
                        @click="resetFilters"
                        class="btn-filter-reset"
                        id="btn-reset-filter"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div v-if="users.length === 0" class="empty-state">
                    <div class="empty-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <p class="empty-title">No users found</p>
                    <p class="empty-desc">No accounts match the current filter criteria or none have been added yet.</p>
                    <button @click="openCreateModal" class="btn-primary" style="margin-top: 1rem;">
                        Create First User
                    </button>
                </div>

                <div v-else class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User Profile</th>
                                <th>Contact Information</th>
                                <th>Role</th>
                                <th>Assigned Branch</th>
                                <th>Status</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="u in users" :key="u.id" class="table-row">
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-badge">
                                            {{ u.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="user-cell-name">{{ u.name }}</p>
                                            <p class="user-cell-code">{{ u.employee_code || 'No Code' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-cell">
                                        <div class="contact-item">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                            <span>{{ u.email }}</span>
                                        </div>
                                        <div v-if="u.phone_number" class="contact-item text-muted">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                            </svg>
                                            <span>{{ u.phone_number }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="role-badge"
                                        :class="getRoleBadgeClass(u.role?.code)"
                                    >
                                        {{ u.role?.name || 'Unassigned' }}
                                    </span>
                                </td>
                                <td>
                                    <div v-if="u.branch" class="branch-tag">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1a1 1 0 011-1h1a1 1 0 011 1v1a1 1 0 01-1 1H8a1 1 0 01-1-1V5z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ u.branch.name }}</span>
                                    </div>
                                    <span v-else class="branch-tag branch-tag--hq">
                                        Head Office
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="status-pill"
                                        :class="`status-pill--${u.status}`"
                                    >
                                        <span class="status-dot"></span>
                                        {{ u.status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-group">
                                        <button
                                            @click="openDetailModal(u)"
                                            class="btn-icon btn-icon--view"
                                            title="View Details"
                                            :id="`btn-view-user-${u.id}`"
                                        >
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>

                                        <button
                                            @click="openEditModal(u)"
                                            class="btn-icon btn-icon--edit"
                                            title="Edit User"
                                            :id="`btn-edit-user-${u.id}`"
                                        >
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </button>

                                        <button
                                            v-if="authUser.id !== u.id"
                                            @click="confirmDelete(u)"
                                            class="btn-icon btn-icon--delete"
                                            title="Delete User"
                                            :id="`btn-delete-user-${u.id}`"
                                        >
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create / Edit User Modal -->
            <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
                <div class="modal-window">
                    <div class="modal-header">
                        <div>
                            <h3 class="modal-title">{{ isEditing ? 'Edit User Profile' : 'Create New User' }}</h3>
                            <p class="modal-subtitle">
                                {{ isEditing ? 'Update profile information and access roles.' : 'Fill in user credentials, role assignment, and contact details.' }}
                            </p>
                        </div>
                        <button @click="closeModal" class="modal-close-btn">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="saveUser" class="modal-body">
                        <div class="form-grid">
                            <!-- Employee Code -->
                            <div class="form-group">
                                <label class="form-label" for="form-employee-code">
                                    Employee Code
                                </label>
                                <input
                                    id="form-employee-code"
                                    v-model="form.employee_code"
                                    type="text"
                                    placeholder="e.g. EMP-001"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.employee_code }"
                                />
                                <span v-if="form.errors.employee_code" class="form-error">
                                    {{ form.errors.employee_code }}
                                </span>
                            </div>

                            <!-- Full Name -->
                            <div class="form-group">
                                <label class="form-label" for="form-name">
                                    Full Name <span class="required-star">*</span>
                                </label>
                                <input
                                    id="form-name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="e.g. John Doe"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    required
                                />
                                <span v-if="form.errors.name" class="form-error">
                                    {{ form.errors.name }}
                                </span>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label" for="form-email">
                                    Email Address <span class="required-star">*</span>
                                </label>
                                <input
                                    id="form-email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="user@coconutsugar.com"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.email }"
                                    required
                                />
                                <span v-if="form.errors.email" class="form-error">
                                    {{ form.errors.email }}
                                </span>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label class="form-label" for="form-phone">
                                    Phone Number
                                </label>
                                <input
                                    id="form-phone"
                                    v-model="form.phone_number"
                                    type="text"
                                    placeholder="+62 812 3456 7890"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.phone_number }"
                                />
                                <span v-if="form.errors.phone_number" class="form-error">
                                    {{ form.errors.phone_number }}
                                </span>
                            </div>

                            <!-- Role -->
                            <div class="form-group">
                                <label class="form-label" for="form-role">
                                    Role <span class="required-star">*</span>
                                </label>
                                <select
                                    id="form-role"
                                    v-model="form.role_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.role_id }"
                                    required
                                >
                                    <option value="" disabled>Select a role</option>
                                    <option v-for="r in roles" :key="r.id" :value="r.id">
                                        {{ r.name }}
                                    </option>
                                </select>
                                <span v-if="form.errors.role_id" class="form-error">
                                    {{ form.errors.role_id }}
                                </span>
                            </div>

                            <!-- Branch Assignment -->
                            <div class="form-group">
                                <label class="form-label" for="form-branch">
                                    Assigned Branch
                                </label>
                                <select
                                    id="form-branch"
                                    v-model="form.branch_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.branch_id }"
                                >
                                    <option value="">Head Office / All Branches</option>
                                    <option v-for="b in branches" :key="b.id" :value="b.id">
                                        {{ b.name }} ({{ b.branch_code }})
                                    </option>
                                </select>
                                <span v-if="form.errors.branch_id" class="form-error">
                                    {{ form.errors.branch_id }}
                                </span>
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label class="form-label" for="form-gender">
                                    Gender
                                </label>
                                <select
                                    id="form-gender"
                                    v-model="form.gender"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.gender }"
                                >
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span v-if="form.errors.gender" class="form-error">
                                    {{ form.errors.gender }}
                                </span>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label" for="form-status">
                                    Account Status <span class="required-star">*</span>
                                </label>
                                <select
                                    id="form-status"
                                    v-model="form.status"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.status }"
                                    required
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <span v-if="form.errors.status" class="form-error">
                                    {{ form.errors.status }}
                                </span>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label class="form-label" for="form-password">
                                    Password {{ isEditing ? '(Optional)' : '*' }}
                                </label>
                                <div class="password-input-wrapper">
                                    <input
                                        id="form-password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        placeholder="Min. 8 characters"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.password }"
                                        :required="!isEditing"
                                    />
                                    <button
                                        type="button"
                                        class="password-toggle-btn"
                                        @click="showPassword = !showPassword"
                                        tabindex="-1"
                                    >
                                        <svg v-if="!showPassword" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg v-else viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span v-if="form.errors.password" class="form-error">
                                    {{ form.errors.password }}
                                </span>
                            </div>

                            <!-- Retype Password -->
                            <div class="form-group">
                                <label class="form-label" for="form-password-confirmation">
                                    Retype Password {{ isEditing ? '(Optional)' : '*' }}
                                </label>
                                <div class="password-input-wrapper">
                                    <input
                                        id="form-password-confirmation"
                                        v-model="form.password_confirmation"
                                        :type="showPasswordConfirmation ? 'text' : 'password'"
                                        placeholder="Repeat password"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.password_confirmation }"
                                        :required="!isEditing && form.password.length > 0"
                                    />
                                    <button
                                        type="button"
                                        class="password-toggle-btn"
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        tabindex="-1"
                                    >
                                        <svg v-if="!showPasswordConfirmation" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg v-else viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span v-if="form.errors.password_confirmation" class="form-error">
                                    {{ form.errors.password_confirmation }}
                                </span>
                            </div>

                            <!-- Address -->
                            <div class="form-group form-group--full">
                                <label class="form-label" for="form-address">
                                    Home / Residential Address
                                </label>
                                <textarea
                                    id="form-address"
                                    v-model="form.address"
                                    rows="2"
                                    placeholder="Enter street, city, province details..."
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.address }"
                                ></textarea>
                                <span v-if="form.errors.address" class="form-error">
                                    {{ form.errors.address }}
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" @click="closeModal" class="btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary" :disabled="form.processing" id="btn-submit-user">
                                <span v-if="form.processing" class="spinner"></span>
                                {{ isEditing ? 'Save Changes' : 'Create User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detail Modal -->
            <div v-if="showDetailModal && selectedUser" class="modal-backdrop" @click.self="closeDetailModal">
                <div class="modal-window modal-window--detail">
                    <div class="modal-header">
                        <div class="detail-header-info">
                            <div class="detail-avatar">
                                {{ selectedUser.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="modal-title">{{ selectedUser.name }}</h3>
                                <p class="modal-subtitle">{{ selectedUser.employee_code || 'No Employee Code Assigned' }}</p>
                            </div>
                        </div>
                        <button @click="closeDetailModal" class="modal-close-btn">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    <div class="detail-content">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Assigned Role</span>
                                <span class="role-badge" :class="getRoleBadgeClass(selectedUser.role?.code)">
                                    {{ selectedUser.role?.name || 'Unassigned' }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Account Status</span>
                                <span class="status-pill" :class="`status-pill--${selectedUser.status}`">
                                    <span class="status-dot"></span>
                                    {{ selectedUser.status }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Assigned Branch</span>
                                <span class="detail-value">
                                    {{ selectedUser.branch ? `${selectedUser.branch.name} (${selectedUser.branch.branch_code})` : 'Head Office / All Branches' }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Gender</span>
                                <span class="detail-value text-capitalize">
                                    {{ selectedUser.gender || '-' }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Email Address</span>
                                <span class="detail-value">{{ selectedUser.email }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Phone Number</span>
                                <span class="detail-value">{{ selectedUser.phone_number || '-' }}</span>
                            </div>

                            <div class="detail-item detail-item--full">
                                <span class="detail-label">Residential Address</span>
                                <span class="detail-value">{{ selectedUser.address || 'Not specified' }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Last Login</span>
                                <span class="detail-value text-muted">{{ formatDate(selectedUser.last_login_at) }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Registered At</span>
                                <span class="detail-value text-muted">{{ formatDate(selectedUser.created_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            @click="closeDetailModal(); openEditModal(selectedUser)"
                            class="btn-primary"
                        >
                            Edit User
                        </button>
                        <button type="button" @click="closeDetailModal" class="btn-secondary">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal && deletingUser" class="modal-backdrop" @click.self="closeDeleteModal">
                <div class="modal-window modal-window--sm">
                    <div class="delete-modal-content">
                        <div class="delete-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="delete-title">Delete User Account</h3>
                        <p class="delete-desc">
                            Are you sure you want to delete <strong>{{ deletingUser.name }}</strong> ({{ deletingUser.email }})?
                            This account will be deactivated and removed from active directories.
                        </p>
                    </div>

                    <div class="modal-footer modal-footer--center">
                        <button @click="closeDeleteModal" class="btn-secondary">
                            Cancel
                        </button>
                        <button @click="deleteUser" class="btn-danger" id="btn-confirm-delete">
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.user-management {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Alert Banner */
.alert-banner {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
}

.alert-banner svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.alert-banner--success {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
}

.alert-banner--error {
    background: #FFF1F2;
    border: 1px solid #FECDD3;
    color: #9F1239;
}

/* Header */
.header-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0F172A;
    letter-spacing: -0.01em;
}

.header-desc {
    font-size: 0.85rem;
    color: #64748B;
    margin-top: 0.2rem;
}

/* Primary Button */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #1E40AF;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
    font-family: inherit;
    box-shadow: 0 1px 2px rgba(30, 64, 175, 0.2);
}

.btn-primary:hover:not(:disabled) {
    background: #1D4ED8;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary svg {
    width: 18px;
    height: 18px;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    border: 1px solid #CBD5E1;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    font-family: inherit;
}

.btn-secondary:hover {
    background: #F1F5F9;
    border-color: #94A3B8;
}

.btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #F43F5E;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    font-family: inherit;
}

.btn-danger:hover {
    background: #E11D48;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 22px;
    height: 22px;
}

.stat-icon--blue {
    background: #EFF6FF;
    color: #1E40AF;
}

.stat-icon--green {
    background: #ECFDF5;
    color: #059669;
}

.stat-icon--amber {
    background: #FFFBEB;
    color: #D97706;
}

.stat-icon--purple {
    background: #F5F3FF;
    color: #7C3AED;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
}

.text-emerald { color: #059669; }
.text-amber { color: #D97706; }
.text-muted { color: #64748B; }
.text-capitalize { text-transform: capitalize; }

/* Filters Bar */
.filters-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.filters-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 240px;
}

.search-icon {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #94A3B8;
}

.filter-input {
    width: 100%;
    padding: 0.6rem 0.875rem 0.6rem 2.4rem;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #0F172A;
    background: #F8FAFC;
    font-family: inherit;
    transition: border-color 0.15s, background 0.15s;
}

.filter-input:focus {
    outline: none;
    border-color: #3B82F6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.filter-select {
    padding: 0.6rem 2rem 0.6rem 0.875rem;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #0F172A;
    background: #F8FAFC;
    font-family: inherit;
    cursor: pointer;
    transition: border-color 0.15s;
}

.filter-select:focus {
    outline: none;
    border-color: #3B82F6;
    background: white;
}

.btn-filter-action {
    padding: 0.6rem 1rem;
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    color: #1E40AF;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s;
}

.btn-filter-action:hover {
    background: #DBEAFE;
}

.btn-filter-reset {
    padding: 0.6rem 0.875rem;
    background: transparent;
    border: 1px solid #CBD5E1;
    color: #64748B;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
}

.btn-filter-reset:hover {
    background: #F1F5F9;
    color: #0F172A;
}

/* Table Card */
.table-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    text-align: left;
}

.data-table thead tr {
    background: #EFF6FF;
    border-bottom: 1px solid #DBEAFE;
}

.data-table th {
    padding: 0.875rem 1.25rem;
    font-weight: 600;
    color: #1E3A5F;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.data-table tbody tr {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.12s;
}

.data-table tbody tr:hover {
    background: #F8FAFC;
}

.data-table td {
    padding: 1rem 1.25rem;
    color: #334155;
    vertical-align: middle;
}

/* User Cell */
.user-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-cell-name {
    font-weight: 600;
    color: #0F172A;
    font-size: 0.9rem;
}

.user-cell-code {
    font-size: 0.75rem;
    color: #64748B;
}

/* Contact Cell */
.contact-cell {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.825rem;
}

.contact-item svg {
    width: 14px;
    height: 14px;
    color: #94A3B8;
    flex-shrink: 0;
}

/* Role Badges */
.role-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    white-space: nowrap;
}

.role-badge--superadmin { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
.role-badge--admin      { background: #E0F2FE; color: #0284C7; border: 1px solid #BAE6FD; }
.role-badge--qc         { background: #CCFBF1; color: #0D9488; border: 1px solid #99F6E4; }
.role-badge--ics        { background: #EDE9FE; color: #7C3AED; border: 1px solid #DDD6FE; }
.role-badge--expansi    { background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; }
.role-badge--csr        { background: #D1FAE5; color: #059669; border: 1px solid #A7F3D0; }
.role-badge--pk         { background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; }
.role-badge--pb         { background: #FFEDD5; color: #EA580C; border: 1px solid #FED7AA; }
.role-badge--default    { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }

/* Branch Tag */
.branch-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.825rem;
    font-weight: 500;
    color: #334155;
    background: #F1F5F9;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
}

.branch-tag svg {
    width: 14px;
    height: 14px;
    color: #64748B;
}

.branch-tag--hq {
    color: #64748B;
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
}

/* Status Pill */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-pill--active {
    background: #ECFDF5;
    color: #065F46;
}
.status-pill--active .status-dot {
    background: #10B981;
}

.status-pill--inactive {
    background: #F1F5F9;
    color: #475569;
}
.status-pill--inactive .status-dot {
    background: #94A3B8;
}

.status-pill--suspended {
    background: #FFF1F2;
    color: #9F1239;
}
.status-pill--suspended .status-dot {
    background: #F43F5E;
}

/* Actions Group */
.actions-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.35rem;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    cursor: pointer;
    background: transparent;
    transition: all 0.12s;
}

.btn-icon svg {
    width: 16px;
    height: 16px;
}

.btn-icon--view {
    color: #1E40AF;
}
.btn-icon--view:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
}

.btn-icon--edit {
    color: #0D9488;
}
.btn-icon--edit:hover {
    background: #F0FDFA;
    border-color: #99F6E4;
}

.btn-icon--delete {
    color: #F43F5E;
}
.btn-icon--delete:hover {
    background: #FFF1F2;
    border-color: #FECDD3;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: #EFF6FF;
    color: #1E40AF;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.empty-icon svg {
    width: 28px;
    height: 28px;
}

.empty-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0F172A;
}

.empty-desc {
    font-size: 0.85rem;
    color: #64748B;
    max-width: 360px;
    margin-top: 0.25rem;
}

/* Modals */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1.5rem;
}

.modal-window {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modal-appear 0.15s ease-out;
}

.modal-window--sm {
    max-width: 440px;
}

.modal-window--detail {
    max-width: 580px;
}

@keyframes modal-appear {
    from { opacity: 0; transform: scale(0.97); }
    to   { opacity: 1; transform: scale(1); }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #E2E8F0;
}

.modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0F172A;
}

.modal-subtitle {
    font-size: 0.8rem;
    color: #64748B;
    margin-top: 0.15rem;
}

.modal-close-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: #F1F5F9;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.modal-close-btn:hover {
    background: #E2E8F0;
    color: #0F172A;
}

.modal-close-btn svg {
    width: 16px;
    height: 16px;
}

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.form-group--full {
    grid-column: span 2;
}

.form-label {
    font-size: 0.825rem;
    font-weight: 600;
    color: #334155;
}

.required-star {
    color: #F43F5E;
}

.form-control {
    padding: 0.6rem 0.875rem;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #0F172A;
    background: #F8FAFC;
    font-family: inherit;
    transition: border-color 0.15s, background 0.15s;
}

.form-control:focus {
    outline: none;
    border-color: #3B82F6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
}

.form-control.is-invalid {
    border-color: #F43F5E;
    background: #FFF1F2;
}

.form-error {
    font-size: 0.75rem;
    color: #F43F5E;
    font-weight: 500;
}

.password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input-wrapper input {
    padding-right: 2.5rem;
    width: 100%;
}

.password-toggle-btn {
    position: absolute;
    right: 0.5rem;
    background: transparent;
    border: none;
    color: #94A3B8;
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle-btn:hover {
    color: #334155;
}

.password-toggle-btn svg {
    width: 18px;
    height: 18px;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid #E2E8F0;
}

.modal-footer--center {
    justify-content: center;
    padding: 1.25rem 1.5rem;
}

/* Detail Modal Styling */
.detail-header-info {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.detail-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
    color: white;
    font-weight: 700;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-content {
    padding: 1.5rem;
    overflow-y: auto;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.detail-item--full {
    grid-column: span 2;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.detail-value {
    font-size: 0.9rem;
    font-weight: 500;
    color: #0F172A;
}

/* Delete Modal Styling */
.delete-modal-content {
    padding: 1.75rem 1.5rem 1rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.delete-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #FFF1F2;
    color: #F43F5E;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.delete-icon svg {
    width: 28px;
    height: 28px;
}

.delete-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0F172A;
}

.delete-desc {
    font-size: 0.85rem;
    color: #64748B;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.spinner {
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
    display: inline-block;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 640px) {
    .form-grid,
    .detail-grid {
        grid-template-columns: 1fr;
    }
    .form-group--full,
    .detail-item--full {
        grid-column: span 1;
    }
}
</style>
