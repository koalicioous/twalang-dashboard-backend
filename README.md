## Twalang Dashboard Backend

1. Install Dependencies

```
composer install
```
2. Set .ENV file with your development environment

3. Generate Apps Key

```
php artisan key:generate
```

4. Run database Migration

```
php artisan migrate
```

5. Run Location, category, and Admin Seeder

```
php artisan db:seed --class="LocationSeeder"
php artisan db:seed --class="CategorySeeder"
php artisan db:seed --class="AdminSeeder"
```

6. Run Purchase Seeder

```
php artisan db:seed
```

7. Run development server
```
php artisan serve
```