<x-app-layout>
    <div class="w-full grid grid-cols-4 gap-10 my-10">
        <div class="md:col-span-3 col-span-4">
            <h1 class="text-4xl font-bold">{{ $page->title }}</h1>
            <p class="pt-4 text-justify">{!! $page->content !!}</p>
            @if ($page->document)
                <iframe src="{{ asset('storage/information/' . $page->document) }}" style="width:100%; height:700px;"></iframe>
                <a href="{{ asset('storage/information/' . $page->document) }}" class="btn btn-primary">Download File</a>
            @endif
        </div>
        <div id="side-bar">
            @include('components.archive-widget')
        </div>
    </div>
</x-app-layout>
