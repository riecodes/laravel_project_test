## Completed ✅
- ✅ Form request validation (StoreArticleRequest, UpdateArticleRequest, RegisterRequest, LoginRequest)
- ✅ API CRUD article (index, store, show, update, destroy)
- ✅ Model View Controller (Article model, ArticleController, User model)
- ✅ API responses as JSON (all endpoints return JSON)
- ✅ Sanctum installed (HasApiTokens trait in User model)
- ✅ Password hashing (automatic via 'hashed' cast in User model)
- ✅ Register endpoint with token generation (POST /api/register)
- ✅ Login endpoint with token generation (POST /api/login)
- ✅ Bearer token separated in JSON response (token field separate from user object)
- ✅ Auth routes added to api.php (register and login routes)
- ✅ auth:sanctum middleware applied to Article routes

## Testing Required 🧪
- 🧪 Test register endpoint in Postman
- 🧪 Test login endpoint in Postman
- 🧪 Test article routes WITHOUT bearer token (should return 401)
- 🧪 Test article routes WITH bearer token (should work)
- 🧪 Verify token can be used for authentication

## Known Issues ⚠️
- ⚠️ LoginRequest.php may have syntax error (check validation rules)
- ⚠️ /api/user route is outside auth:sanctum middleware (move inside if needed)

**Goal Achieved:** No bearer token = no authentication on Article routes!


Relation: user to user
- if a user creates an article, they should be able to access all the articles that they created
- only use laravel eloquent (do not use select), use belongs to syntax etc.
- relation is used so that its controlled on what data to pass on, not just the whole thing 

- use Auth on every funtion that needs authentication. ex, in index(). it needs to verify first if the user is authenticated before showing it.

- also make validation for all the requests like api/login, api/register /api/articles