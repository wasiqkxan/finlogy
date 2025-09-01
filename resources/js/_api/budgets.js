
import axios from "./axios.js";

export const getBudgets = async () => {
  const response = await axios.get('/budgets');
  return response.data;
}

export const createBudget = async (data) => {
  const response = await axios.post('/budgets', data);
  return response.data;
}

export const updateBudget = async (id, data) => {
  const response = await axios.put(`/budgets/${id}`, data);
  return response.data;
}

export const deleteBudget = async (id) => {
  const response = await axios.delete(`/budgets/${id}`);
  return response.data;
}
