import { useState, useEffect, useRef, useCallback } from 'react';
import ProductCard from './product-card';
import type { Product } from '@/types';
import { MOCK_PRODUCTS } from '@/constants/data.ts';
import { useCart } from '@/hooks/use-cart.tsx';
import Loader from '@/components/shared/loader.tsx';

export default function ProductGrid() {
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [, setPage] = useState(1);
  const { addToCart } = useCart();
  const loaderRef = useRef<HTMLDivElement | null>(null);

  const fetchProducts = useCallback(async () => {
    if (isLoading) return;
    setIsLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 1000));
    setProducts((prev) => [
      ...prev,
      ...MOCK_PRODUCTS.map((p) => ({ ...p, id: `${p.id}-${Math.random()}` }))
    ]);
    setPage((prev) => prev + 1);
    setIsLoading(false);
  }, [isLoading]);

  useEffect(() => {
    fetchProducts();
    // eslint-disable-next-line
  }, []);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && !isLoading) {
          fetchProducts();
        }
      },
      { rootMargin: '100px' }
    );

    if (loaderRef.current) observer.observe(loaderRef.current);
    return () => observer.disconnect();
  }, [fetchProducts, isLoading]);

  return (
    <div className="relative">
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        {products.map((product) => (
          <ProductCard
            key={product.id}
            product={product}
            onAddToCart={() => addToCart(product)}
          />
        ))}
      </div>
      <div ref={loaderRef} className="scroll-detector h-10"></div>
      {isLoading && <Loader />}
    </div>
  );
}
