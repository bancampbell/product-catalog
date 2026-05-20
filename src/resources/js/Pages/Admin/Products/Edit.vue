<template>
    <MainLayout>
        <div class="max-w-2xl mx-auto px-4 py-12">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Редактировать товар</h1>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Название <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                            :class="{ 'border-red-400 bg-red-50': errors.name }"
                        />
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Категория <span class="text-red-500">*</span></label>
                        <select
                            v-model="form.category_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                            :class="{ 'border-red-400 bg-red-50': errors.category_id }"
                        >
                            <option value="">Выберите категорию</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <p v-if="errors.category_id" class="text-red-500 text-xs mt-1">{{ errors.category_id }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Цена <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                            :class="{ 'border-red-400 bg-red-50': errors.price }"
                        />
                        <p v-if="errors.price" class="text-red-500 text-xs mt-1">{{ errors.price }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Описание</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                        ></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            :disabled="loading"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 transition-all shadow-md hover:shadow-lg"
                        >
                            <span v-if="loading" class="inline-block animate-spin mr-2">⏳</span>
                            {{ loading ? 'Сохранение...' : 'Обновить' }}
                        </button>
                        <Link
                            href="/admin/products"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition text-center"
                        >
                            Отмена
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '../../../layouts/MainLayout.vue';
import {Link, router} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps({product: Object, categories: Array});

const form = ref({
    name: props.product.name,
    category_id: props.product.category_id,
    price: props.product.price,
    description: props.product.description || '',
});

const errors = ref({});
const loading = ref(false);

const submit = () => {
    loading.value = true;
    errors.value = {};

    router.put(`/admin/products/${props.product.id}`, form.value, {
        onFinish: () => {
            loading.value = false;
        },
        onError: (err) => {
            errors.value = err;
        },
    });
};
</script>
