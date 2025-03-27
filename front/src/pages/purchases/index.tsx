import { useEffect } from 'react';
import PurchaseItem from './components/purchase-item.tsx';
import { Purchase } from '@/types';
import { useGetInfiniteOrders } from '@/pages/purchases/queries/queries.ts';
import { useInView } from 'react-intersection-observer';
import Loader from '@/components/shared/loader.tsx';

export default function PurchasesPage() {
  const { ref, inView } = useInView();
  const {
    data,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
    status,
    isError,
    error
  } = useGetInfiniteOrders();

  useEffect(() => {
    if (inView && hasNextPage) {
      fetchNextPage();
    }
  }, [inView, hasNextPage, fetchNextPage]);

  if (status === 'error' || isError || error) {
    return (
      <div className="grid h-full place-content-center">
        Error when loading orders
      </div>
    );
  }

  return (
    <div className="h-full flex-1 space-y-4 overflow-y-auto p-4 pt-6 md:p-8">
      {data?.pages.map((page) =>
        page.data.map((purchase: Purchase) => (
          <PurchaseItem key={purchase.id} purchase={purchase} />
        ))
      )}
      <div ref={ref} className="scroll-detector"></div>
      {isFetchingNextPage && <Loader />}
    </div>
  );
}
