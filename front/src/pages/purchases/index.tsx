import { useState, useEffect } from 'react';
import PurchaseItem from './components/purchase-item.tsx';
import type { Purchase } from '@/types';
import { mockPurchases } from '@/constants/data.ts';

export default function PurchasesPage() {
  const [purchases, setPurchases] = useState<Purchase[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const fetchPurchaseHistory = async () => {
      setIsLoading(true);
      try {
        setPurchases(mockPurchases);
      } catch (error) {
        console.error('Error fetching purchase history:', error);
      } finally {
        setIsLoading(false);
      }
    };

    fetchPurchaseHistory();
  }, []);

  return (
    <div className="h-full flex-1 space-y-4 overflow-y-auto p-4 pt-6 md:p-8">
      {isLoading ? (
        <div className="space-y-4">
          {[...Array(3)].map((_, index) => (
            <div
              key={index}
              className="animate-pulse rounded-lg bg-gray-100 p-4"
            >
              <div className="mb-4 h-6 w-1/4 rounded bg-gray-200"></div>
              <div className="mb-2 h-4 w-1/3 rounded bg-gray-200"></div>
              <div className="mb-4 h-20 rounded bg-gray-200"></div>
              <div className="h-4 w-1/5 rounded bg-gray-200"></div>
            </div>
          ))}
        </div>
      ) : purchases.length === 0 ? (
        <div className="rounded-lg bg-white py-12 text-center shadow">
          <h3 className="mb-2 text-lg font-medium text-gray-900">
            No tienes compras realizadas
          </h3>
          <p className="text-gray-500">
            Cuando realices una compra, aparecerá aquí.
          </p>
        </div>
      ) : (
        <div className="space-y-6">
          {purchases.map((purchase) => (
            <PurchaseItem key={purchase.id} purchase={purchase} />
          ))}
        </div>
      )}
    </div>
  );
}
