<label class="swap swap-rotate">
    <input type="checkbox"
        wire:click="toggle"
        @checked($mode==='prefersdark' ) />

    <i class="swap-off bi bi-brightness-high-fill text-yellow-500 text-2xl"></i>
    <i class="swap-on bi bi-moon text-white text-2xl font-bold"></i>

</label>