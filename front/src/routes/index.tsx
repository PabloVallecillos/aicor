import { Suspense, lazy } from 'react';
import { Navigate, Outlet, useRoutes } from 'react-router-dom';
import { JWT_LOCAL_STORAGE_KEY } from '@/constants/local-storage.tsx';

const BaseLayout = lazy(() => import('@/components/layout/base-layout.tsx'));
const SignInPage = lazy(() => import('@/pages/auth/signin'));
const ShopPage = lazy(() => import('@/pages/shop'));
const PurchasesPage = lazy(() => import('@/pages/purchases'));
const NotFound = lazy(() => import('@/pages/not-found'));

const isAuthenticated = () => {
  return Boolean(localStorage.getItem(JWT_LOCAL_STORAGE_KEY));
};

function ProtectedRoute() {
  return isAuthenticated() ? (
    BaseLayoutOutlet()
  ) : (
    <Navigate to="/login" replace />
  );
}

function BaseLayoutOutlet() {
  return (
    <BaseLayout>
      <Suspense>
        <Outlet />
      </Suspense>
    </BaseLayout>
  );
}

export default function AppRouter() {
  const routes = useRoutes([
    {
      path: '/',
      element: BaseLayoutOutlet(),
      children: [
        { index: true, element: <ShopPage /> },
        { path: 'login', element: <SignInPage /> },
        { path: '404', element: <NotFound /> },
        { path: '*', element: <Navigate to="/404" replace /> }
      ]
    },
    {
      path: '/purchases',
      element: <ProtectedRoute />,
      children: [{ index: true, element: <PurchasesPage /> }]
    }
  ]);

  return routes;
}
