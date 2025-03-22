import Image from '@/components/ui/image';
import type { Product } from '@/types';

interface ProductCardProps {
  product: Product;
  onAddToCart: () => void;
}

export default function ProductCard({
  product,
  onAddToCart
}: ProductCardProps) {
  return (
    <div className="flex h-full flex-col overflow-hidden rounded-lg bg-card shadow-md transition-shadow duration-300 hover:shadow-lg">
      <div className="relative h-48 overflow-hidden">
        <Image
          src={product.image || '/placeholder.svg'}
          alt={product.name}
          className="h-full w-full object-cover"
        />
      </div>
      <div className="flex flex-grow flex-col p-4">
        <h3 className="mb-1 truncate text-lg font-semibold text-primary">
          {product.name}
        </h3>
        <p className="mb-2 line-clamp-2 flex-grow text-sm text-primary">
          {product.description}
        </p>
        <div className="mt-auto flex items-center justify-between">
          <span className="text-lg font-bold text-primary">{`$${product.price.toFixed(2)}`}</span>
          <button
            onClick={onAddToCart}
            className="rounded-md bg-primary px-3 py-1 text-sm text-muted transition-colors duration-200 hover:bg-background hover:text-muted-foreground"
          >
            Add
          </button>
        </div>
      </div>
    </div>
  );
}
