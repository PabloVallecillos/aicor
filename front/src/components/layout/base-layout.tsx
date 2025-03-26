import { ModeToggle } from '@/components/shared/theme-toggle.tsx';
import Nav from '@/components/shared/nav.tsx';
import ShoppingCartModal from '@/components/shared/shopping-cart-modal.tsx';

export default function BaseLayout({
  children
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="flex h-screen flex-col overflow-hidden bg-secondary">
      <Nav />
      <main className="relative mx-4 my-3 mr-2 flex-1 overflow-hidden rounded-xl bg-background focus:outline-none md:mx-4 md:my-4 md:ml-4 md:mr-4">
        <div className="fixed bottom-2 left-2 z-10">
          <ModeToggle />
        </div>
        <ShoppingCartModal />
        {children}
      </main>
    </div>
  );
}
