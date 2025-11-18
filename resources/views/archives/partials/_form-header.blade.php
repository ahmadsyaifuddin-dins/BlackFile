<!-- Global Error Banner -->
<div x-show="errorMessage !== ''" 
     x-cloak
     x-transition 
     class="bg-red-900/20 border border-red-500/50 p-3 rounded text-red-400 text-sm mb-4">
    <p class="font-bold">> UPLOAD FAILED:</p>
    <p x-text="errorMessage"></p>
</div>