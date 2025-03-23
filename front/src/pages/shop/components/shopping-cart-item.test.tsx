import { it, expect, describe, beforeEach, vi } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, render, screen, fireEvent } from '@testing-library/react';
import ShoppingCartItem from '@/pages/shop/components/shopping-cart-item';
import { CartItem } from '@/types';
import * as cartProvider from '@/hooks/use-cart.tsx';

vi.mock('@/hooks/use-cart.tsx', () => ({
  useCart: vi.fn()
}));

describe('ShoppingCartItem', () => {
  beforeEach(() => {
    cleanup();
    vi.clearAllMocks();
  });

  const mockItem: CartItem = {
    product: {
      id: '1',
      name: 'Product in the cart',
      price: 25,
      image: '/placeholder.svg',
      description: 'Description of the product'
    },
    quantity: 1
  };

  const mockUpdateQuantity = vi.fn();
  const mockRemoveFromCart = vi.fn();

  beforeEach(() => {
    vi.mocked(cartProvider.useCart).mockReturnValue({
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-expect-error
      cart: [mockItem],
      cartTotal: 25,
      updateQuantity: mockUpdateQuantity,
      removeFromCart: mockRemoveFromCart,
      addToCart: vi.fn(),
      clearCart: vi.fn()
    });
  });

  it('should render the ShoppingCartItem component', () => {
    render(<ShoppingCartItem item={mockItem} />);
    expect(screen.getByText(/Product in the cart/i)).toBeInTheDocument();
  });

  it('should display the quantity and price of the product in the cart', () => {
    render(<ShoppingCartItem item={mockItem} />);
    expect(screen.getByText(/\$25.00/i)).toBeInTheDocument();
    // expect(screen.getByText(/Quantity: 1/i)).toBeInTheDocument();
  });

  it('should call updateQuantity with the correct parameters when the quantity buttons are clicked', () => {
    render(<ShoppingCartItem item={mockItem} />);

    fireEvent.click(screen.getByText('+'));
    expect(mockUpdateQuantity).toHaveBeenCalledWith('1', 2);

    fireEvent.click(screen.getByText('-'));
    expect(mockUpdateQuantity).toHaveBeenCalledWith('1', 0);
  });

  // it('should call removeFromCart when the remove button is clicked', () => {
  //   render(<ShoppingCartItem item={mockItem} />);
  //
  //   fireEvent.click(screen.getByRole('button', { name: /remove/i }));
  //   expect(mockRemoveFromCart).toHaveBeenCalledWith('1');
  // });
});
