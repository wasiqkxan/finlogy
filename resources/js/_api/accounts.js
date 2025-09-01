
import axios from "./axios.js";

export const getAccounts = async () => {
  const response = await axios.get('/accounts');
  return response.data;
}

export const createAccount = async (data) => {
  const response = await axios.post('/accounts', data);
  return response.data;
}

export const updateAccount = async (id, data) => {
  const response = await axios.put(`/accounts/${id}`, data);
  return response.data;
}

export const deleteAccount = async (id) => {
  const response = await axios.delete(`/accounts/${id}`);
  return response.data;
}
