import axios from 'axios';

const api = axios.create({
  timeout: 10000,
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
