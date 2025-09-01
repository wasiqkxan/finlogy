
import { useEffect } from "react";
import { Link, Navigate, Outlet, useNavigate } from "react-router-dom";
import useAuthContext from "../contexts/AuthContext";
import axiosClient from "../_api/axios";

export default function DefaultLayout() {
    const { user, token, setUser, setToken } = useAuthContext();
    const navigate = useNavigate();

    useEffect(() => {
        if (token && !user) {
            axiosClient.get("/user").then(({ data }) => {
                setUser(data);
            });
        }
    }, [token, user, setUser]);

    if (!token) {
        return <Navigate to="/login" />;
    }

    const handleLogout = async () => {
        try {
            await axiosClient.post("/logout");
            setToken(null);
            setUser(null);
            localStorage.removeItem("token");
            navigate("/login");
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="flex-shrink-0 flex items-center">
                                <Link to="/dashboard">Finlogy</Link>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <div className="mr-4">{user && user.name}</div>
                            <button
                                onClick={handleLogout}
                                className="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                <div className="py-6">
                    <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <Outlet />
                    </div>
                </div>
            </main>
        </div>
    );
}
