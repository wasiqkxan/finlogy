
import React from 'react';
import ReactDOM from 'react-dom/client';
import {RouterProvider} from "react-router-dom";
import router from "./router/index.jsx";
import {AuthProvider} from "./contexts/AuthContext.jsx";

ReactDOM.createRoot(document.getElementById('app')).render(
  <React.StrictMode>
    <AuthProvider>
      <RouterProvider router={router} />
    </AuthProvider>
  </React.StrictMode>
);
