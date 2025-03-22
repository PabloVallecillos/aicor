import { ShoppingCart } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useCart } from '@/providers/cart-provider.tsx';
import { navItems } from '@/constants/data.ts';
import { Logo } from '@/components/shared/logo.tsx';

export default function Nav() {
  const { totalItems, openCart } = useCart();

  return (
    <nav className="bg-secondary shadow-sm dark:bg-primary-foreground">
      <div className="mx-auto px-4">
        <div className="flex h-16 items-center justify-between">
          <Link to="/" className="h-auto py-2 sm:h-12">
            <Logo className="h-full w-auto" />
          </Link>

          <div className="flex items-center space-x-4">
            {navItems.map(function ({ href, icon: Icon }) {
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
          </div>
        </div>
      </div>
    </nav>
  );
}
