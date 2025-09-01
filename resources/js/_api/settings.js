
import axios from "./axios.js";

export const getSettings = async () => {
  const response = await axios.get('/settings');
  return response.data;
}

export const updateSettings = async (data) => {
  const response = await axios.put('/settings', data);
  return response.data;
}
