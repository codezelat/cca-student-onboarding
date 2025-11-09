import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Professional purple-focused glassmorphism palette
                primary: {
                    50: "#faf5ff",
                    100: "#f3e8ff",
                    200: "#e9d5ff",
                    300: "#d8b4fe",
                    400: "#c084fc",
                    500: "#a855f7",
                    600: "#9333ea",
                    700: "#7e22ce",
                    800: "#6b21a8",
                    900: "#581c87",
                    950: "#3b0764",
                },
                accent: {
                    50: "#faf5ff",
                    100: "#f3e8ff",
                    200: "#e9d5ff",
                    300: "#d8b4fe",
                    400: "#c084fc",
                    500: "#a855f7",
                    600: "#8b5cf6",
                    700: "#7c3aed",
                    800: "#6d28d9",
                    900: "#5b21b6",
                    950: "#4c1d95",
                },
                secondary: {
                    50: "#f5f3ff",
                    100: "#ede9fe",
                    200: "#ddd6fe",
                    300: "#c4b5fd",
                    400: "#a78bfa",
                    500: "#8b5cf6",
                    600: "#7c3aed",
                    700: "#6d28d9",
                    800: "#5b21b6",
                    900: "#4c1d95",
                },
            },
            backgroundImage: {
                "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
                "gradient-mesh":
                    "linear-gradient(135deg, #6366f1 0%, #8b5cf6 25%, #a855f7 50%, #9333ea 75%, #7c3aed 100%)",
                "gradient-purple":
                    "linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)",
                "gradient-soft":
                    "linear-gradient(135deg, #faf5ff 0%, #f5f3ff 100%)",
                "gradient-deep":
                    "linear-gradient(135deg, #6366f1 0%, #4c1d95 100%)",
            },
            backdropBlur: {
                xs: "2px",
            },
            animation: {
                float: "float 6s ease-in-out infinite",
                glow: "glow 3s ease-in-out infinite",
                blob: "blob 7s infinite",
            },
            keyframes: {
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-20px)" },
                },
                glow: {
                    "0%, 100%": { opacity: "1" },
                    "50%": { opacity: "0.5" },
                },
                blob: {
                    "0%, 100%": { transform: "translate(0px, 0px) scale(1)" },
                    "33%": { transform: "translate(30px, -50px) scale(1.1)" },
                    "66%": { transform: "translate(-20px, 20px) scale(0.9)" },
                },
            },
        },
    },

    plugins: [forms],
};
