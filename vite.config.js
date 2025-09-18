import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Panel-specific entries
                'resources/css/coupons-panel.css',
                'resources/js/coupons-panel.js',
                'resources/css/reporting-panel.css',
                'resources/js/reporting-panel.js',
                'resources/css/inventory-panel.css',
                'resources/js/inventory-panel.js'
                // add others here as needed
            ],
            refresh: true,
        }),
    ],
});
