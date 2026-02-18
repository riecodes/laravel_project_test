# Laravel Article API with Sanctum Authentication

A Laravel REST API project for managing articles with authentication using Laravel Sanctum (Bearer Token).

## Project Status

### Completed Features ✅
- **Article CRUD API** - Full REST API for articles (title and content)
- **Form Request Validation** - Custom validation classes for cleaner controllers
- **Model-View-Controller Architecture** - Proper MVC structure
- **JSON API Responses** - All endpoints return JSON
- **Laravel Sanctum Setup** - Token-based authentication configured
- **Password Hashing** - Automatic password encryption using model casts

### In Progress 🟡
- **User Registration** - Endpoint exists, needs token generation

### Pending Features ❌
- Login endpoint
- Bearer token authentication middleware on Article routes
- Complete authentication flow

## Tech Stack

- **Laravel** - PHP Framework
- **Laravel Sanctum** - API Token Authentication
- **MySQL/SQLite** - Database
- **Form Requests** - Request validation

## Database Schema

### Articles Table
- `id` - Primary key
- `title` - String (255)
- `content` - Text
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Users Table
- `id` - Primary key
- `name` - String
- `email` - Unique string
- `password` - Hashed string
- `timestamps`

## API Endpoints

### Article Endpoints (Will require Bearer Token)
```
GET    /api/articles        - List all articles
POST   /api/articles        - Create new article
GET    /api/articles/{id}   - Get single article
PUT    /api/articles/{id}   - Update article
DELETE /api/articles/{id}   - Delete article
```

### Authentication Endpoints (Public)
```
POST   /api/register        - Register new user (in progress)
POST   /api/login          - Login user (pending)
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ArticleController.php       - Article CRUD operations
│   │   └── Auth/
│   │       └── Register.php            - User registration
│   └── Requests/
│       ├── StoreArticleRequest.php     - Article creation validation
│       ├── UpdateArticleRequest.php    - Article update validation
│       └── RegisterRequest.php         - User registration validation
└── Models/
    ├── Article.php                     - Article model
    └── User.php                        - User model with HasApiTokens

routes/
└── api.php                             - API routes definition
```

## Installation

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations:
```bash
php artisan migrate
```

5. Start development server:
```bash
php artisan serve
```

## Testing with Postman

### 1. Register User (In Progress)
```
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### 2. Create Article (Will require authentication)
```
POST http://localhost:8000/api/articles
Content-Type: application/json
Authorization: Bearer {your-token-here}

{
    "title": "My First Article",
    "content": "This is the content of my article."
}
```

### 3. Get All Articles
```
GET http://localhost:8000/api/articles
```

### 4. Update Article
```
PUT http://localhost:8000/api/articles/1
Content-Type: application/json

{
    "title": "Updated Title",
    "content": "Updated content"
}
```

### 5. Delete Article
```
DELETE http://localhost:8000/api/articles/1
```

## Form Request Validation

This project uses Laravel Form Requests for clean, reusable validation:

- `StoreArticleRequest` - Validates article creation (title, content required)
- `UpdateArticleRequest` - Validates article updates (fields optional)
- `RegisterRequest` - Validates user registration (name, email, password with confirmation)

## Security Features

- **Password Hashing** - Automatic via model cast (`'password' => 'hashed'`)
- **Mass Assignment Protection** - `$fillable` properties defined
- **Request Validation** - All inputs validated before processing
- **Bearer Token Authentication** - (Implementation in progress)

## Next Steps

1. Add token generation to Register controller
2. Create Login controller with token generation
3. Add `auth:sanctum` middleware to Article routes
4. Test complete authentication flow in Postman

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
