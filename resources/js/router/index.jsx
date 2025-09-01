
import {createBrowserRouter} from "react-router-dom";
import Dashboard from "../pages/Dashboard.jsx";
import Login from "../pages/Login.jsx";
import Register from "../pages/Register.jsx";
import GuestLayout from "../components/GuestLayout.jsx";
import DefaultLayout from "../components/DefaultLayout.jsx";
import Accounts from "../pages/Accounts.jsx";
import Transactions from "../pages/Transactions.jsx";
import Budgets from "../pages/Budgets.jsx";
import Bills from "../pages/Bills.jsx";
import Reports from "../pages/Reports.jsx";
import Settings from "../pages/Settings.jsx";
import Profile from "../pages/Profile.jsx";

const router = createBrowserRouter([
  {
    path: '/',
    element: <DefaultLayout />,
    children: [
      {
        path: '/dashboard',
        element: <Dashboard />
      },
      {
        path: '/',
        element: <Dashboard />
      },
      {
        path: '/accounts',
        element: <Accounts />
      },
      {
        path: '/transactions',
        element: <Transactions />
      },
      {
        path: '/budgets',
        element: <Budgets />
      },
      {
        path: '/bills',
        element: <Bills />
      },
      {
        path: '/reports',
        element: <Reports />
      },
      {
        path: '/settings',
        element: <Settings />
      },
      {
        path: '/profile',
        element: <Profile />
      },
    ]
  },
  {
    path: '/',
    element: <GuestLayout />,
    children: [
      {
        path: '/login',
        element: <Login />
      },
      {
        path: '/register',
        element: <Register />
      }
    ]
  }
])

export default router;
