import Image from '@/components/ui/image';
import type { Product } from '@/types';
import { Check, Minus, Plus } from 'lucide-react';

interface ProductCardProps {
  product: Product;
  onAddToCart: () => void;
  isSelected: boolean;
  onToggleSelect: () => void;
  selectedQuantity: number;
  onUpdateSelectedQuantity: (quantity: number) => void;
}

export default function ProductCard({
  product,
  onAddToCart,
  isSelected,
  onToggleSelect,
  selectedQuantity,
  onUpdateSelectedQuantity
}: ProductCardProps) {
  return (
    <div
      className={`
      flex h-full flex-col overflow-hidden rounded-lg bg-card shadow-md 
      transition-shadow duration-300 hover:shadow-lg 
      ${isSelected ? 'ring-2 ring-primary' : ''}
    `}
    >
      <div className="relative h-48 overflow-hidden">
        <div className="absolute left-2 top-2 z-10">
          <button
            onClick={(e) => {
              e.stopPropagation();
              onToggleSelect();
            }}
            className={`
              flex h-6 w-6 items-center justify-center rounded-full 
              ${isSelected ? 'bg-primary text-white' : 'border border-gray-300 bg-white'}
            `}
          >
            {isSelected && <Check size={14} />}
          </button>
        </div>
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
          <span className="text-lg font-bold text-primary">{`$${product.price}`}</span>

          {isSelected ? (
            <div className="flex items-center rounded-md border-2">
              <button
                onClick={(e) => {
                  e.stopPropagation();
                  onUpdateSelectedQuantity(Math.max(1, selectedQuantity - 1));
                }}
                className="rounded px-2 py-1 text-primary hover:bg-gray-100"
                disabled={selectedQuantity <= 1}
              >
                <Minus size={14} />
              </button>
              <span className="px-2 py-1">{selectedQuantity}</span>
              <button
                onClick={(e) => {
                  e.stopPropagation();
                  onUpdateSelectedQuantity(selectedQuantity + 1);
                }}
                className="rounded px-2 py-1 text-primary hover:bg-gray-100"
              >
                <Plus size={14} />
              </button>
            </div>
          ) : (
            <button
              onClick={onAddToCart}
              className="rounded-md bg-primary px-3 py-1 text-sm text-muted transition-colors duration-200 hover:bg-background hover:text-muted-foreground"
            >
              Add
            </button>
          )}
        </div>
      </div>
    </div>
  );
}
