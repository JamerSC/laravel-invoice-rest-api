### Task & Activities

### September 8, 2025 - Laravel Testing with PHPUnit/Pest (Auth + CRUD)

1. API tests with PHPUnit or Pest (Auth + CRUD)

-   Create project Invoice API
-   Can create customer & issue an invoices
-   Implement PHPUnit testing

2. Setup

-   ✅ Compose new laravel project Invoice API
-   ✅ Update .env file logging & database
-   Generate app key: `php artisan key:generate`
-   Created new file in the root `.env.testing`
-   ✅ create new database `invoice_api_db`
-   ✅ Compose sanctum authentication `composer require laravel/sanctum`
-   In app\Http\kernel.php at API un comment `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class`
-   ✅ Create Model, Controller, Request,
-   ✅ Create Model Class
-   Customer model `php artisan make:model Customer -m`
-   Invoice model `php artisan make:model Invoice -m`
-   Setup Migration files in `invoice-api\database\migrations user, customer, invoice`
-   Migrate setup files `php artisan migrate `
-   Setup models $fillables & relationships
-
-   ✅ Create Controller Class
-   Customer controller `php artisan make:controller Api\V1\CustomerController --api`
-   Invoice controller `php artisan make:controller Api\V1\InvoiceController --api`
-   User controller `php artisan make:controller Api\V1\UserController --api`
-   Auth Controller `php artisan make:controller Api\V1\AuthController`
-   Setup controllers function
-   ✅ Create Resource Class
-   Customer Resource `php artisan make:resource V1\CustomerResource`
-   Add invoices
-   Invoice resource `php artisan make:resource V1\InvoiceResource`
-   User resource `php artisan make:resource V1\UserResource`
-   Setup resources display
-   ✅ Create Request Class
-   Store User `php artisan make:request V1\StoreUserRequest`
-   Update User `php artisan make:request V1\UpdateUserRequest`
-   Store customer request `php artisan make:request V1\StoreCustomerRequest`
-   Update customer request `php artisan make:request V1\UpdateCustomerRequest`
-   Store invoice request `php artisan make:request V1\StoreInvoiceRequest`
-   Update invoice request `php artisan make:request V1\UpdateInvoiceRequest`
-   Register request `php artisan make:request V1\RegisterRequest`
-   Setup request
-   Login request `php artisan make:request V1\LoginRequest`
-   ✅ Create Seeder Class
-   User seeder `php artisan make:seeder UserSeeder ` - Create admin default user
-   Customer seeder `php artisan make:seeder CustomerSeeder`
-   Invoice seeder `php artisan make:seeder InvoiceSeeder `
-   ✅ ROUTES
-   Create route prefix
-   Create route + middleware with Authentication of Sanctum
-   Add routes of auth controller: register, login, logout
-   Add routes for User, Customer, & Invoice Controller

3.  TEST: https://laravel.com/docs/12.x/testing

-   PHPUnit and Pest are testing frameworks for PHP, and Laravel supports both.
    But they have some key differences in style, readability, and developer experience.
-   `PHPUnit is a unit testing framework for PHP and Laravel provides a seamless way to use it for testing your API`

-   Setup
-   ✅ Duplicate `.env` file and rename it into `.env.testing`
-   Create test database `invoice_api_db_test` & Update database name
-   Clear Caches: It's good practice to clear caches before testing. `php artisan config:clear` & `php artisan cache:clear`
-   Run Migrations: Run the migrations to set up the test database. `php artisan migrate --env=testing`
-   Create a Test File `php artisan make:test UserApiTest` This command will create a new file at tests/Feature/UserApiTest.php. Feature tests are ideal for API testing as they test a larger portion of your application, including routing and controllers.
-   Command Test for Feature only `php artisan test --testsuite=Feature --stop-on-failure`
-   Use Traits: Open tests/Feature/UserApiTest.php and add the `RefreshDatabase` and `WithoutMiddleware` traits.
-   `RefreshDatabase:` This trait automatically migrates your test database before each test and rolls back the migrations after. It ensures that each test method runs on a clean, blank database.
-   `WithoutMiddleware:` This trait disables all middleware for your test. This is useful for isolating your tests from things like CSRF protection, allowing you to focus on the core logic.

-   Specific function `php artisan test --filter=a_user_can_retrieve_their_own_profile`

-   ✅ Others
-   Creating test file in unit `php artisan make:test UserTest --unit`
-   Command to run the test `php artisan test`
-   Command Test for Unit only `php artisan test --testsuite=Unit --stop-on-failure`

### September 9, 2025 - Pagination, Sort, Filtering, & Search with Optional

1. Pagination - controls how many results.

-   eloquent’s built-in pagination

-   `$users = User::paginate(10);` // standard pagination
-   `$users = User::simplePaginate(10);` // lightweight pagination
-   `$users = User::cursorPaginate(10);` // best for large tables

-   End point `/api/v1/customers?page=1&per_page=5`
-   End point `/api/v1/customers?type=individual&sort=name&page=2&per_page=10&include_invoices=true`
-

2. Sort - orders the results.

-   ?sort=-id //descending order
-   ?sort=od //ascending order
-   `{{baseURL}}/api/v1/customers?type=business&sort=-id&page=1&per_page=10&include_invoices=false`

3. Filter - limits results by type, status

-   ?type=business //or individual
-   `{{baseURL}}/api/v1/customers?type=business&page=1&per_page=10&include_invoices=false`

4. Search - finds model (ex. customers) by keyword.

-   `{{baseURL}}/api/v1/customers?q=jo`

5. Optional - includes let you decide whether to fetch related data (invoices).

### September 10, 2025 - File Upload + Storage (e.g., User Attachments)

1. Basic file upload

-   Make a File model `php artisan make:model File -m`
-   Make a FileUploadController - `php artisan make:controller Api\V1\FileUploadController`
-   In FileUploadController created a functions
-   Single upload file
-   Multiple file upload
-   Created routs for single & multiple upload
