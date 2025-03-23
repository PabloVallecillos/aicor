import axios from 'axios';

const api = axios.create({
  timeout: 10000,
  baseURL: import.meta.env.VITE_API_BASE_URL ?? '',
  headers: {
    'Content-Type': 'application/json'
  }
});

api.interceptors.request.use(
  (config) => {
    // Modify request before send
    return config;
  },
  (error) => {
    // Handle errors before send
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  (response) => {
    // Handle success responses
    return response;
  },
  (error) => {
    // Handle errors on response
    console.log('Response error:', error);
    return Promise.reject(error);
  }
);

export async function apiLogin(code: string) {
  try {
    const res = await axios.post(`/api/auth/google`, { code });
    return res.data;
  } catch (error) {
    console.log(error);
    return error;
  }
}
