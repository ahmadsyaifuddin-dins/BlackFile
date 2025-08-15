<div x-show="sidebarOpen" @click="sidebarOpen = false"
x-transition:enter="transition-opacity ease-linear duration-300"
x-transition:enter-end="opacity-100"
x-transition:leave="transition-opacity ease-linear duration-300"
x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
class="fixed inset-0 z-10 md:hidden" aria-hidden="true"></div>
</div>