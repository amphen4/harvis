import { fileURLToPath, URL } from 'url';
import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import laravel from 'laravel-vite-plugin';

// https://vitejs.dev/config/
export default defineConfig(
    ({ mode }) => {
    const env = loadEnv(mode, process.cwd(), ''); 
    return {
            define: {
                'process.env.VITE_API_URL': JSON.stringify(env.VITE_API_URL)
            },
            plugins: [
                laravel({
                    input: ['resources/css/app.css', 'resources/js/main.ts'],
                    refresh: true,
                }),
                vue({
                template: {
                    compilerOptions: {
                    isCustomElement: (tag) => ['v-list-recognize-title'].includes(tag)
                    }
                }
                }),
                vuetify({
                autoImport: true
                })
            ],
            resolve: {
                alias: {
                '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
                'vue-i18n': 'vue-i18n/dist/vue-i18n.esm-bundler.js'
                }
            },
            css: {
                preprocessorOptions: {
                scss: {}
                }
            },
            build: {
                chunkSizeWarningLimit: 1024 * 1024 // Set the limit to 1 MB
            },
            optimizeDeps: {
                exclude: ['vuetify'],
                entries: ['./resources/js/**/*.vue']
            }
        }
});
