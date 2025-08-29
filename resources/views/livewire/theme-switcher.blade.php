<!-- //make align middle all of items -->
<label class="flex cursor-pointer gap-3 items-center">
    <i class="bi bi-brightness-high-fill text-warning text-xl"></i>
    <input type="checkbox" class="toggle theme-controller"
        wire:click="toggle"
        @checked($mode==='prefersdark' ) />    
    <i class="bi bi-moon-fill text-base-content text-xl"></i>
</label>