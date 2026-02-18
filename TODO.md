## Completed ✅
- ✅ form request (StoreArticleRequest, UpdateArticleRequest, RegisterRequest)
- ✅ api crud article (index, store, show, update, destroy)
- ✅ model view controller (Article model, ArticleController)
- ✅ api (response as json)
- ✅ Sanctum installed (HasApiTokens trait in User model)
- ✅ Password hashing (automatic via 'hashed' cast in User model)

## In Progress 🟡
- 🟡 Register endpoint (needs token generation)

## To Do ❌
use laravel sanctum using bearer token. use it as a middleware, if not authenticated (without bearer token) 
- ❌ Create Login controller/endpoint
- ❌ kailangan mailabas yung bearer token sa json na nakahiwalay (separate token in response)
- ❌ Add auth:sanctum middleware to Article routes
- ❌ beraer token should be copied and should be put on the postman authentication for the data to be access

**Goal:** no bearer token = no authentication