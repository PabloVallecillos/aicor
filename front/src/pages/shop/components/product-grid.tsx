import { useGetInfiniteProducts } from '@/pages/shop/queries/queries.ts';
import { Product } from '@/types';
import { useEffect } from 'react';
import Loader from '@/components/shared/loader.tsx';
import ProductCard from '@/pages/shop/components/product-card.tsx';
import { useCart } from '@/hooks/use-cart.tsx';
import { useInView } from 'react-intersection-observer';

export default function ProductGrid() {
  const { addToCart } = useCart();
  const { ref, inView } = useInView();
  const { data, fetchNextPage, hasNextPage, isFetchingNextPage, status } =
    useGetInfiniteProducts();

  useEffect(() => {
    if (inView && hasNextPage) {
      fetchNextPage();
    }
  }, [inView, hasNextPage, fetchNextPage]);

  if (status === 'error') {
    return (
      <div className="grid h-full place-content-center">
        Error when loading products
      </div>
    );
  }

  return (
    <div className="relative">
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        {data?.pages.map((page) =>
          page.data.map((product: Product) => (
            <ProductCard
              key={product.id}
              product={product}
              onAddToCart={() => addToCart(product)}
            />
          ))
        )}
      </div>
      <div ref={ref} className="scroll-detector"></div>
      {isFetchingNextPage && <Loader />}
    </div>
  );
}
