<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import Button from 'primevue/button';

const form = useForm({
    title: '',
    description: '',
    amount: 0,
});

const submitClaim = () => {
    // form.post otomatis mengeset form.processing menjadi TRUE selama request berjalan
    form.post(route('claims.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <form @submit.prevent="submitClaim">
        <Button 
            type="submit" 
            label="Kirim Klaim" 
            :loading="form.processing" 
            :disabled="form.processing" 
        />
    </form>
</template>