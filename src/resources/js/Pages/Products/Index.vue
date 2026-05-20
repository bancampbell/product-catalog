<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Коллекция
                </h1>
                <div class="relative">
                    <select
                        v-model="selectedCategory"
                        @change="handleFilter"
                        class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 focus:bg-white transition-all px-5 py-2.5 pr-10 cursor-pointer"
                    >
                        <option value="">Все категории</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-20">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
                <p class="mt-4 text-gray-500">Загрузка...</p>
            </div>

            <div v-else-if="products.length === 0" class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="text-6xl mb-4">🛍️</div>
                <p class="text-gray-500">Товары не найдены</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-indigo-200"
                >
                    <Link :href="`/product/${product.id}`" class="block p-5">
                        <div class="mb-3">
                            <div class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-2">
                                {{ product.category?.name || 'Uncategorized' }}
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ product.name }}
                            </h2>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">
                            {{ product.description || 'Нет описания' }}
                        </p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-2xl font-bold text-indigo-600">
                                {{ formatPrice(product.price) }} ₽
                            </span>
                            <span class="text-gray-400 text-sm group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-if="meta.last_page > 1" class="mt-12 flex justify-center">
                <div class="flex items-center gap-2 bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                    <button
                        @click="goToPage(meta.current_page - 1)"
                        :disabled="meta.current_page === 1"
                        class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        ←
                    </button>

                    <div class="hidden sm:flex gap-1">
                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            @click="goToPage(page)"
                            :class="[
                                'min-w-[40px] h-10 rounded-lg font-medium transition',
                                page === meta.current_page
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'text-gray-600 hover:bg-gray-50'
                            ]"
                        >
                            <template v-if="page === '...'">...</template>
                            <template v-else>{{ page }}</template>
                        </button>
                    </div>

                    <span class="sm:hidden px-3 py-1 text-sm text-gray-600">
                        {{ meta.current_page }} / {{ meta.last_page }}
                    </span>

                    <button
                        @click="goToPage(meta.current_page + 1)"
                        :disabled="meta.current_page === meta.last_page"
                        class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        →
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import MainLayout from '../../layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useProducts } from '../../composables/useProducts';

const { products, categories, loading, meta, loadProducts, loadCategories } = useProducts();
const selectedCategory = ref('');

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU').format(price);
};

const visiblePages = computed(() => {
    const current = meta.value.current_page;
    const last = meta.value.last_page;
    const delta = 2;
    const range = [];
    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }
    const pages = [1];
    if (range[0] > 2) pages.push('...');
    pages.push(...range);
    if (range[range.length - 1] < last - 1) pages.push('...');
    if (last > 1) pages.push(last);
    return pages;
});

const loadData = async () => {
    await loadProducts(1, selectedCategory.value || null);
    await loadCategories();
};

const handleFilter = () => {
    loadProducts(1, selectedCategory.value || null);
};

const goToPage = (page) => {
    if (typeof page !== 'number') return;
    if (page >= 1 && page <= meta.value.last_page) {
        loadProducts(page, selectedCategory.value || null);
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
};

onMounted(() => {
    loadData();
});
</script>
