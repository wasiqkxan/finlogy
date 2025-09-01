
import axios from "axios";

const axiosClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api",
});

axiosClient.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    config.headers.Authorization = `Bearer ${token}`;
    return config;
});

axiosClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const { response } = error;
        if (response.status === 401) {
            localStorage.removeItem("token");
            // window.location.reload(); // Consider a more graceful redirect
        }
        throw error;
    }
);

export default axiosClient;
