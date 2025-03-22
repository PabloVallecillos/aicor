import type { Purchase } from '@/types';

interface PurchaseItemProps {
  purchase: Purchase;
}

export default function PurchaseItem({ purchase }: PurchaseItemProps) {
  const formattedDate = new Intl.DateTimeFormat('en-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(purchase.date);

  return (
    <div className="overflow-hidden rounded-lg bg-card shadow-md">
      <div className="border-b p-4">
        <div className="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
          <div>
            <h3 className="text-lg font-semibold text-primary">
              {purchase.id}
            </h3>
            <p className="text-sm text-primary">{formattedDate}</p>
          </div>
          <div className="flex items-center gap-3">
            <span className="font-bold text-primary">
              ${purchase.total.toFixed(2)}
            </span>
          </div>
        </div>
      </div>

      <div className="flex items-center justify-end p-4">
        <div className="flex items-center gap-2">
          <span className="text-sm text-primary">
            {purchase.items.length}{' '}
            {purchase.items.length === 1 ? 'product' : 'products'}
          </span>
        </div>
      </div>
    </div>
  );
}
