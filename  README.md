cat > README.md << 'EOF'
# Product Catalog - Тестовое задание

## Запуск

```bash
git clone https://github.com/bancampbell/product-catalog.git
cd product-catalog
cp .env.example .env
docker-compose up -d
docker exec -it product_catalog_php composer install
docker exec -it product_catalog_php cp .env.example .env
docker exec -it product_catalog_php php artisan key:generate
docker exec -it product_catalog_php php artisan migrate --seed --force
cd src
npm install
npm run build
```
## Доступ
```bash
Сайт: http://localhost
Админка: http://localhost/admin/login
Логин: admin@example.com
Пароль: admin123