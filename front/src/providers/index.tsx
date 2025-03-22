import { Button } from '@/components/ui/button';
import { useRouter } from '@/routes/hooks';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Suspense } from 'react';
import { ErrorBoundary, FallbackProps } from 'react-error-boundary';
import { BrowserRouter } from 'react-router-dom';
import ThemeProvider from './theme-provider';
// import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import { Toaster } from '@/components/ui/toaster.tsx';
import { CartProvider } from '@/providers/cart-provider.tsx';
// eslint-disable-next-line react-refresh/only-export-components
export const queryClient = new QueryClient();

const ErrorFallback = ({ error }: FallbackProps) => {
  const router = useRouter();
  console.log('error', error);
  return (
    <div
      className="flex h-screen w-screen flex-col items-center  justify-center text-red-500"
      role="alert"
    >
      <h2 className="text-2xl font-semibold">
        Ooops, something went wrong :({' '}
      </h2>
      <pre className="text-2xl font-bold">{error.message}</pre>
      <pre>{error.stack}</pre>
      <Button className="mt-4" onClick={() => router.back()}>
        Go back
      </Button>
    </div>
  );
};

export default function AppProvider({
  children
}: {
  children: React.ReactNode;
}) {
  return (
    <Suspense>
      <BrowserRouter>
        <ErrorBoundary FallbackComponent={ErrorFallback}>
          <QueryClientProvider client={queryClient}>
            {/*<ReactQueryDevtools />*/}
            <ThemeProvider defaultTheme="dark" storageKey="vite-ui-theme">
              <CartProvider>
                <Toaster />
                {children}
              </CartProvider>
            </ThemeProvider>
          </QueryClientProvider>
        </ErrorBoundary>
      </BrowserRouter>
    </Suspense>
  );
}
