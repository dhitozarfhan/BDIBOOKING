<x-app-layout>
    <div class="container flex mt-6 md:mt-10 max-w-7xl md:mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-8 mx-6 md:mr-8">
                <div class=" bg-white p-6 rounded-lg shadow-lg w-full md:mb-16">
                    <h1 class="text-4xl font-bold mb-4">{{ __('information.public_question') }}</h1>
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-6">
                        {{ __('information.question_hint') }}
                    </div>
                    <form action="{{ route('question.submit') }}" method="POST" class="my-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="form-group mb-6">
                                    <label for="subject" class="block text-gray-700">
                                        <i class="fas fa-bullhorn"></i>
                                        {{ __('information.subject') }}
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="subject" value="{{ old('subject') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                    </div>
                                    @error('subject')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-6">
                                    <label for="content" class="block text-gray-700">
                                        <i class="fas fa-question"></i>
                                        {{ __('information.message') }}
                                    </label>
                                    <div class="flex items-center">
                                        <textarea name="content" class="form-control w-full p-2 border border-gray-300 rounded-md h-40">
                                            {{ old('content') }}</textarea>
                                    </div>
                                    @error('content')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-group mb-6">
                                    <label for="name" class="block text-gray-700">
                                        <i class="fas fa-user"></i>
                                        {{ __('information.name') }}
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                    </div>
                                    @error('name')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-6">
                                    <label for="mobile" class="block text-gray-700">
                                        <i class="fas fa-phone"></i>
                                        {{ __('information.mobile') }}
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="mobile" value="{{ old('mobile') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                    </div>
                                    @error('mobile')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-6">
                                    <label for="email" class="block text-gray-700">
                                        <i class="fas fa-envelope"></i>
                                        {{ __('information.email') }}
                                    </label>
                                    <div class="flex items-center">
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                    </div>
                                    @error('email')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <button type="submit" class="btn bg-blue-600 text-white text-lg w-full md:w-auto hover:bg-blue-800 transition duration-300">
                                        {{ __('information.submit') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="lg:col-span-4 mx-6 mb-8">
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
