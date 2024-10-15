<nav class="border-gray-100 bg-white">
    <div class="flex items-center justify-center py-4 gap-4 border-b">
        <a href="{{ route('home') }}">
            <x-logo.kemenperin />
        </a>
        <a href="{{ route('home') }}">
            <x-logo.bdiyk-corpu />
        </a>
        <label class="w-48 flex items-center rounded-md bg-gray-100">
            <input
                class="w-40 ml-1 bg-gray-100 focus:outline-none focus:border-none focus:ring-0 outline-none border-none text-gray-800 placeholder:text-gray-400"
                placeholder="Cari sesuatu..." type="text" />
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-5 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </label>
        <button
            class="inline-flex items-center px-4 py-2 bg-red-500 rounded-md font-semibold text-white gap-1 hover:bg-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path
                    d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
            </svg>
            Pendaftaran Peserta Diklat
        </button>
        <button
            class="inline-flex items-center px-4 py-2 bg-blue-500 rounded-md font-semibold text-white gap-1 hover:bg-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path fill-rule="evenodd"
                    d="M8 7a5 5 0 1 1 3.61 4.804l-1.903 1.903A1 1 0 0 1 9 14H8v1a1 1 0 0 1-1 1H6v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-2a1 1 0 0 1 .293-.707L8.196 8.39A5.002 5.002 0 0 1 8 7Zm5-3a.75.75 0 0 0 0 1.5A1.5 1.5 0 0 1 14.5 7 .75.75 0 0 0 16 7a3 3 0 0 0-3-3Z"
                    clip-rule="evenodd" />
            </svg>
            Login SIDIA
        </button>
    </div>
    <div>
        @include('layouts.partials.header-bottom')
    </div>
</nav>
