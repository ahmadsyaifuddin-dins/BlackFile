<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel + Tailwind v4</title>
     @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <script>
        // Check for saved theme preference or use system preference
        (function() {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 text-gray-800 dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 dark:text-gray-100 font-sans transition-colors duration-300">
    <div class="min-h-screen flex flex-col items-center justify-center p-4 relative">
        <!-- Theme Toggle Button -->
        <button id="theme-toggle" type="button" class="absolute top-4 right-4 p-2 rounded-lg bg-white/30 dark:bg-gray-800/30 backdrop-blur-md border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
            <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>

        <div class="max-w-2xl w-full bg-white/70 dark:bg-white/5 backdrop-blur-md rounded-2xl border border-gray-200 dark:border-white/10 p-8 shadow-xl dark:shadow-2xl transition-all hover:shadow-indigo-500/10 dark:hover:shadow-indigo-500/20">
            <h1 class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500 bg-clip-text text-transparent mb-6">
                Welcome to <span class="animate-pulse">v4</span>
            </h1>
            
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                The future of CSS with Laravel & Vite
            </p>
            
            <a href="https://github.com/ahmad-syaifuddin/tailwind4-best-practice-landing-page-laravel" target="_blank"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-medium text-white transition-all transform hover:-translate-y-1 inline-flex items-center gap-2">
                📖 View Layout Best Practice
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            
            <div class="mt-8 flex gap-4 flex-wrap">
                <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 rounded-full text-xs font-mono">Laravel 10</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 rounded-full text-xs font-mono">Tailwind v4</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 rounded-full text-xs font-mono">Vite</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 rounded-full text-xs font-mono">Dark Mode</span>
            </div>
        </div>

        <!-- Stylish Footer -->
        <footer class="mt-12 text-center text-gray-500 dark:text-gray-400 text-sm">
            <div class="flex items-center justify-center gap-2">
                <span>Crafted with</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
                <span>by</span>
                <a href="https://github.com/ahmadsyaifuddin-dins" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                    Ahmad Syaifuddin
                </a>
            </div>
            <div class="mt-2 opacity-75">
                © {{ now()->year }} All rights reserved
            </div>
        </footer>
    </div>
 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Function to update icon visibility
            function updateThemeIcon() {
                if (document.documentElement.classList.contains('dark')) {
                    themeToggleDarkIcon.classList.add('hidden');
                    themeToggleLightIcon.classList.remove('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                }
            }

            // Initialize icon on page load
            updateThemeIcon();

            themeToggle.addEventListener('click', function() {
                // Toggle dark class
                document.documentElement.classList.toggle('dark');
                
                // Update localStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                }
                
                // Update icon
                updateThemeIcon();
            });
        });
    </script>
</body>
</html>