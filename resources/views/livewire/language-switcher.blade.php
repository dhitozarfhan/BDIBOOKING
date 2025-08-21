<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn">
        {{ strtoupper($locale) }}
        <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"/></svg>
    </div>
    <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
        @foreach($available as $code => $label)
        <li>
            <a wire:click="setLocale('{{ $code }}')"
               @class(['active' => $locale === $code])>
                {{ $label }}
            </a>
        </li>
        @endforeach
    </ul>
</div>