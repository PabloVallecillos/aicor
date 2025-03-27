import { useState, ReactNode, useEffect, useRef } from 'react';
import { Product, CartItem } from '@/types';
import { useToast } from '@/components/ui/use-toast.ts';
import { CartContext } from '@/hooks/use-cart';
import { cartApi } from '@/lib/api';
import { RequestCartAddMultipleEndpoint } from '@/types/api.tsx';

export function CartProvider({ children }: { children: ReactNode }) {
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const { toast } = useToast();
  const hasFetched = useRef(false);

  const fetchCartItems = async () => {
    if (hasFetched.current) return;
    try {
      const { items } = await cartApi.getCart();
      setCartItems(items);
      hasFetched.current = true;
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not fetch cart items',
        variant: 'destructive'
      });
    }
  };

  useEffect(
    () => {
      fetchCartItems();
    },
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    []
  );

  const openCart = () => setIsCartOpen(true);
  const closeCart = () => setIsCartOpen(false);

  const addToCart = async (product: Product) => {
    try {
      await cartApi.addToCart(product);

      setCartItems((prevCart) => {
        const existingItem = prevCart.find(
          (item) => item.product.id === product.id
        );

        if (existingItem) {
          return prevCart.map((item) =>
            item.product.id === product.id
              ? { ...item, quantity: item.quantity + 1 }
              : item
          );
        } else {
          return [...prevCart, { product, quantity: 1 }];
        }
      });
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not add item to cart',
        variant: 'destructive'
      });
    }
  };

  const addMultipleToCart = async (
    products: { product: Product; quantity: number }[]
  ) => {
    try {
      const apiRequestData: RequestCartAddMultipleEndpoint = {
        items: products.map(({ product, quantity }) => ({
          product_id: Number(product.id),
          quantity
        }))
      };

      await cartApi.addMultipleToCart(apiRequestData);

      setCartItems((prevCart) => {
        const updatedCart = [...prevCart];

        products.forEach(({ product, quantity }) => {
          const existingItemIndex = updatedCart.findIndex(
            (item) => item.product.id === product.id
          );

          if (existingItemIndex >= 0) {
            updatedCart[existingItemIndex] = {
              ...updatedCart[existingItemIndex],
              quantity: updatedCart[existingItemIndex].quantity + quantity
            };
          } else {
            updatedCart.push({ product, quantity });
          }
        });

        return updatedCart;
      });

      openCart();
    } catch (error) {
      console.error(error);
    }
  };

  const removeFromCart = async (productId: string) => {
    try {
      await cartApi.removeFromCart(productId);

      setCartItems((prevCart) =>
        prevCart.filter((item) => item.product.id !== productId)
      );
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not remove item from cart',
        variant: 'destructive'
      });
    }
  };

  const updateQuantity = async (productId: string, quantity: number) => {
    try {
      if (quantity <= 0) {
        await removeFromCart(productId);
        return;
      }

      await cartApi.updateQuantity(productId, quantity);

      setCartItems((prevCart) =>
        prevCart.map((item) =>
          item.product.id === productId ? { ...item, quantity } : item
        )
      );
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not update cart item quantity',
        variant: 'destructive'
      });
    }
  };

  const clearCart = async () => {
    try {
      await cartApi.clearCart();
      setCartItems([]);
    } catch (error) {
      console.error(error);
      toast({
        title: 'Error',
        description: 'Could not clear cart',
        variant: 'destructive'
      });
    }
  };

  const totalItems = cartItems.reduce(
    (total, item) => total + item.quantity,
    0
  );
  const totalPrice = cartItems.reduce(
    (total, item) => total + item.product.price * item.quantity,
    0
  );

  const value = {
    cartItems,
    isCartOpen,
    openCart,
    closeCart,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    totalItems,
    totalPrice,
    fetchCartItems,
    addMultipleToCart
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}
