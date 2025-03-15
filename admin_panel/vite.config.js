import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
  base: "/admin/", // <-- ВАЖНО! Если админка размещена в поддиректории
  plugins: [react()],
});
