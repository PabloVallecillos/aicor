import { useState, useEffect, useRef, useCallback } from 'react';
import ProductCard from './product-card';
import type { Product } from '@/types';
import { useCart } from '@/hooks/use-cart.tsx';
import Loader from '@/components/shared/loader.tsx';
import { useProducts } from '@/pages/shop/queries/queries.ts';

export default function ProductGrid() {
  const [products, setProducts] = useState<Product[]>([]);
  const [page, setPage] = useState(1);
  const { addToCart } = useCart();
  const loaderRef = useRef<HTMLDivElement | null>(null);
  const { data, isLoading, isFetching, isError } = useProducts({
    per_page: 12 * 2,
    page: page
  });

  const fetchProducts = useCallback(async () => {
    if (data && !isLoading && !isFetching) {
      setProducts((prevProducts) => [...prevProducts, ...data.data]);
    }
  }, [data, isLoading, isFetching]);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && !isLoading && !isFetching) {
          setPage((prevPage) => prevPage + 1);
        }
      },
      { rootMargin: '100px' }
    );

    if (loaderRef.current) observer.observe(loaderRef.current);
    return () => observer.disconnect();
  }, [isLoading, isFetching]);

  useEffect(() => {
    fetchProducts();
  }, [data, fetchProducts]);

  return (
    <div className="relative">
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        {!isError &&
          products.map((product) => (
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
