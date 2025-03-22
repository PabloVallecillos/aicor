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
php artisan migrate --seed
php artisan serve
```

### 🎨 Frontend (React)
```bash
cd front
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