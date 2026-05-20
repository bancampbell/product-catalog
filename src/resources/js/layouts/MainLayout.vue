<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-bold text-gray-800 hover:text-gray-600">
                            Product Catalog
                        </Link>
                    </div>
                    <div class="hidden sm:flex items-center space-x-4">
                        <Link
                            href="/admin/products"
                            class="text-gray-600 hover:text-gray-900"
                            :class="{ 'text-indigo-600 font-semibold': $page.url.startsWith('/admin') }"
                        >
                            Admin Panel
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
