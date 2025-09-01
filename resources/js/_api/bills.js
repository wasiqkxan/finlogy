
import axios from "./axios.js";

export const getBills = async () => {
  const response = await axios.get('/bills');
  return response.data;
}

export const createBill = async (data) => {
  const response = await axios.post('/bills', data);
  return response.data;
}

export const updateBill = async (id, data) => {
  const response = await axios.put(`/bills/${id}`, data);
  return response.data;
}

export const deleteBill = async (id) => {
  const response = await axios.delete(`/bills/${id}`);
  return response.data;
}
