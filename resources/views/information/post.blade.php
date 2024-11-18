<x-app-layout>
    <div class="w-full px-10 grid grid-cols-4 gap-10 my-10">
        <div class="md:col-span-3 col-span-4">
            <h1 class="text-4xl font-bold">{{ $post->id_title }}</h1>
            <p class="pt-4 text-justify">{!! $post->id_content !!}</p>
            @if ($post->file)
                <iframe src="{{ $post->getFile() }}" style="width:100%; height:700px;" class="my-3"></iframe>
                <a href="{{ $post->getFile() }}" class="btn border-black font-medium"><i class="fas fa-file-pdf text-red-600"></i>Download</a>
            @endif
        </div>
        <div id="side-bar">
            @include('components.archive-widget')
        </div>
    </div>
</x-app-layout>
