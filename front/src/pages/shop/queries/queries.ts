import { useInfiniteQuery } from '@tanstack/react-query';
import type { ApiListResponse } from '@/types/api';
import type { Product } from '@/types';
import { getProducts } from '@/lib/api';

export const useGetInfiniteProducts = () => {
  return useInfiniteQuery<ApiListResponse<Product>>({
    queryKey: ['products'],
    queryFn: async ({ pageParam = 1 }) => {
      return await getProducts({ page: pageParam, per_page: 24 });
    },
    initialPageParam: 1,
    getNextPageParam: (data: ApiListResponse<Product>) => {
      if (data.current_page < data.last_page) {
        return data.current_page + 1;
      }
      return undefined;
    },
    retry: false,
  });
};
