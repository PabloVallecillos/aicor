import { ShoppingCart, User } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useCart } from '@/hooks/use-cart.tsx';
import { NAV_ITEMS } from '@/constants/data.ts';
import { Logo } from '@/components/shared/logo.tsx';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu.tsx';
import { Button } from '@/components/ui/button.tsx';
import { useAuth } from '@/hooks/use-auth.tsx';
import { useRouter } from '@/routes/hooks';

export default function Nav() {
  const { totalItems, openCart } = useCart();
  const { logout, authToken } = useAuth();
  const router = useRouter();

  const handleLogout = () => {
    logout();
    router.push('/');
  };

  return (
    <nav className="bg-secondary shadow-sm dark:bg-primary-foreground">
      <div className="mx-auto px-4">
        <div className="flex h-16 items-center justify-between">
          <Link to="/" className="h-8 py-2">
            <Logo className="h-full w-auto" />
          </Link>

          <div className="flex items-center space-x-4">
            {NAV_ITEMS.map(function ({ href, icon: Icon }) {
              return (
                <Link
                  key={href}
                  to={href}
                  className="flex items-center rounded-md px-3 py-2 text-sm font-medium text-primary"
                >
                  {/* eslint-disable-next-line @typescript-eslint/ban-ts-comment */}
                  {/* @ts-expect-error */}
                  <Icon className="mr-1 h-5 w-5" />
                </Link>
              );
            })}

            <button
              onClick={openCart}
              className="relative flex items-center rounded-md px-3 py-2 text-sm font-medium text-primary"
            >
              <ShoppingCart className="h-5 w-5" />
              {totalItems > 0 && (
                <span className="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                  {totalItems}
                </span>
              )}
            </button>

            {authToken && (
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="link" size="icon">
                    <User className="absolute h-5 w-5" />
                    <span className="sr-only">Toggle theme</span>
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuItem
                    className="cursor-pointer"
                    onClick={handleLogout}
                  >
                    Logout
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
