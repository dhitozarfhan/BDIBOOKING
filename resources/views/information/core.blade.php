<x-app-layout>
    <section class="bg-gray-100 mx-40">
        <div class="container text-center">
            <div class="flex justify-between items-center mb-8">
                <i class="fas fa-plus text-purple-600 text-2xl"></i>
                <i class="fas fa-star text-orange-500 text-2xl"></i>
            </div>
            <h1 class="text-6xl font-bold text-gray-800 mb-12">{{ __('home.dip') }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach ($cores as $dt)
                    <div class="bg-white rounded-lg shadow-md hover:ring-1 hover:ring-blue-500 group">
                        <a wire:navigate href="{{ route('information.core', ['slug' => $dt->slug]) }}" class="p-4">
                            <h2 class="text-4xl text-gray-400 mb-2 group-hover:text-blue-500">{!! $dt['icon'] !!}</h2>
                            <h6 class="font-bold">{{ $dt[config('app.locale').'_name'] }}</h6>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="bg-white mt-8 pt-5">
        <div class="container">
            <div class="grid gap-4 md:gap-5">
                <div class="mx-40">
                    <h3 class="mb-6 text-3xl font-bold">
                        {!! $core->icon !!} {{ $core->{config('app.locale') . '_name'} }}
                    </h3>
                    <div class="mb-10">
                        @if (!empty($informations))
                            @forelse ($informations as $index => $info)
                                <div class="mb-4">
                                    <button onclick="toggleAccordion({{ $index }})" class="w-full flex justify-between items-center py-4 bg-gray-100 rounded-md">
                                        <span class="font-bold ml-4">{{ $info->first()->category->id_name }}</span>
                                        <span id="icon-{{ $index }}" class="font-bold mr-4 transition-transform duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                            <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                                            </svg>
                                        </span>
                                    </button>
                                    <div id="content-{{ $index }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                                        <div class="pt-4 text-slate-500">
                                            <table class="table-auto w-full">
                                                <tbody>
                                                    @foreach ($info as $item)
                                                        <tr class="border-b border-slate-200">
                                                            <td class="px-4 py-2">
                                                                <a href="{{ url('information/post/' . $item->information_id . '/' . Str::slug($item->id_title)) }}"
                                                                    class="text-blue-600 hover:text-blue-800">{{ $item->id_title }}</a>
                                                            </td>
                                                            <td width="100px">{{ $item->year }}</td>
                                                            <td width="170px" class="text-end">
                                                                @if ($item->file)
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#file-modal" data-bs-file-url="{{ $item->getFile() }} data-bs-title="{{ $item->id_title }}"
                                                                        class="text-blue-600 hover:underline"><i class="far fa-file-pdf text-red-600 mr-2"></i>Lihat</a>
                                                                    /
                                                                    <a href="{{ $item->getFile() }}" class="text-blue-600 hover:underline">Download</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="mb-6">
                                    <div role="alert" class="mb-4 relative flex w-full p-4 text-teal-700 bg-cyan-100 border border-teal-300 rounded-md">
                                        {{ __('information.information') }}
                                    </div>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        function toggleAccordion(index) {
          const content = document.getElementById(`content-${index}`);
          const icon = document.getElementById(`icon-${index}`);

          // SVG for Minus icon
          const minusSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
              <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
            </svg>
          `;

          // SVG for Plus icon
          const plusSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
              <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
            </svg>
          `;

          // Toggle the content's max-height for smooth opening and closing
          if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0';
            icon.innerHTML = plusSVG;
          } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.innerHTML = minusSVG;
          }
        }
    </script>
</x-app-layout>
