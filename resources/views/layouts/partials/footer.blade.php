@php
function renderFooterMenu($items, $isRoot = true) {
echo '<ul>';
    foreach ($items as $item) {
    $hasChildren = isset($item->children) && $item->children->count();
    $liClass = $item->depth == 0 ? 'float-left w-1/2 px-2' : ' ms-4';
    echo '<li class="'.$liClass.'">';

        // Parent link
        echo '<a href="'.$item->link.'" class="text-white hover:underline"'.($item->target_blank ? ' target="_blank"' : ' wire:navigate').'>'.$item->name_locale.'</a>';

        // Submenu
        if ($hasChildren) {
        echo '<ul>';
            renderFooterMenu($item->children, false);
            echo '</ul>';
        }
        echo '</li>';
    }
    echo '</ul>';
}
@endphp
<footer class="footer sm:footer-horizontal bg-neutral text-neutral-content p-4">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <div class="py-3">
                <img src="{{ asset('images/bdi-yogyakarta-white.svg') }}" alt="Balai Diklat Industri Yogyakarta" class="h-10">
                <p class="py-3 text-white">
                    {!! __('Footer About Message') !!}
                </p>
                <nav class="mt-4">
                    <div class="flex items-center gap-4">
                        <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook" class="text-white hover:text-gray-400">
                            <i class="bi bi-facebook text-2xl"></i>
                        </a>

                        <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram" class="text-white hover:text-gray-400">
                            <i class="bi bi-instagram text-2xl"></i>
                        </a>

                        <a href="https://x.com" target="_blank" rel="noopener" aria-label="X (Twitter)" class="text-white hover:text-gray-400">
                            <i class="bi bi-twitter-x text-2xl"></i>
                        </a>

                        <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube" class="text-white hover:text-gray-400">
                            <i class="bi bi-youtube text-4xl"></i>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="py-3">
                @if(!empty($footers))
                @php
                renderFooterMenu($footers);
                @endphp
                @endif
            </div>
            <div class="py-3">
                <p class="font-bold text-xl text-white">{{ __('Contact Us') }}</p>
                <ul class="text-white py-2 flex gap-2">
                    <i class="bi bi-geo-alt"></i>
                    <p class="text-sm">Jalan Gedongkuning 140, Kotagede, Yogyakarta 5517</p>
                </ul>
                <ul class="text-white py-2 flex gap-2">
                    <i class="bi bi-telephone"></i>
                    <p class="text-sm">
                        <b>{{ __('Telephone') }} : </b> (0274) 373912
                    </p>
                </ul>
                <ul class="text-white py-2 flex gap-2">
                    <i class="bi bi-envelope"></i>
                    <p class="text-sm">
                        <b>Email : </b>
                        <a href="mailto:bdiyogyakarta@kemenperin.go.id" class="hover:text-gray-500">
                            bdiyogyakarta@kemenperin.go.id
                        </a>
                    </p>
                </ul>
            </div>
        </div>
    </div>
</footer>
<footer class="ooter sm:footer-horizontal footer-center bg-neutral text-neutral-content">
    <div class="container mx-auto px-4 py-4 border-t border-neutral-700">
        <p class="text-white text-center text-sm">© 2025 BDI Yogyakarta Kementerian Perindustrian</p>
    </div>
</footer>