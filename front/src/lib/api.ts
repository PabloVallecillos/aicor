import axios from 'axios';
import {
  ApiLoginResponse,
  ApiListResponse,
  RequestListEndpoint,
  RequestCartAddMultipleEndpoint
} from '@/types/api';
import { JWT_LOCAL_STORAGE_KEY } from '@/constants/local-storage.tsx';
import { CartItem, Product } from '@/types';

const api = axios.create({
  timeout: 10000,
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

api.interceptors.request.use(
  (config) => {
    const authToken = localStorage.getItem(JWT_LOCAL_STORAGE_KEY);
    if (authToken) {
      config.headers.Authorization = `Bearer ${authToken}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  (res) => {
    return res.data;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export async function apiLogin(accessToken: string) {
  return await api.post<ApiLoginResponse>(`/api/auth/google`, {
    access_token: accessToken
  });
}

export async function getProducts(data: RequestListEndpoint) {
  return await api.post<ApiListResponse>(`/api/products/list`, data);
}

export const cartApi = {
  getCart: async () => {
    return await api.get<CartItem[]>('/api/cart');
  },

  addMultipleToCart: async (data: RequestCartAddMultipleEndpoint) => {
    return await api.post<CartItem>(`/api/cart/add-multiple`, data);
  },

  addToCart: async (product: Product) => {
    return await api.post<CartItem>(`/api/cart/add/${product.id}`);
  },

  removeFromCart: async (productId: string) => {
    return await api.delete(`/api/cart/remove/${productId}`);
  },

  updateQuantity: async (productId: string, quantity: number) => {
    return await api.put(`/api/cart/update/${productId}/${quantity}`);
  },

  clearCart: async () => {
    return await api.delete('/api/cart/clear');
  }
};
