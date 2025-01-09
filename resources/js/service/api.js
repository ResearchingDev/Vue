// src/services/api.js
import axios from 'axios';
import router from '../router'; // Import Vue Router for redirecting

const apiUrl = import.meta.env.VITE_APP_API_BASE_URL;

const axiosInstance = axios.create({
  baseURL: apiUrl,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to log errors
axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    // Log error before rejecting the promise
    console.error('Request Error:', error); // Log the request error
    return Promise.reject(error);
  }
);

// Response interceptor to log errors and handle 401 for redirection
axiosInstance.interceptors.response.use(
  (response) => response.data,
  (error) => {
    // Log the error response
    console.error('Response Error:', error); // Log the response error
    // If the error has a response (like 4xx, 5xx), log additional details
    if (error.response) {
      // Handle 401 Unauthorized error
      if (error.response.status === 401) {
        console.error('Unauthorized, redirecting to login page...');
        
        localStorage.removeItem('User');
        localStorage.removeItem('token');
        // Redirect to login page
        router.push('/login');
      }

      console.error('Error Response:', error.response);
    } else {
      // For errors that don't have a response (network issues, timeout, etc.)
      console.error('Error without Response:', error.message);
    }
    return Promise.reject({
      data: error.response?.data,
      message: error.response?.data?.message || error.message,
      status_code: error.response?.status || 500 // Default to 500 if no status
    });
  }
);

export default axiosInstance;
