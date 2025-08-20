@php
function renderMenu($items, $isRoot = true, $depth = 1) {
    $ulClass = $isRoot
        ? 'menu menu-horizontal bg-base-100 rounded-box'
        : 'menu bg-base-100 rounded-box';

    echo '<ul class="'.$ulClass.'">';
    foreach ($items as $item) {
        $hasChildren = isset($item->children) && $item->children->count();
        $liClass = $hasChildren ? 'dropdown dropdown-hover relative' : '';
        echo '<li class="'.$liClass.'"'.($hasChildren ? ' tabindex="0"' : '').'>';

        // Parent link
        echo '<a href="'.$item->link.'" class="flex items-center gap-2 '.($item->target_blank ? 'font-bold' : '').'"'.($item->target_blank ? ' target="_blank"' : ' wire:navigate').'>'.$item->name_locale;
        if($hasChildren){
            // Dropdown icon
            if ($depth === 1) {
                echo ' <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
            }
            else {
                //carret right
                // display carret right direction
                echo ' <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l6-6-6-6" /></svg>';
            }
        }
        echo '</a>';

        // Submenu
        if ($hasChildren) {
            if ($depth === 1) {
                // Submenu di bawah parent
                echo '<ul class="dropdown-content left-0 top-5 absolute min-w-[16rem]">';
            } else {
                // Submenu di kanan parent
                echo '<ul class="dropdown-content left-20 top-0 absolute min-w-[16rem]">';
            }
            renderMenu($item->children, false, $depth + 1);
            echo '</ul>';
        }
        echo '</li>';
    }
    echo '</ul>';
}
@endphp
<div x-data="{ open: false }" class="border-b z-50 bg-base-100 relative">
    <!-- Desktop Menu -->
    <div class="hidden md:flex justify-center">
        @php
            renderMenu($headers);
        @endphp
    </div>
    <!-- Mobile Menu Button -->
    <div class="flex md:hidden items-center justify-between px-2 py-2">
        <span class="font-bold">MENU</span>
        <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none" aria-label="Open main menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden w-full">
        @php
            renderMenu($headers, true, 1, true);
        @endphp
    </div>
</div>