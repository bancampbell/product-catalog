<template>
    <MainLayout>
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Управление товарами
                </h1>
                <button
                    @click="logout"
                    :disabled="logoutLoading"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white font-medium hover:from-red-600 hover:to-red-700 disabled:opacity-50 transition-all shadow-md hover:shadow-lg"
                >
                    <span v-if="logoutLoading" class="inline-block animate-spin mr-2">⏳</span>
                    {{ logoutLoading ? 'Выход...' : 'Выйти' }}
                </button>
            </div>

            <Link
                href="/admin/products/create"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg mb-6"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Добавить товар
            </Link>

            <div class="grid gap-4">
                <div
                    v-for="product in products.data"
                    :key="product.id"
                    class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-5 border border-gray-100 hover:border-indigo-200"
                >
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider bg-indigo-50 px-2 py-1 rounded-full">
                                    {{ product.category?.name || 'Без категории' }}
                                </span>
                                <span class="text-lg font-bold text-gray-800">{{ product.name }}</span>
                            </div>
                            <div class="text-2xl font-bold text-indigo-600">
                                {{ formatPrice(product.price) }} ₽
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Link
                                :href="`/admin/products/${product.id}/edit`"
                                class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 transition font-medium text-sm"
                            >
                                Редактировать
                            </Link>
                            <button
                                @click="openModal(product)"
                                class="px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition font-medium text-sm"
                            >
                                🗑 Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="products.meta?.last_page > 1" class="mt-8 flex justify-center">
                <div class="flex items-center gap-2 bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                    <button
                        @click="goToPage(products.meta.current_page - 1)"
                        :disabled="products.meta.current_page === 1"
                        class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        ←
                    </button>
                    <div class="flex gap-1">
                        <button
                            v-for="page in products.meta.last_page"
                            :key="page"
                            @click="goToPage(page)"
                            :class="[
                                'min-w-[40px] h-10 rounded-lg font-medium transition',
                                page === products.meta.current_page
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'text-gray-600 hover:bg-gray-50'
                            ]"
                        >
                            {{ page }}
                        </button>
                    </div>
                    <button
                        @click="goToPage(products.meta.current_page + 1)"
                        :disabled="products.meta.current_page === products.meta.last_page"
                        class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        →
                    </button>
                </div>
            </div>
        </div>

        <ConfirmModal
            :is-open="modalOpen"
            title="Удаление товара"
            :message="`Вы уверены, что хотите удалить товар «${itemToDelete?.name}»? Это действие нельзя отменить.`"
            confirm-text="Удалить"
            type="danger"
            :loading="loading"
            @close="closeModal"
            @confirm="confirmDelete"
        />

        <Toast
            :show="toastShow"
            :message="toastMessage"
            :type="toastType"
            @close="toastShow = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '../../../layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import ConfirmModal from '../../../components/ConfirmModal.vue';
import Toast from '../../../components/Toast.vue';
import { useConfirmModal } from '../../../composables/useConfirmModal';

const props = defineProps({ products: Object });

const logoutLoading = ref(false);
const toastShow = ref(false);
const toastMessage = ref('');
const toastType = ref('success');
const { modalOpen, itemToDelete, loading, openModal, closeModal, setLoading } = useConfirmModal();

const showToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    toastShow.value = true;
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU').format(price);
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;

    setLoading(true);
    const id = itemToDelete.value.id;

    router.delete(`/admin/products/${id}`, {
        onFinish: () => {
            setLoading(false);
            closeModal();
            showToast('Товар успешно удалён', 'success');
        },
        onError: () => {
            setLoading(false);
            closeModal();
            showToast('Ошибка при удалении товара', 'error');
        },
    });
};

const goToPage = (page) => {
    router.get(`/admin/products?page=${page}`, {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

const logout = async () => {
    logoutLoading.value = true;

    try {
        const token = localStorage.getItem('token');
        if (token) {
            await axios.post('/api/logout', {}, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
        }
    } catch (error) {
        console.error('Logout error:', error);
    }

    localStorage.removeItem('token');
    window.location.href = '/admin/logout';
};
</script>
