# 🛍️ Full Stack Developer Technical Test

### Online Store Development with Laravel & React

📌 [**Test Requirements**](./test_requirements.pdf)

# Online Store with Laravel and React

## 📂 Project Structure
```
.github/workflows
  ├── cd.yml  # Automatic deployment to EC2
  ├── ci.yml  # Tests and code checks on PRs
back/  # Laravel API
front/  # React Frontend
```

### 📂 front architecture and dependencies
Inspired by this [template](https://github.com/Kiranism/react-shadcn-dashboard-starter)

- Js Library - [React 19](https://react.dev/)
- Language - [TypeScript](https://www.typescriptlang.org)
- Styling - [Tailwind CSS](https://tailwindcss.com)
- Async state management - [Tanstack Query aka React Query](https://tanstack.com/query/latest/docs/framework/react/overview)
- Linting - [ESLint](https://eslint.org)
- Formatting - [Prettier](https://prettier.io)
- Authentication - [@react-oauth/google](https://www.npmjs.com/package/@react-oauth/google)

### 📂 back architecture and dependencies

- OpenAPI (Swagger) documentation - [scramble](https://scramble.dedoc.co/)
- Authentication - [Laravel Socialite](https://laravel.com/docs/12.x/socialite)

### 🙍‍♂️ authentication

This application implements a secure authentication flow using Google OAuth and JWT tokens:

1. **Frontend:** Displays Google login popup, collects access_token.
2. **Frontend → Backend:** Sends Google access_token to backend.
3. **Backend:** Validates token, retrieves user information, generates JWT.
4. **Backend → Frontend:** Returns JWT token.
5. **Frontend:** Stores JWT for subsequent authenticated requests.

### 🔄 back api documentation
- `/docs/api` for interactive testing
- `/docs/api.json` OpenApi 3.1.0

### 📄 back entity scaffolding

See `back/Makefile`
```bash
make generate-entity-scaffolding name=ModelName
```

| Option  | Description               | Generated File                                      |
|---------|---------------------------|-----------------------------------------------------|
| `-m`    | Migration                 | `database/migrations/xxxx_xx_xx_xxxxxx_create_model_names_table.php` |
| `-f`    | Factory                   | `database/factories/ModelNameFactory.php`            |
| `-s`    | Seeder                    | `database/seeders/ModelNameSeeder.php`               |
| `-c`    | Controller                | `app/Http/Controllers/ModelNameController.php`       |
| `--api` | Makes the controller API  | Methods `index`, `store`, `show`, `update`, `destroy` |

### 🛒 back cart architecture

#### ✨ Key Features

- 🔐 **Secure User Support:** Supports both authenticated users with account-linked carts and guest users with temporary, session-based carts.
- 💾 **Database Driven:** Leverages database persistence for reliable cart data storage.
- 🔄 **Dynamic Cart Management:** Enables adding, updating quantities, and removing products from the cart in real-time.
- 🛡️ **Robust Security:** Ensures cart isolation between users and includes cleanup mechanisms for old guest carts.

#### 🏗️ System Architecture

##### Core Components

1. **Models**
   - `Cart`: Represents the main shopping cart entity.
   - `CartItem`: Represents individual items within a cart.

2. **Services**
   - `CartService`: Contains the core business logic for cart operations.
   - `CartSessionService`: Manages cart data for guest users using sessions.
   - `AuthService`: Handles user authentication and linking carts.

3. **Controllers**
   - `CartController`: Provides REST API endpoints for interacting with the cart.

4. **Repositories**
   - `CartRepository`: Handles database interactions for cart data.

5. **Contracts**
   - `CartRepositoryInterface`: Defines the contract for cart data access.
   - `CartServiceInterface`: Defines the contract for cart business logic.
   - `CartSessionServiceInterface`: Defines the contract for cart session management.
   - `AuthServiceInterface`: Defines the contract for authentication services related to carts.

#### 🚀 Functionality

##### 👤 Authenticated Users
- Add products to cart: ➕
- Update quantities: ⬆️⬇️
- Remove products: 🗑️
- Clear cart completely: 🧹

##### 👻 Guest Users
- Create temporary cart: 🆕
- Cart persistence via session token: 🍪
- Automatic cart migration upon registration: ➡️👤

#### 📦 Database Migrations

##### Table Structure

- `carts`: Stores primary cart information
  - `user_id`: Links to an authenticated user (nullable).
  - `guest_id`: Identifier for guest users (nullable).
  - `session_token`: Token for guest user session persistence (nullable).

- `cart_items`: Stores individual items in a cart
  - `cart_id`: Foreign key linking to the `carts` table.
  - `product_id`: Identifier of the associated product.
  - `quantity`: Number of units of the product.
  - `price`: Price of the product at the time of addition.

#### 🔒 Security Considerations

- Cart data is isolated between different users. 🧍↔️🧍
- Scheduled jobs to clean up old and abandoned guest carts. ⏳🗑️
- Permission checks implemented for all cart operations. ✅

#### 🧪 Testing

- Comprehensive tests for authenticated user scenarios. ✅👤
- Thorough tests for guest user scenarios. ✅👻
- Edge case testing to ensure robustness. 🧪
- Security-focused tests to prevent unauthorized access. 🛡️✅

#### 🚨 Performance Optimization

- Database queries are optimized with indexing. ⚡️
- Efficient management of cart items to minimize overhead. ⚙️

#### 🔍 Monitoring & Logging

- Tracking of key cart interactions for insights. 📝
- Logging of critical events and potential issues. 📝
- Hooks for performance monitoring and analysis. 📊

#### 📊 Scalability Considerations

- Architecture designed to handle high traffic loads. 📈
- Supports different strategies for cart management. 🛠️
- Designed for easy integration with microservices. 🔗

## 🚀 Installation and Setup
### Requirements
- **PHP 8.4**, Composer, MariaDB, Node.js, npm
- **Laravel 12**, React + Vite, Tailwind CSS

### 🔧 Backend (Laravel API)
```bash
cd back
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:secret
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

### 🎨 Frontend (React)
```bash
cd front
cp .env.example .env
npm install
npm run dev
```

## ✅ CI/CD: GitHub Actions
Workflows have been set up in **.github/workflows/**:
- **CI (Continuous Integration):** Runs tests and checks code on PRs.
- **CD (Continuous Deployment):** Automatically deploys to **EC2** upon release publishing.

### 🔀 Development Workflow
1. **Create a branch** from `main` following the naming convention:
   - `feature/<functionality>` (new feature)
   - `bugfix/<error>` (bug fix)

2. **Push changes** and open a **Pull Request**.
3. **CI runs tests** automatically.
4. **Merge to `main`** only if tests pass.
5. **CD deploys** upon release publication.