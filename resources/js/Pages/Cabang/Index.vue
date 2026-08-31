<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    cabangs: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: '' }),
    },
});

const searchQuery = ref(props.filters.search || '');
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const showDeleteModal = ref(false);
const deletingCabang = ref(null);

const form = useForm({
    kode_cabang: '',
    nama_cabang: '',
    alamat: '',
    kota: '',
    provinsi: '',
    kode_pos: '',
    latitude: '',
    longitude: '',
    penanggung_jawab: '',
    telepon: '',
    email: '',
    status: 'aktif',
    kapasitas_harian_kg: 0,
    keterangan: '',
});

const handleSearch = () => {
    router.get('/cabang', { search: searchQuery.value }, { preserveState: true, replace: true });
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    form.status = 'aktif';
    showModal.value = true;
};

const openEditModal = (cabang) => {
    isEditing.value = true;
    editingId.value = cabang.id;
    form.clearErrors();
    form.kode_cabang = cabang.kode_cabang;
    form.nama_cabang = cabang.nama_cabang;
    form.alamat = cabang.alamat;
    form.kota = cabang.kota;
    form.provinsi = cabang.provinsi || '';
    form.kode_pos = cabang.kode_pos || '';
    form.latitude = cabang.latitude !== null ? String(cabang.latitude) : '';
    form.longitude = cabang.longitude !== null ? String(cabang.longitude) : '';
    form.penanggung_jawab = cabang.penanggung_jawab;
    form.telepon = cabang.telepon;
    form.email = cabang.email || '';
    form.status = cabang.status || 'aktif';
    form.kapasitas_harian_kg = cabang.kapasitas_harian_kg || 0;
    form.keterangan = cabang.keterangan || '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const saveCabang = () => {
    if (isEditing.value) {
        form.put(`/cabang/${editingId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/cabang', {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (cabang) => {
    deletingCabang.value = cabang;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (!deletingCabang.value) return;
    router.delete(`/cabang/${deletingCabang.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            deletingCabang.value = null;
        },
    });
};

const getCurrentLocation = () => {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.latitude = position.coords.latitude.toFixed(6);
                form.longitude = position.coords.longitude.toFixed(6);
            },
            (error) => {
                alert('Gagal mengambil lokasi: ' + error.message);
            }
        );
    } else {
        alert('Browser tidak mendukung geolokasi');
    }
};

const openMapPreview = (lat, lng) => {
    if (lat && lng) {
        window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
    }
};
</script>

