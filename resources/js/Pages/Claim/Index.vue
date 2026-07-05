<script setup>
import { usePage, useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { computed, inject, ref } from 'vue';
import Button from 'primevue/button';

defineProps({ claims: Array });

const route = inject('route');
const page = usePage();
const currentUser = computed(() => page.props.value.auth.user);

const showCreateForm = ref(false);
const activeDetailClaim = ref(null);

const createForm = useForm({
    nama_lengkap: '', alamat: '', no_telepon: '', email_kontak: '',
    tempat_lahir: '', tanggal_lahir: '', perencanaan_hidup: 'Kesehatan',
    nominal_asuransi: 0, tinggi_badan: 0, berat_badan: 0,
    masalah_kesehatan: '', total_asuransi_jiwa: 0
});

const statusForm = useForm({ target_status: '', note: '' });

// Form helper khusus untuk aksi hapus data
const deleteForm = useForm({});

const submitNewClaim = () => {
    createForm.post(route('claims.store'), {
        onSuccess: () => {
            showCreateForm.value = false;
            createForm.reset();
            alert('Formulir asuransi berhasil dibuat!');
        }
    });
};

const handleUpdateStatus = (claimId, nextStatus) => {
    statusForm.target_status = nextStatus;
    statusForm.note = `Diproses oleh ${currentUser.value.name} (${currentUser.value.role})`;

    statusForm.patch(route('claims.updateStatus', claimId), {
        preserveScroll: true,
        onSuccess: () => {
            activeDetailClaim.value = null;
            alert('Status berhasil diperbarui!');
        }
    });
};

// Fungsi menghapus draft formulir
const handleDeleteClaim = (claimId) => {
    if (confirm('Apakah Anda yakin ingin menghapus draft formulir ini secara permanen?')) {
        deleteForm.delete(route('claims.destroy', claimId), {
            preserveScroll: true,
            onSuccess: () => {
                alert('Draft formulir berhasil dihapus!');
            }
        });
    }
};
</script>

<template>
    <Head title="Panel Klaim Asuransi" />

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            <div class="bg-white p-4 rounded-lg shadow-sm border mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Sistem Pengajuan & Approval Asuransi</h1>
                    <p class="text-xs text-gray-500">Aplikasi Pengelolaan Klaim Berbasis Peran (RBAC)</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">{{ currentUser.name }}</p>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 uppercase">Role: {{ currentUser.role }}</span>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="text-xs bg-red-50 text-red-600 px-3 py-2 rounded border border-red-200">Logout</Link>
                </div>
            </div>

            <div v-if="currentUser.role === 'user' && !showCreateForm" class="mb-6">
                <Button label="➕ Ajukan Formulir Asuransi Baru" severity="success" @click="showCreateForm = true" />
            </div>

            <div v-if="showCreateForm" class="bg-white p-6 rounded-lg shadow border mb-6">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Formulir Pengajuan Asuransi Baru</h2>
                    <Button label="Batal" severity="secondary" size="small" @click="showCreateForm = false" />
                </div>
                
                <form @submit.prevent="submitNewClaim" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Nama Lengkap</label>
                        <input type="text" v-model="createForm.nama_lengkap" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">No. Telepon</label>
                        <input type="text" v-model="createForm.no_telepon" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Email Kontak</label>
                        <input type="email" v-model="createForm.email_kontak" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Tempat Lahir</label>
                        <input type="text" v-model="createForm.tempat_lahir" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Tanggal Lahir</label>
                        <input type="date" v-model="createForm.tanggal_lahir" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Perencanaan Hidup (Tipe)</label>
                        <select v-model="createForm.perencanaan_hidup" class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900">
                            <option>Kesehatan</option>
                            <option>Jiwa</option>
                            <option>Kendaraan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Nominal Asuransi (Rp)</label>
                        <input type="number" v-model="createForm.nominal_asuransi" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Total Asuransi Jiwa (Rp)</label>
                        <input type="number" v-model="createForm.total_asuransi_jiwa" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Tinggi Badan (cm)</label>
                        <input type="number" v-model="createForm.tinggi_badan" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Berat Badan (kg)</label>
                        <input type="number" v-model="createForm.berat_badan" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600">Alamat Lengkap</label>
                        <textarea v-model="createForm.alamat" required class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" rows="2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600">Jelaskan Masalah Kesehatan yang Dimiliki</label>
                        <textarea v-model="createForm.masalah_kesehatan" class="mt-1 w-full text-sm rounded border-gray-300 text-gray-900" rows="2" placeholder="Tulis '-' jika tidak ada"></textarea>
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <Button type="submit" label="Simpan sebagai Draft" severity="success" class="w-full" :loading="createForm.processing" />
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">Nama Pemohon</th>
                            <th class="p-4 text-left">Tipe Plan</th>
                            <th class="p-4 text-left">Nominal</th>
                            <th class="p-4 text-left">Status</th>
                            <th class="p-4 text-center">Aksi Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="claim in claims" :key="claim.id">
                            <td class="p-4 font-bold">#{{ claim.id }}</td>
                            <td class="p-4">{{ claim.nama_lengkap }}</td>
                            <td class="p-4"><span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-semibold">{{ claim.perencanaan_hidup }}</span></td>
                            <td class="p-4 font-semibold">Rp {{ Number(claim.nominal_asuransi).toLocaleString('id-ID') }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase" :class="{
                                    'bg-gray-100 text-gray-800': claim.status === 'draft',
                                    'bg-blue-100 text-blue-800': claim.status === 'submitted',
                                    'bg-yellow-100 text-yellow-800': claim.status === 'reviewed',
                                    'bg-green-100 text-green-800': claim.status === 'approved',
                                    'bg-red-100 text-red-800': claim.status === 'rejected'
                                }">{{ claim.status }}</span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <Button label="Lihat Data" severity="secondary" size="small" @click="activeDetailClaim = claim" />
                                
                                <template v-if="currentUser.role === 'user' && claim.status === 'draft'">
                                    <Button label="Kirim (Submit)" severity="primary" size="small" :loading="statusForm.processing" @click="handleUpdateStatus(claim.id, 'submitted')" />
                                    <Button label="Hapus" severity="danger" size="small" :loading="deleteForm.processing" @click="handleDeleteClaim(claim.id)" />
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="activeDetailClaim" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white p-6 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-xl">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Detail Pengajuan Formulir Asuransi #{{ activeDetailClaim.id }}</h3>
                        <Button label="Tutup" severity="secondary" size="small" @click="activeDetailClaim = null" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm border-b pb-4 mb-4 text-gray-900">
                        <p><strong>Nama Lengkap:</strong> {{ activeDetailClaim.nama_lengkap }}</p>
                        <p><strong>No. HP:</strong> {{ activeDetailClaim.no_telepon }}</p>
                        <p><strong>Email Kontak:</strong> {{ activeDetailClaim.email_kontak }}</p>
                        <p><strong>TTL:</strong> {{ activeDetailClaim.tempat_lahir }}, {{ activeDetailClaim.tanggal_lahir }}</p>
                        <p><strong>Jenis Plan:</strong> {{ activeDetailClaim.perencanaan_hidup }}</p>
                        <p><strong>Nominal Ajuan:</strong> Rp {{ Number(activeDetailClaim.nominal_asuransi).toLocaleString('id-ID') }}</p>
                        <p><strong>Total Asuransi Jiwa:</strong> Rp {{ Number(activeDetailClaim.total_asuransi_jiwa).toLocaleString('id-ID') }}</p>
                        <p><strong>Fisik:</strong> {{ activeDetailClaim.tinggi_badan }} cm / {{ activeDetailClaim.berat_badan }} kg</p>
                        <div class="col-span-2"><strong>Alamat:</strong> {{ activeDetailClaim.alamat }}</div>
                        <div class="col-span-2 bg-red-50 p-2 rounded text-red-900"><strong>Riwayat Masalah Kesehatan:</strong> {{ activeDetailClaim.masalah_kesehatan || '-' }}</div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <div v-if="currentUser.role === 'verifier' && activeDetailClaim.status === 'submitted'" class="space-x-2">
                            <Button label="Lolos Verifikasi (Mark Reviewed)" severity="warn" :loading="statusForm.processing" @click="handleUpdateStatus(activeDetailClaim.id, 'reviewed')" />
                            <Button label="Tidak Lolos Verifikasi (Reject)" severity="danger" :loading="statusForm.processing" @click="handleUpdateStatus(activeDetailClaim.id, 'rejected')" />
                        </div>

                        <div v-if="currentUser.role === 'approver' && activeDetailClaim.status === 'reviewed'" class="space-x-2">
                            <Button label="ACC (Approve)" severity="success" :loading="statusForm.processing" @click="handleUpdateStatus(activeDetailClaim.id, 'approved')" />
                            <Button label="Tolak (Reject)" severity="danger" :loading="statusForm.processing" @click="handleUpdateStatus(activeDetailClaim.id, 'rejected')" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>