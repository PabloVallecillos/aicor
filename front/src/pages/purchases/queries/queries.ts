import { useInfiniteQuery } from '@tanstack/react-query';
import type { ApiListResponse } from '@/types/api';
import type { Product } from '@/types';
import { getOrders } from '@/lib/api';

export const useGetInfiniteOrders = () => {
  return useInfiniteQuery<ApiListResponse<Product>>({
    queryKey: ['orders'],
    queryFn: async ({ pageParam = 1 }) => {
      return await getOrders({ page: pageParam, per_page: 10 });
    },
    initialPageParam: 1,
    getNextPageParam: (data: ApiListResponse<Product>) => {
      if (data.current_page < data.last_page) {
        return data.current_page + 1;
      }
      return undefined;
    },
    retry: false
  });
};