<template>
    <AppLayout title="Data Cabang">
        <!-- Actions & Filter Bar -->
        <div class="content-header">
            <div class="search-box">
                <svg viewBox="0 0 20 20" fill="currentColor" class="search-icon">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                <input
                    v-model="searchQuery"
                    @keyup.enter="handleSearch"
                    type="text"
                    placeholder="Cari kode, nama cabang, kota..."
                    class="search-input"
                />
                <button v-if="searchQuery" @click="searchQuery = ''; handleSearch()" class="search-clear">✕</button>
            </div>

            <button @click="openCreateModal" class="btn-primary">
                <svg viewBox="0 0 20 20" fill="currentColor" class="btn-icon">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Cabang
            </button>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div v-if="cabangs.length === 0" class="empty-state">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3>Belum ada data cabang</h3>
                <p>Mulai dengan menambahkan cabang atau sentra pengolahan gula kelapa pertama Anda.</p>
                <button @click="openCreateModal" class="btn-primary mt-3">
                    + Tambah Cabang Sekarang
                </button>
            </div>

            <div v-else class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kode & Nama Cabang</th>
                            <th>Kota & Alamat</th>
                            <th>Penanggung Jawab</th>
                            <th>Kontak</th>
                            <th>Koordinat GPS</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cabang in cabangs" :key="cabang.id">
                            <td>
                                <div class="font-bold text-slate-900">{{ cabang.nama_cabang }}</div>
                                <span class="badge-code">{{ cabang.kode_cabang }}</span>
                            </td>
                            <td>
                                <div class="text-slate-800 font-medium">{{ cabang.kota }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-xs">{{ cabang.alamat }}</div>
                            </td>
                            <td>
                                <div class="text-slate-900 font-medium">{{ cabang.penanggung_jawab }}</div>
                                <div class="text-xs text-slate-500">Kapasitas: {{ Number(cabang.kapasitas_harian_kg).toLocaleString() }} kg/hari</div>
                            </td>
                            <td>
                                <div class="text-slate-800 text-sm">{{ cabang.telepon }}</div>
                                <div v-if="cabang.email" class="text-xs text-slate-500">{{ cabang.email }}</div>
                            </td>
                            <td>
                                <div v-if="cabang.latitude && cabang.longitude">
                                    <button
                                        @click="openMapPreview(cabang.latitude, cabang.longitude)"
                                        class="btn-coords"
                                        title="Buka di Google Maps"
                                    >
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1 text-blue-600">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ cabang.latitude }}, {{ cabang.longitude }}
                                    </button>
                                </div>
                                <span v-else class="text-xs text-slate-400 italic">- Belum diset -</span>
                            </td>
                            <td>
                                <span
                                    class="status-badge"
                                    :class="cabang.status === 'aktif' ? 'status-badge--active' : 'status-badge--inactive'"
                                >
                                    {{ cabang.status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    <button @click="openEditModal(cabang)" class="btn-action btn-action--edit" title="Edit">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(cabang)" class="btn-action btn-action--delete" title="Hapus">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah / Edit Cabang -->
        <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
            <div class="modal-card">
                <div class="modal-header">
                    <h3>{{ isEditing ? 'Edit Data Cabang' : 'Tambah Cabang Baru' }}</h3>
                    <button @click="closeModal" class="btn-close">✕</button>
                </div>

                <form @submit.prevent="saveCabang" class="modal-body">
                    <!-- Row 1: Kode & Nama Cabang -->
                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label>Kode Cabang <span class="required">*</span></label>
                            <input
                                v-model="form.kode_cabang"
                                type="text"
                                placeholder="Contoh: CBG-01"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.kode_cabang }"
                            />
                            <span v-if="form.errors.kode_cabang" class="error-msg">{{ form.errors.kode_cabang }}</span>
                        </div>
                        <div class="form-group flex-2">
                            <label>Nama Cabang <span class="required">*</span></label>
                            <input
                                v-model="form.nama_cabang"
                                type="text"
                                placeholder="Contoh: Sentra Banyumas Timur"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.nama_cabang }"
                            />
                            <span v-if="form.errors.nama_cabang" class="error-msg">{{ form.errors.nama_cabang }}</span>
                        </div>
                    </div>

                    <!-- Row 2: Alamat Lengkap -->
                    <div class="form-group">
                        <label>Alamat Lengkap <span class="required">*</span></label>
                        <textarea
                            v-model="form.alamat"
                            rows="2"
                            placeholder="Jl. Kelapa Manis No. 12, Desa Karangsari"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.alamat }"
                        ></textarea>
                        <span v-if="form.errors.alamat" class="error-msg">{{ form.errors.alamat }}</span>
                    </div>

                    <!-- Row 3: Kota, Provinsi, Kode Pos -->
                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label>Kota / Kabupaten <span class="required">*</span></label>
                            <input
                                v-model="form.kota"
                                type="text"
                                placeholder="Banyumas"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.kota }"
                            />
                            <span v-if="form.errors.kota" class="error-msg">{{ form.errors.kota }}</span>
                        </div>
                        <div class="form-group flex-1">
                            <label>Provinsi</label>
                            <input
                                v-model="form.provinsi"
                                type="text"
                                placeholder="Jawa Tengah"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group flex-1">
                            <label>Kode Pos</label>
                            <input
                                v-model="form.kode_pos"
                                type="text"
                                placeholder="53123"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Row 4: Latitude & Longitude with Helpers -->
                    <div class="form-group">
                        <div class="flex justify-between items-center mb-1">
                            <label class="mb-0">Koordinat GPS Cabang</label>
                            <button
                                type="button"
                                @click="getCurrentLocation"
                                class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 cursor-pointer"
                            >
                                📍 Ambil Lokasi Saya
                            </button>
                        </div>
                        <div class="form-row">
                            <div class="flex-1">
                                <input
                                    v-model="form.latitude"
                                    type="text"
                                    placeholder="Latitude (cth: -7.431391)"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.latitude }"
                                />
                                <span v-if="form.errors.latitude" class="error-msg">{{ form.errors.latitude }}</span>
                            </div>
                            <div class="flex-1">
                                <input
                                    v-model="form.longitude"
                                    type="text"
                                    placeholder="Longitude (cth: 109.247833)"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.longitude }"
                                />
                                <span v-if="form.errors.longitude" class="error-msg">{{ form.errors.longitude }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 5: PIC & Kontak -->
                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label>Penanggung Jawab (PIC) <span class="required">*</span></label>
                            <input
                                v-model="form.penanggung_jawab"
                                type="text"
                                placeholder="Nama kepala cabang"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.penanggung_jawab }"
                            />
                            <span v-if="form.errors.penanggung_jawab" class="error-msg">{{ form.errors.penanggung_jawab }}</span>
                        </div>
                        <div class="form-group flex-1">
                            <label>Nomor Telepon / WA <span class="required">*</span></label>
                            <input
                                v-model="form.telepon"
                                type="text"
                                placeholder="08123456789"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.telepon }"
                            />
                            <span v-if="form.errors.telepon" class="error-msg">{{ form.errors.telepon }}</span>
                        </div>
                        <div class="form-group flex-1">
                            <label>Email Cabang</label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="cabang@coconutsugar.com"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.email }"
                            />
                            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                        </div>
                    </div>

                    <!-- Row 6: Kapasitas Harian & Status -->
                    <div class="form-row">
                        <div class="form-group flex-1">
                            <label>Kapasitas Harian (Kg)</label>
                            <input
                                v-model="form.kapasitas_harian_kg"
                                type="number"
                                step="0.1"
                                placeholder="0"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group flex-1">
                            <label>Status Cabang <span class="required">*</span></label>
                            <select v-model="form.status" class="form-control">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 7: Keterangan -->
                    <div class="form-group">
                        <label>Keterangan Tambahan</label>
                        <textarea
                            v-model="form.keterangan"
                            rows="2"
                            placeholder="Catatan fasilitas cabang, kapasitas penampungan, dll."
                            class="form-control"
                        ></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" @click="closeModal" class="btn-secondary">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Perbarui Cabang' : 'Simpan Cabang') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div v-if="showDeleteModal" class="modal-backdrop" @click.self="showDeleteModal = false">
            <div class="modal-card modal-card--sm">
                <div class="modal-header">
                    <h3 class="text-rose-600">Konfirmasi Hapus</h3>
                    <button @click="showDeleteModal = false" class="btn-close">✕</button>
                </div>
                <div class="modal-body">
                    <p class="text-slate-700 text-sm">
                        Apakah Anda yakin ingin menghapus cabang <strong>{{ deletingCabang?.nama_cabang }}</strong> ({{ deletingCabang?.kode_cabang }})?
                    </p>
                    <div class="modal-footer mt-4">
                        <button type="button" @click="showDeleteModal = false" class="btn-secondary">
                            Batal
                        </button>
                        <button type="button" @click="executeDelete" class="btn-danger">
                            Hapus Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-box {
    position: relative;
    width: 320px;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 12px;
    width: 18px;
    height: 18px;
    color: #94A3B8;
}

