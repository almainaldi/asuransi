<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { inject } from 'vue'; // Tambahkan ini

defineProps({
    canResetPassword: Boolean,
    status: String,
});

// Setup form menggunakan Inertia Form Helper
const form = useForm({
    email: '',
    password: '',
    remember: false
});

// AMBIL FUNGSI ROUTE DARI ZIGGY SECARA MANUAL JIKA GLOBAL TIDAK TERBACA
const route = inject('route'); 

// Fungsi pengiriman data ke route login Laravel
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            console.log('Login sukses!');
        },
        onError: (errors) => {
            console.error('Login gagal:', errors);
        }
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border">
            
            <div class="mb-4 text-center">
                <h2 class="text-xl font-bold text-gray-800">Approval System Login</h2>
                <p class="text-xs text-gray-500">Silakan masuk menggunakan akun simulasi Anda</p>
            </div>

            <div v-if="form.errors.email || form.errors.password" class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-600">
                {{ form.errors.email || form.errors.password }}
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-900" 
                        v-model="form.email" 
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                </div>

                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-900" 
                        v-model="form.password" 
                        required 
                        autocomplete="current-password" 
                    />
                </div>

                <div class="block">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                            v-model="form.remember"
                        />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button 
                        type="submit" 
                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                        :class="{ 'opacity-25': form.processing }" 
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Masuk (Log In)</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>