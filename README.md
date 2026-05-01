# Inventory System API

A RESTful inventory management API built with Laravel 13, providing products, categories, orders, and dashboard statistics with Sanctum token authentication.

## Tech Stack

- **PHP** 8.4
- **Laravel** 13
- **Laravel Sanctum** 4 (API token authentication)
- **SQLite**
- **Pest** 4 (testing)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## API Endpoints

All endpoints are prefixed with `/api/v1`.

### Authentication

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/v1/register` | Register a new user | No |
| POST | `/api/v1/login` | Login and get token | No |
| POST | `/api/v1/logout` | Logout (revoke token) | Yes |
| GET | `/api/v1/user` | Get authenticated user | Yes |

### Categories

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/categories` | List all categories (paginated) | Yes |
| POST | `/api/v1/categories` | Create a category | Yes |
| GET | `/api/v1/categories/{id}` | Get a category | Yes |
| PUT/PATCH | `/api/v1/categories/{id}` | Update a category | Yes |
| DELETE | `/api/v1/categories/{id}` | Delete a category | Yes |

### Products

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/products` | List products (paginated, filterable) | Yes |
| POST | `/api/v1/products` | Create a product | Yes |
| GET | `/api/v1/products/{id}` | Get a product | Yes |
| PUT/PATCH | `/api/v1/products/{id}` | Update a product | Yes |
| DELETE | `/api/v1/products/{id}` | Delete a product | Yes |

**Query params for listing:** `search` (name search), `category` (filter by category name)

### Orders

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/orders` | List user's orders (paginated) | Yes |
| POST | `/api/v1/orders` | Create an order | Yes |
| GET | `/api/v1/orders/{id}` | Get an order | Yes |

**Query params for listing:** `status` (pending, completed, cancelled, refunded)

### Dashboard

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/dashboard` | Get dashboard statistics | Yes |

## Authentication

The API uses Laravel Sanctum with Bearer tokens. Tokens expire after 24 hours.

```bash
# Register
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"password"}'

# Login
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password"}'

# Authenticated request
curl http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer <your-token>"
```

## Architecture

```
Controller → Service → Model
     ↓           ↓
 Form Request  API Resource
```

- **Controllers** handle HTTP request/response delegation
- **Services** contain business logic (CRUD, order processing with transaction + row locking)
- **Form Requests** validate input
- **API Resources** transform model output
- **Enums** define order statuses and HTTP status codes

## Testing

```bash
php artisan test
php artisan test --filter=testName
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
