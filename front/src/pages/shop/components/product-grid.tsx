import { useGetInfiniteProducts } from '@/pages/shop/queries/queries.ts';
import { Product } from '@/types';
import { useEffect, useState } from 'react';
import Loader from '@/components/shared/loader.tsx';
import ProductCard from '@/pages/shop/components/product-card.tsx';
import { useCart } from '@/hooks/use-cart.tsx';
import { useInView } from 'react-intersection-observer';

export default function ProductGrid() {
  const { addToCart, addMultipleToCart } = useCart();
  const { ref, inView } = useInView();
  const {
    data,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
    status,
    isError,
    error
  } = useGetInfiniteProducts();
  const [selectedProducts, setSelectedProducts] = useState<Map<string, number>>(
    new Map()
  );

  useEffect(() => {
    if (inView && hasNextPage) {
      fetchNextPage();
    }
  }, [inView, hasNextPage, fetchNextPage]);

  if (status === 'error' || isError || error) {
    return (
      <div className="grid h-full place-content-center">
        Error when loading products
      </div>
    );
  }

  const products = data?.pages.flatMap((page) => page.data) || [];

  const toggleProductSelection = (productId: string) => {
    setSelectedProducts((prev) => {
      const newMap = new Map(prev);
      if (newMap.has(productId)) {
        newMap.delete(productId);
      } else {
        newMap.set(productId, 1);
      }
      return newMap;
    });
  };

  const updateSelectedQuantity = (productId: string, quantity: number) => {
    if (quantity <= 0) {
      setSelectedProducts((prev) => {
        const newMap = new Map(prev);
        newMap.delete(productId);
        return newMap;
      });
      return;
    }

    setSelectedProducts((prev) => {
      const newMap = new Map(prev);
      newMap.set(productId, quantity);
      return newMap;
    });
  };

  const addSelectedToCart = () => {
    if (selectedProducts.size === 0) return;

    const productsToAdd = Array.from(selectedProducts.entries()).map(
      ([id, quantity]) => {
        const product = products.find((p) => p.id === id);
        if (!product) throw new Error(`Product with id ${id} not found`);
        return { product, quantity };
      }
    );

    addMultipleToCart(productsToAdd);
    setSelectedProducts(new Map());
  };

  const totalSelectedItems = Array.from(selectedProducts.values()).reduce(
    (sum, quantity) => sum + quantity,
    0
  );

  return (
    <div className="relative">
      {selectedProducts.size > 0 && (
        <div className="fixed bottom-2 right-2 z-40 flex flex-col items-end space-y-2">
          <div className="flex items-center space-x-2 rounded-lg bg-accent p-3 shadow-lg">
            <span className="text-sm font-medium">
              {selectedProducts.size}{' '}
              {selectedProducts.size === 1 ? 'product' : 'products'} selected (
              {totalSelectedItems} {totalSelectedItems === 1 ? 'item' : 'items'}
              )
            </span>
            <button
              onClick={addSelectedToCart}
              className="rounded-md bg-primary px-3 py-1 text-sm text-muted transition-colors duration-200 hover:bg-background hover:text-muted-foreground"
            >
              <span>Add</span>
            </button>
          </div>
        </div>
      )}
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        {data?.pages.map((page) =>
          page.data.map((product: Product) => (
            <ProductCard
              key={product.id}
              product={product}
              onAddToCart={() => addToCart(product)}
              isSelected={selectedProducts.has(product.id)}
              onToggleSelect={() => toggleProductSelection(product.id)}
              selectedQuantity={selectedProducts.get(product.id) || 0}
              onUpdateSelectedQuantity={(quantity) =>
                updateSelectedQuantity(product.id, quantity)
              }
            />
          ))
        )}
      </div>
      <div ref={ref} className="scroll-detector"></div>
      {isFetchingNextPage && <Loader />}
    </div>
  );
}
