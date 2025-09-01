
import axios from "./axios.js";

export const getProfile = async () => {
  const response = await axios.get('/profile');
  return response.data;
}

export const updateProfile = async (data) => {
  const response = await axios.put('/profile', data);
  return response.data;
}
