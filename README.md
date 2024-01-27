# Đồ án chuyên ngành năm 4: Webite bán quần áo bằng Laravel
## Giáo viên hướng dẫn: Thầy Đặng Hồng Lĩnh
## Sinh viên thực hiện: Đặng Hà Nam
## Công nghệ
1. Laravel 10x (PHP v8.1.17)
    - Documentation: https://laravel.com/docs/9.x
2. Bootstrap v5.2.3
    - Documentation: https://getbootstrap.com/docs/5.2/getting-started/introduction/
3. Icon
    - https://fontawesome.com/icons
4. Alert
    - SweetAlert: https://sweetalert2.github.io/
5. Ckeditor
    - https://ckeditor.com/ckeditor-5/
6. Lightbox
    - https://lokeshdhakar.com/projects/lightbox2/
7. Jquery
    - https://jquery.com/
8. Chartjs
    - https://www.chartjs.org/
9. Composer
    - https://getcomposer.org/
    
## Thiết lập trang web
- Bước 1: Tạo file `.env` cho API, và file tham khảo `.env.example`
    ```
    cp .env.example .env
    ```
- Bước 2: Chạy dòng lệnh để cài đặt vendor
    ```
    composer install
    ```
- Bước 3: Chạy dòng lệnh để tạo khóa (key)
    ```
    php artisan key:generate
    ```
- Bước 4: Chạy dòng lệnh để chạy Migrate tạo DB
    ```
    php artisan migrate:fresh
    ```
- Bước 5: Chạy dòng lệnh để thêm DB mặc định
    ```
    php artisan db:seed
    ```
- Bước 6: Chạy dòng lệnh để thêm liên kết lưu trữ ảnh vào storage
    ```
    php artisan storage:link
    ```
- Bước 7: Chạy dòng lệnh để cài đặt các gói phụ thuộc 
    ```
    npm install
    ```

- Bước 8: Chạy server
    ```
    php artisan serve 

    ```
- Tài khoản admin:
     ```
    Username: danghanam2k2@gmail.com
    Password: 12345678
    ```

## Visual studio code Extensions
- GitLens
- Laravel Blade Snippets
- Laravel Snippets
- Laravel Format
