
import { Navigate, Outlet } from "react-router-dom";
import useAuthContext from "../contexts/AuthContext";

export default function GuestLayout() {
    const { token } = useAuthContext();

    if (token) {
        return <Navigate to="/dashboard" />;
    }

    return (
        <div>
            <Outlet />
        </div>
    );
}
