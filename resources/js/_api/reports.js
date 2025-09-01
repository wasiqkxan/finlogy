
import axios from "./axios.js";

export const getReports = async (reportType) => {
  const response = await axios.get(`/reports/${reportType}`);
  return response.data;
}
