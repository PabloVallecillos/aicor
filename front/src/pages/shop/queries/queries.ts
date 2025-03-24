import { useQuery } from '@tanstack/react-query';
import type { RequestListEndpoint, ApiListResponse } from '@/types/api';
import { getProducts } from '@/lib/api';
import type { Product } from '@/types';

export const useProducts = (params: RequestListEndpoint) => {
  return useQuery<ApiListResponse<Product>>({
    queryKey: ['products', params],
    queryFn: async () => getProducts(params)
  });
};
