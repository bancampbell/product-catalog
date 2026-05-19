<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Заголовок и фильтр -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Товары</h1>
                <select
                    v-model="selectedCategory"
                    @change="handleFilter"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                >
                    <option value="">Все категории</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Состояние загрузки -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="mt-2 text-gray-500">Загрузка...</p>
            </div>

            <!-- Список товаров -->
            <div v-else-if="products.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
                <p class="text-gray-500">Товары не найдены</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6"
                >
                    <Link :href="`/product/${product.id}`" class="block">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2 hover:text-indigo-600">
                            {{ product.name }}
                        </h2>
                    </Link>
                    <p class="text-gray-600 mb-2 line-clamp-2">{{ product.description || 'Описание отсутствует' }}</p>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-lg font-bold text-indigo-600">{{ formatPrice(product.price) }} ₽</span>
                        <span class="text-sm text-gray-500">{{ product.category?.name || 'Без категории' }}</span>
                    </div>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="meta.last_page > 1" class="mt-8 flex justify-center space-x-2">
                <button
                    @click="goToPage(meta.current_page - 1)"
                    :disabled="meta.current_page === 1"
                    class="px-4 py-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ← Назад
                </button>
                <span class="px-4 py-2 text-gray-700">
                    Страница {{ meta.current_page }} из {{ meta.last_page }}
                </span>
                <button
                    @click="goToPage(meta.current_page + 1)"
                    :disabled="meta.current_page === meta.last_page"
                    class="px-4 py-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Вперед →
                </button>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import MainLayout from '../../layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useProducts } from '../../composables/useProducts';

const { products, categories, loading, meta, loadProducts, loadCategories } = useProducts();
const selectedCategory = ref('');

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU').format(price);
};

const loadData = async () => {
    await loadProducts(1, selectedCategory.value || null);
    await loadCategories();
};

const handleFilter = () => {
    loadProducts(1, selectedCategory.value || null);
};

const goToPage = (page) => {
    if (page >= 1 && page <= meta.value.last_page) {
        loadProducts(page, selectedCategory.value || null);
    }
};

onMounted(() => {
    loadData();
});
</script>
