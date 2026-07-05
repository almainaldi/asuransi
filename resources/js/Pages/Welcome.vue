<script setup>
import { Head } from '@inertiajs/inertia-vue3';
// Import Tombol dari PrimeVue UI
import Button from 'primevue/button'; 
// Import Store Pinia yang kita buat tadi
import { useAsuransiStore } from '../store/useAsuransiStore'; 

const store = useAsuransiStore();
</script>

<template>
    <Head title="Welcome" />

    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Uji Coba Ketentuan Tugas</h1>
        
        <p class="text-lg text-gray-700">
            Tipe Polis Saat Ini (Pinia): <span class="font-bold text-blue-600">{{ store.tipePolis }}</span>
        </p>

        <div class="flex gap-2">
            <Button label="Ubah ke Jiwa" severity="success" @click="store.setTipePolis('Jiwa')" />
            <Button label="Ubah ke Kendaraan" severity="warn" @click="store.setTipePolis('Kendaraan')" />
        </div>
    </div>
</template>