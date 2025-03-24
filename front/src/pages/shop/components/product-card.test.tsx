import { it, expect, describe, beforeEach, vi } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, render, screen } from '@testing-library/react';
import ProductCard from './product-card.tsx';
import type { Product } from '@/types';

vi.mock('@/components/ui/image', () => ({
  default: ({ alt }: { alt: string }) => (
    <img alt={alt} data-testid="mocked-image" />
  )
}));

describe('ProductCard', () => {
  beforeEach(() => {
    cleanup();
  });

  it('should render the ProductCard component', () => {
    const product: Product = {
      id: '1',
      name: 'Test Product',
      price: 20,
      image: 'image.jpg',
      description: 'Test product description'
    };
    render(<ProductCard product={product} onAddToCart={() => {}} />);
    expect(screen.getByText('Test Product')).toBeInTheDocument();
    expect(screen.getByText('Test product description')).toBeInTheDocument();
    expect(screen.getByText('$20')).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /add/i })).toBeInTheDocument();
  });
});
