# Laravel Article API with Sanctum Authentication

A Laravel REST API project for managing articles with authentication using Laravel Sanctum (Bearer Token).

## Project Status

### Completed Features ✅
- **Article CRUD API** - Full REST API for articles (title and content)
- **Form Request Validation** - Custom validation classes (StoreArticleRequest, UpdateArticleRequest, RegisterRequest, LoginRequest)
- **Model-View-Controller Architecture** - Proper MVC structure
- **JSON API Responses** - All endpoints return JSON
- **Laravel Sanctum Setup** - Token-based authentication configured and active
- **Password Hashing** - Automatic password encryption using model casts
- **User Registration** - Complete with token generation (POST /api/register)
- **User Login** - Complete with token generation (POST /api/login)
- **Bearer Token Authentication** - Article routes protected with auth:sanctum middleware
- **Separated Token Response** - Token returned separately from user data

### Ready for Testing 🧪
- All authentication endpoints ready
- Article endpoints protected and ready for testing with Bearer tokens
- Complete authentication flow implemented

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

### Authentication Endpoints (Public - No Token Required)
```
POST   /api/register        - Register new user and get token
POST   /api/login          - Login user and get token
```

### Article Endpoints (Protected - Requires Bearer Token)
```
GET    /api/articles        - List all articles
POST   /api/articles        - Create new article
GET    /api/articles/{id}   - Get single article
PUT    /api/articles/{id}   - Update article
DELETE /api/articles/{id}   - Delete article
```

### User Endpoint
```
GET    /api/user           - Get authenticated user info (requires Bearer token)
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ArticleController.php       - Article CRUD operations
│   │   └── Auth/
│   │       ├── Register.php            - User registration with token
│   │       └── Login.php               - User login with token
│   └── Requests/
│       ├── StoreArticleRequest.php     - Article creation validation
│       ├── UpdateArticleRequest.php    - Article update validation
│       ├── RegisterRequest.php         - User registration validation
│       └── LoginRequest.php            - User login validation
└── Models/
    ├── Article.php                     - Article model
    └── User.php                        - User model with HasApiTokens

routes/
└── api.php                             - API routes with auth middleware
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

### 1. Register User
```
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

Response (201):
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "1|aBcDeF123456..."
}
```

### 2. Login User
```
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}

Response (200):
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "2|xYz789..."
}
```

### 3. Create Article (Protected - Requires Bearer Token)
```
POST http://localhost:8000/api/articles
Content-Type: application/json
Authorization: Bearer 1|aBcDeF123456...

{
    "title": "My First Article",
    "content": "This is the content of my article."
}

Response (201):
{
    "id": 1,
    "title": "My First Article",
    "content": "This is the content of my article.",
    "created_at": "2026-02-16T10:00:00.000000Z",
    "updated_at": "2026-02-16T10:00:00.000000Z"
}
```

### 4. Get All Articles (Protected)
```
GET http://localhost:8000/api/articles
Authorization: Bearer 1|aBcDeF123456...

Response (200): Array of articles
```

### 5. Update Article (Protected)
```
PUT http://localhost:8000/api/articles/1
Content-Type: application/json
Authorization: Bearer 1|aBcDeF123456...

{
    "title": "Updated Title",
    "content": "Updated content"
}
```

### 6. Delete Article (Protected)
```
DELETE http://localhost:8000/api/articles/1
Authorization: Bearer 1|aBcDeF123456...

Response (200):
{
    "message": "Article deleted successfully bro"
}
```

### 7. Get Authenticated User
```
GET http://localhost:8000/api/user
Authorization: Bearer 1|aBcDeF123456...

Response (200):
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    ...
}
```

### Testing Without Token (Should Fail)
```
GET http://localhost:8000/api/articles
(No Authorization header)

Response (401):
{
    "message": "Unauthenticated."
}
```

## Form Request Validation

This project uses Laravel Form Requests for clean, reusable validation:

- `StoreArticleRequest` - Validates article creation (title, content required)
- `UpdateArticleRequest` - Validates article updates (fields optional with 'sometimes')
- `RegisterRequest` - Validates user registration (name, email, password with confirmation)
- `LoginRequest` - Validates user login (email, password required)

## Security Features

- **Password Hashing** - Automatic via model cast (`'password' => 'hashed'`)
- **Mass Assignment Protection** - `$fillable` properties defined in models
- **Request Validation** - All inputs validated via Form Requests before processing
- **Bearer Token Authentication** - Laravel Sanctum with auth:sanctum middleware
- **Protected API Routes** - Article routes require valid Bearer token
- **Token-Based Sessions** - Each login creates a new token for multi-device support
- **Secure Password Verification** - Uses `Hash::check()` for password comparison

## Authentication Flow

1. **Register** - User creates account → Receives Bearer token
2. **Login** - User logs in → Receives new Bearer token (multiple tokens supported)
3. **Access Protected Routes** - Send token in `Authorization: Bearer {token}` header
4. **No Token** - Protected routes return 401 Unauthenticated error

## Next Steps

1. Test all endpoints in Postman
2. Verify token authentication works on Article routes
3. Consider adding logout endpoint to revoke tokens
4. Consider adding token expiration configuration
5. Add more protected endpoints as needed

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
