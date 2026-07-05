import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAsuransiStore = defineStore('asuransi', () => {
    // State (Composition API style)
    const tipePolis = ref('Kesehatan');

    // Action untuk mengubah state
    function setTipePolis(baru) {
        tipePolis.value = baru;
    }

    return { tipePolis, setTipePolis };
});