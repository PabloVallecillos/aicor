import { useCart } from '@/providers/cart-provider.tsx';
import Image from '@/components/ui/image';
import { CartItem } from '@/types';

interface ShoppingCartItemProps {
  item: CartItem;
}

export default function ShoppingCartItem({ item }: ShoppingCartItemProps) {
  const { removeFromCart, updateQuantity } = useCart();

  return (
    <>
      <li
        key={item.product.id}
        className="flex items-center gap-4 border-b pb-4"
      >
        <div className="relative h-20 w-20 flex-shrink-0">
          <Image
            src={item.product.image || '/placeholder.svg'}
            alt={item.product.name}
            className="rounded object-cover"
          />
        </div>
        <div className="flex-grow">
          <h3 className="font-medium">{item.product.name}</h3>
          <p className="text-gray-600">${item.product.price.toFixed(2)}</p>
          <div className="mt-2 flex items-center">
            <button
              onClick={() => updateQuantity(item.product.id, item.quantity - 1)}
              className="flex h-8 w-8 items-center justify-center rounded-l border"
            >
              -
            </button>
            <span className="flex h-8 w-10 items-center justify-center border-b border-t">
              {item.quantity}
            </span>
            <button
              onClick={() => updateQuantity(item.product.id, item.quantity + 1)}
              className="flex h-8 w-8 items-center justify-center rounded-r border"
            >
              +
            </button>
          </div>
        </div>
        <button
          onClick={() => removeFromCart(item.product.id)}
          className="text-red-500 hover:text-red-700"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </button>
      </li>
    </>
  );
}
