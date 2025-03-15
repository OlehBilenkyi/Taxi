import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import App from "./App";
import Dashboard from "./pages/Dashboard";
import Orders from "./pages/Orders";
import { CarsPage as Cars } from "./pages/Cars";


ReactDOM.createRoot(document.getElementById("root")).render(
  <BrowserRouter basename="/admin"> {/* <-- добавил basename */}
    <Routes>
      <Route path="/" element={<App />} />
      <Route path="/dashboard" element={<Dashboard />} />
      <Route path="/orders" element={<Orders />} />
      <Route path="/cars" element={<Cars />} />
    </Routes>
  </BrowserRouter>
);
