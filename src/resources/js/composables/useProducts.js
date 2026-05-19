import { ref } from 'vue';
import { getProducts, getCategories } from '../api/products';

export function useProducts() {
    const products = ref([]);
    const categories = ref([]);
    const loading = ref(false);
    const meta = ref({
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
    });

    const loadProducts = async (page = 1, categoryId = null) => {
        loading.value = true;
        try {
            const response = await getProducts(page, categoryId);
            products.value = response.data.data;
            meta.value = response.data.meta;
        } catch (error) {
            console.error('Error loading products:', error);
        } finally {
            loading.value = false;
        }
    };

    const loadCategories = async () => {
        try {
            const response = await getCategories();
            categories.value = response.data.data;
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    };

    return {
        products,
        categories,
        loading,
        meta,
        loadProducts,
        loadCategories,
    };
}
