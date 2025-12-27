import axios from "axios";

const API_URL = "http://localhost:8000/api";


axios.interceptors.request.use((config) => {
  const adminToken = localStorage.getItem("admin_token");

  if (adminToken) {
    config.headers.Authorization = `Bearer ${adminToken}`;
  }

  return config;
});

export const register = (data) =>
  axios.post(`${API_URL}/register`, data);

export const login = (data) =>
  axios.post(`${API_URL}/login`, data);

export const adminLogin = (data) =>
  axios.post(`${API_URL}/admin/login`, data);

export const logout = () =>
  axios.post(`${API_URL}/logout`);

export const getUsers = () => axios.get('/api/admin/users')
export const updateUser = (id, data) => axios.put(`/api/admin/users/${id}`, data)
export const deleteUser = (id) => axios.delete(`/api/admin/users/${id}`)

export const getCars = () => axios.get('/api/admin/cars')
export const addCar = (data) => axios.post('/api/admin/cars', data)
export const updateCar = (id, data) => axios.put(`/api/admin/cars/${id}`, data)
export const deleteCar = (id) => axios.delete(`/api/admin/cars/${id}`)

export const getReviews = () =>
  axios.get(`${API_URL}/admin/reviews`);

export const toggleReview = (id) =>
  axios.patch(`${API_URL}/admin/reviews/${id}/toggle`);

export const deleteReview = (id) =>
  axios.delete(`${API_URL}/admin/reviews/${id}`);


export const getRentalConditions = () => axios.get('/api/admin/rental-conditions')
export const addRentalCondition = (data) => axios.post('/api/admin/rental-conditions', data)
export const updateRentalCondition = (id, data) => axios.put(`/api/admin/rental-conditions/${id}`, data)
export const deleteRentalCondition = (id) => axios.delete(`/api/admin/rental-conditions/${id}`)

export const getFaqs = () => axios.get('/api/admin/faqs')
export const addFaq = (data) => axios.post('/api/admin/faqs', data)
export const updateFaq = (id, data) => axios.put(`/api/admin/faqs/${id}`, data)
export const deleteFaq = (id) => axios.delete(`/api/admin/faqs/${id}`)