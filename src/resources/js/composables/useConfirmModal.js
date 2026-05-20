import { ref } from 'vue';

export function useConfirmModal() {
    const modalOpen = ref(false);
    const itemToDelete = ref(null);
    const loading = ref(false);

    const openModal = (item) => {
        itemToDelete.value = item;
        modalOpen.value = true;
    };

    const closeModal = () => {
        modalOpen.value = false;
        itemToDelete.value = null;
        loading.value = false;
    };

    const setLoading = (state) => {
        loading.value = state;
    };

    return {
        modalOpen,
        itemToDelete,
        loading,
        openModal,
        closeModal,
        setLoading
    };
}
