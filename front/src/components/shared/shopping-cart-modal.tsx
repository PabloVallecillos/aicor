import { useCart } from '@/hooks/use-cart.tsx';
import ShoppingCartItem from '@/pages/shop/components/shopping-cart-item.tsx';
import { Trash, X } from 'lucide-react';
import { toast } from '@/components/ui/use-toast.ts';
import { confirmPurchase } from '@/lib/api.ts';
import { useState } from 'react';

export default function ShoppingCartModal() {
  const { isCartOpen, closeCart, clearCart, cartItems, totalPrice } = useCart();
  const [isProcessing, setIsProcessing] = useState<boolean>(false);

  const handleConfirmPurchase = async () => {
    if (cartItems.length === 0) return;
    setIsProcessing(true);
    try {
      await confirmPurchase(cartItems);
      clearCart();
      closeCart();
      toast({
        title: 'Congratulations!',
        description: 'Order registered'
      });
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not confirm purchase',
        variant: 'destructive'
      });
    } finally {
      setIsProcessing(false);
    }
  };

  return (
    <>
      {isCartOpen && (
        <div
          className="fixed inset-0 z-40 bg-black bg-opacity-50"
          onClick={closeCart}
        />
      )}

      <div
        className={`
        fixed right-0 top-0 z-50 h-full w-full transform bg-secondary shadow-xl 
        transition-transform duration-300 ease-in-out sm:w-96
        ${isCartOpen ? 'translate-x-0' : 'translate-x-full'}
      `}
      >
        <div className="flex h-full flex-col">
          <div className="flex items-center justify-between border-b p-4">
            <h2 className="text-xl font-bold">Cart</h2>
            <button
              onClick={closeCart}
              className="text-primary hover:text-muted-foreground"
            >
              <X />
            </button>
          </div>

          <div className="flex-grow overflow-auto p-4">
            {cartItems.length === 0 ? (
              <div className="py-8 text-center text-primary">Empty cart</div>
            ) : (
              <ul className="space-y-4">
                {cartItems.map((item) => (
                  <ShoppingCartItem key={item.product.id} item={item} />
                ))}
              </ul>
            )}
          </div>

          <div className="border-t p-4">
            <div className="mb-4 flex justify-between">
              <span className="font-medium">Total:</span>
              <span className="font-bold">${totalPrice}</span>
            </div>
            <div className="flex gap-2">
              <button
                className="flex w-12 flex-shrink-0 items-center justify-center rounded-md border-2 bg-secondary py-2 font-medium text-red-500 hover:text-red-700 disabled:opacity-50"
                disabled={cartItems.length === 0}
                onClick={() => {
                  clearCart();
                  closeCart();
                }}
              >
                <Trash />
              </button>
              <button
                className="flex-grow rounded-md bg-primary py-2 font-medium text-white hover:bg-primary/90 disabled:opacity-50"
                disabled={cartItems.length === 0 || isProcessing}
                onClick={handleConfirmPurchase}
              >
                Proceed to payment
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
