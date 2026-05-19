import client from './client';

export const getProducts = (page = 1, categoryId = null) => {
    const params = { page };
    if (categoryId) {
        params.category_id = categoryId;
    }
    return client.get('/products', { params });
};

export const getCategories = () => {
    return client.get('/categories');
};

export const getProduct = (id) => {
    return client.get(`/products/${id}`);
};

export const createProduct = (data) => {
    return client.post('/products', data);
};

export const updateProduct = (id, data) => {
    return client.put(`/products/${id}`, data);
};

export const deleteProduct = (id) => {
    return client.delete(`/products/${id}`);
};
