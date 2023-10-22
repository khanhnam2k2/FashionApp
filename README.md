# Project FashionApp
## Technology
1. Laravel 10x (PHP v8.1.17)
    - Documentation: https://laravel.com/docs/9.x
2. Bootstrap v5.2.3
    - Documentation: https://getbootstrap.com/docs/5.2/getting-started/introduction/
3. Icon
    - https://fontawesome.com/icons
4. Alert
    - SweetAlert: https://sweetalert2.github.io/
## SETUP PROJECT
- Step 1: Create file `.env` for API, refer `.env.example`
    ```
    cp .env.example .env
    ```
- Step 2: Run command line to install vendor
    ```
    composer install
    ```
- Step 3: Run command line to generate key
    ```
    php artisan key:generate
    ```
- Step 4: Run command line to run migrate to create DB
    ```
    php artisan migrate --force
    ```
- Step 5: Run command line to add default DB
    ```
    php artisan db:seed
    ```
- Step 6: Run command line to add storage link
    ```
    php artisan storage:link
    ```
- Step 7: Run command line to install npm dependencies
    ```
    npm install
    ```

- Step 8: Run server
    ```
    php artisan serve 

- Run command line
    ```
    1. Log router list
        php artisan route:list
        php artisan api:routes
    2. Add component
        php artisan make:component NameComponent
    3. Add enum
        php artisan make:enum NameEnum
    4. Add Controller
        php artisan make:controller UserController
    4. Migration
        - create table: php artisan make:migration create_{table_name}_table
        - add column: php artisan make:migration add_{column_name}_to_{table_name}_table
        - add more columns: php artisan make:migration add_{column_name}_and_{column_name}_columns_to_{table_name}_table
        - change column: php artisan make:migration change_{column_name}_column_in_{table_name}_table
        - rename column: php artisan make:migration rename_{old_column_name}_column_to_{new_column_name}_in_{table_name}_table
    5. Clear config
        php artisan config:clear
    6. Clear Cache
        php artisan cache:clear
    7. Clear Route Cache
        php artisan route:cache
        php artisan route:clear
    8. Clear View Cache
        php artisan view:clear
    9. Clear Config Cache
        php artisan config:cache
    10. Clear all
        php artisan optimize
        php artisan optimize:clear
    ```

    - Account admin:
        ```
        Username: admin@gmail.com
        Password: 12345678
        ```

## Visual studio code Extensions
- GitLens
- EditorConfig
- Laravel Blade Snippets
- Laravel Snippets
- Laravel Format
