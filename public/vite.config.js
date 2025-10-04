import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'public/build', // Output directory for compiled assets
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: 'ProfileDropdownHomepage/app.js', // Your main JavaScript entry point
        },
    },
});
