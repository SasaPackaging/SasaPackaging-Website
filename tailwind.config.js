/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/views/**/*.php",
    "./app/**/*.php",
    "./config/landing.php",
  ],

  // Preflight (Tailwind's global reset) is OFF while old pages still use Bootstrap.
  // Turn it on (delete this block) once every page is converted.
  corePlugins: {
    preflight: false,
  },

  theme: {
    extend: {
      colors: {
        // Your palette: #F6E2E9 · #FEFAF3 · #B8CFB3 · #84A282
        blush: { DEFAULT: "#f6e2e9", tint: "#fbf0f4" },
        ivory: "#fefaf3",
        sage: {
          DEFAULT: "#b8cfb3",
          soft: "#eef4ec",
          deep: "#84a282",
          ink: "#4d6b4f", // darker sage for small text (readable on ivory)
        },
        ink: "#1f2d22",
        forest: "#2b3f2f",
        body: "#425246",
        muted: "#5b6b5e",
        line: { DEFAULT: "#e4e9df", strong: "#d3decf" },
      },
      fontFamily: {
        sans: [
          "Inter",
          "system-ui",
          "-apple-system",
          "Segoe UI",
          "Arial",
          "sans-serif",
        ],
        head: ["Outfit", "Inter", "system-ui", "sans-serif"],
        display: ['"Cormorant Garamond"', "Georgia", "serif"],
      },
      borderRadius: {
        card: "16px",
      },
      boxShadow: {
        soft: "0 1px 2px rgba(31,45,34,.05), 0 6px 18px rgba(31,45,34,.06)",
        lift: "0 16px 36px rgba(31,45,34,.12)",
        luxe: "0 22px 48px rgba(31,45,34,.14)",
      },
      maxWidth: {
        site: "1180px",
      },
    },
  },
  plugins: [],
};
