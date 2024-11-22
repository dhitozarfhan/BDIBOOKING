@props(['post', 'title', 'summary', 'category', 'date', 'type', 'image',])
<div class="card p-5 mb-5 border rounded-lg bg-white shadow-md">
    <div class="flex flex-row">
        <!-- Thumbnail -->
        <div class="w-1/3 overflow-hidden rounded-lg">
            <div class="relative">
                <img src="{{ $post->getThumbnailImage() }}" alt="Thumbnail" class="object-cover rounded-lg" width="230" height="200">
                <div class="absolute top-3 left-2">
                    <a href="" class="p-2 bg-blue-600 text-white text-sm font-medium rounded">
                        <i class="far fa-folder-open"></i>
                        {{ $category }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="w-2/3">
            <a href="{{ route('news.post', [
                    'year' => $post->time_stamp->format('Y'),
                    'month' => $post->time_stamp->format('m'),
                    'category' => $post->category->category_id,
                    'news' => $post->news_id,
                    'title' => Str::slug($post->id_title)])
                }}">
                <h3 class="text-3xl font-bold mb-3 hover:text-blue-600 transition">{{ $title }}</h3>
            </a>
            <p class="text-gray-700 mb-4">
                {{ Str::limit($summary, 250, '...') }}
                <a href="" class="text-blue-600 hover:underline">baca selengkpanya</a>
            </p>
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full overflow-hidden">
                    <img src="{{ asset('storage/profile-photos/user_man.jpg') }}" alt="Admin" class="w-full h-full object-cover">
                </div>
                <div class="ml-3">
                    <a href="" class="font-semibold text-gray-800 hover:text-blue-600">Ade Aulia Ramadhan</a>
                    <a href="">
                        <p class="text-sm text-red-700">{{ $date }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
