<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carousel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.31.0/dist/full.js"></script>

    <style>
        .carousel-container {
            position: relative;
            width: 75%;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 16px;
        }

        .carousel-wrapper {
            display: flex;
            width: 100%;
            transition: transform 1s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 100%;
            width: 100%;
            height: auto;
        }

        .carousel-item img {
            width: 100%;
            height: auto;
            border-radius: 16px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
        }

        .carousel-indicators button {
            background-color: rgb(200, 200, 200);
            border: none;
            width: 32px;
            height: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .carousel-indicators button.active {
            background-color: rgb(255, 255, 255);
        }

        .carousel-indicators button:hover {
            background-color: rgb(225, 225, 225);
        }
    </style>
</head>

<body>
    <div class="carousel-container flex flex-col items-center">
        <div class="carousel-wrapper rounded-box relative">
            @foreach ($slideshows as $slideshow)
                <div class="carousel-item relative">
                    <img src="{{ $slideshow->getSlideImage() }}" alt="{{ $slideshow->id_title }}" />
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-50"></div>
                    <div class="absolute inset-x-0 bottom-0 h-auto flex flex-col justify-start bg-black bg-opacity-50 p-4 m-4 max-w-full">
                        @if (!empty($slideshow->id_title))
                            <p class="text-white text-left text-xl font-bold mb-4">{{ $slideshow->id_title }}</p>
                        @endif
                        @if (!empty($slideshow->id_description))
                            <p class="text-white text-left">{{ $slideshow->id_description }}</p>
                        @endif
                        @if (!empty($slideshow->path))
                            <a href="{{ $slideshow->path }}" class="btn btn-primary mt-2">{{ __('home.more') }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="carousel-indicators">
            @foreach ($slideshows as $index => $slideshow)
                <button type="button" data-bs-target="#slideshow-carousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>

    <!-- Script -->
    <script>
        let currentIndex = 0;
        const items = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.carousel-indicators button');
        const totalItems = items.length;
        const wrapper = document.querySelector('.carousel-wrapper');
        let intervalId;

        function showItem(index) {
            currentIndex = index;
            const offset = -currentIndex * 100;
            wrapper.style.transform = `translateX(${offset}%)`;

            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === currentIndex);
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showItem(index);
            });
        });

        function showNextItem() {
            showItem((currentIndex + 1) % totalItems);
        }

        function startCarousel() {
            intervalId = setInterval(showNextItem, 3000);
        }

        function stopCarousel() {
            clearInterval(intervalId);
        }

        showItem(currentIndex);
        startCarousel();

        document.querySelector('.carousel-container').addEventListener('mouseover', stopCarousel);
        document.querySelector('.carousel-container').addEventListener('mouseout', startCarousel);
    </script>

</body>

</html>