.search-input {
    width: 100%;
    padding: 0.65rem 2rem 0.65rem 2.5rem;
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.search-input:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.search-clear {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    color: #94A3B8;
    cursor: pointer;
    font-size: 0.8rem;
}

/* Buttons */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.65rem 1.25rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(30, 64, 175, 0.25);
    transition: transform 0.15s, box-shadow 0.15s;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(30, 64, 175, 0.35);
}

.btn-secondary {
    background: #F1F5F9;
    color: #475569;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.65rem 1.25rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.btn-danger {
    background: #F43F5E;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.65rem 1.25rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.btn-icon {
    width: 18px;
    height: 18px;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    text-align: left;
}

.data-table thead tr {
    background: #EFF6FF;
    border-bottom: 1px solid #DBEAFE;
}

.data-table th {
    padding: 0.85rem 1.25rem;
    font-weight: 600;
    color: #1E3A5F;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.data-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}

.data-table tbody tr:hover {
    background: #F8FAFC;
}

.badge-code {
    display: inline-block;
    background: #EFF6FF;
    color: #1E40AF;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 6px;
    margin-top: 0.2rem;
}

.btn-coords {
    display: inline-flex;
    align-items: center;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #334155;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: background 0.15s;
}

.btn-coords:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-badge--active {
    background: #ECFDF5;
    color: #10B981;
}

.status-badge--inactive {
    background: #FFF1F2;
    color: #F43F5E;
}

.action-buttons {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
}

.btn-action svg {
    width: 16px;
    height: 16px;
}

.btn-action--edit {
    background: #EFF6FF;
    color: #1E40AF;
}

.btn-action--edit:hover {
    background: #DBEAFE;
}

.btn-action--delete {
    background: #FFF1F2;
    color: #F43F5E;
}

.btn-action--delete:hover {
    background: #FFE4E6;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
    color: #64748B;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    background: #EFF6FF;
    color: #3B82F6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg { width: 32px; height: 32px; }

.empty-state h3 {
    font-size: 1.1rem;
    color: #0F172A;
    font-weight: 700;
    margin-bottom: 0.35rem;
}

.empty-state p {
    font-size: 0.875rem;
    max-width: 380px;
    margin: 0 auto;
}

/* Modal */
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

.modal-card {
    background: white;
    border-radius: 18px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-card--sm {
    max-width: 420px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #F1F5F9;
}

.modal-header h3 {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0F172A;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.1rem;
    color: #94A3B8;
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: flex;
    gap: 1rem;
}

.flex-1 { flex: 1; }
.flex-2 { flex: 2; }

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.form-group label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
}

.required {
    color: #F43F5E;
}

.form-control {
    width: 100%;
    padding: 0.65rem 0.85rem;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #0F172A;
    background: white;
    outline: none;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.form-control:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
}

.form-control.is-invalid {
    border-color: #F43F5E;
}

.error-msg {
    font-size: 0.75rem;
    color: #F43F5E;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #F1F5F9;
}
</style>
