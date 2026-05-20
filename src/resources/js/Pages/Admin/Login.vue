<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 w-96 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Добро пожаловать
                </h1>
                <p class="text-gray-500 text-sm mt-1">Войдите в панель управления</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="admin@example.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                        :class="{ 'border-red-400 bg-red-50': errors.email }"
                    />
                    <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Пароль</label>
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="••••••"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all bg-gray-50 focus:bg-white"
                        :class="{ 'border-red-400 bg-red-50': errors.password }"
                    />
                    <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                </div>

                <button
                    @click="login"
                    :disabled="loading"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 transition-all shadow-md hover:shadow-lg"
                >
                    <span v-if="loading" class="inline-block animate-spin mr-2">⏳</span>
                    {{ loading ? 'Вход...' : 'Войти' }}
                </button>

                <Link href="/" class="block text-center text-gray-500 hover:text-gray-700 text-sm mt-4 transition">
                    ← На главную
                </Link>

                <p v-if="error" class="text-red-500 text-sm text-center mt-2">{{ error }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

const form = reactive({
    email: 'admin@example.com',
    password: 'admin123',
});

const errors = reactive({
    email: '',
    password: '',
});

const loading = ref(false);
const error = ref('');

const validate = () => {
    let isValid = true;
    errors.email = '';
    errors.password = '';

    if (!form.email) {
        errors.email = 'Email обязателен';
        isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(form.email)) {
        errors.email = 'Введите корректный email';
        isValid = false;
    }

    if (!form.password) {
        errors.password = 'Пароль обязателен';
        isValid = false;
    }

    return isValid;
};

const login = async () => {
    if (!validate()) return;

    loading.value = true;
    error.value = '';

    try {
        const response = await axios.post('/api/login', form);
        const token = response.data.data.access_token;
        localStorage.setItem('token', token);
        window.location.href = `/admin/products?token=${token}`;
    } catch (err) {
        error.value = err.response?.data?.message || 'Ошибка входа';
    } finally {
        loading.value = false;
    }
};
</script>
