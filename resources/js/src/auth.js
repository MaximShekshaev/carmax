import axios from "axios";

const API_URL = "http://localhost:8000/api";

export const register = (data) => axios.post(`${API_URL}/register`, data);
export const login = (data) => axios.post(`${API_URL}/login`, data);
export const logout = (token) =>
  axios.post(`${API_URL}/logout`, {}, {
    headers: { Authorization: `Bearer ${token}` },
  });
