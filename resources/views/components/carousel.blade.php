<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carousel</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.31.0/dist/full.js"></script>

    <style>
        .carousel-container {
            position: relative;
            width: 75%;
            /* Sesuaikan dengan lebar container */
            margin: 0 auto;
            overflow: hidden;
            border-radius: 16px;
        }

        .carousel-wrapper {
            display: flex;
            width: 100%;
            transition: transform 1s ease-in-out;
            /* Transisi geser */
        }

        .carousel-item {
            flex: 0 0 100%;
            /* Setiap item mengambil lebar penuh container */
            width: 100%;
            height: auto;
            /* Atur tinggi sesuai kebutuhan */
        }

        .carousel-item img {
            width: 100%;
            height: auto;
            border-radius: 16px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 10px;
            /* Jarak dari bagian bawah carousel */
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            /* Jarak antar indikator */
        }

        .carousel-indicators button {
            background-color: rgba(0, 0, 0, 0.5);
            /* Warna latar belakang indikator */
            border: none;
            border-radius: 50%;
            /* Membuat indikator berbentuk bulat */
            width: 12px;
            /* Lebar indikator */
            height: 12px;
            /* Tinggi indikator */
            cursor: pointer;
            transition: background-color 0.3s;
            /* Transisi warna saat hover */
        }

        .carousel-indicators button.active {
            background-color: rgba(0, 0, 0, 0.8);
            /* Warna indikator aktif */
        }

        .carousel-indicators button:hover {
            background-color: rgba(0, 0, 0, 0.8);
            /* Warna indikator saat hover */
        }
    </style>
</head>

<body>
    <div class="carousel-container flex flex-col items-center">
        <div class="carousel-wrapper rounded-box relative">
            <div class="carousel-item">
                <img
                    src="https://bdiyogyakarta.kemenperin.go.id/filebox/slideshow/f4f11ccb36812848b872bc2bd8e8f215.jpg" />
            </div>
            <div class="carousel-item">
                <img
                    src="https://bdiyogyakarta.kemenperin.go.id/filebox/slideshow/d86a1f28d57339d1ad4a64a1d9824bb7.jpg" />
            </div>
            <div class="carousel-item">
                <img
                    src="https://bdiyogyakarta.kemenperin.go.id/filebox/slideshow/19888140f508e49dfcf880635de41034.jpeg" />
            </div>
            <div class="carousel-item">
                <img
                    src="https://bdiyogyakarta.kemenperin.go.id/filebox/slideshow/599467b256f0c835d461aa7e698a0e47.jpg" />
            </div>
            <div class="carousel-item">
                <img
                    src="https://bdiyogyakarta.kemenperin.go.id/filebox/slideshow/3cf15bc1d9a5722e4000efe452066845.jpg" />
            </div>
        </div>
        <div class="carousel-indicators"></div>
    </div>

    <!-- Script -->
    <script>
        let currentIndex = 0;
        const items = document.querySelectorAll('.carousel-item');
        const indicatorsContainer = document.querySelector('.carousel-indicators');
        const totalItems = items.length;
        const wrapper = document.querySelector('.carousel-wrapper');
        let intervalId;

        function createIndicators() {
            items.forEach((_, index) => {
                const indicator = document.createElement('button');
                indicator.addEventListener('click', () => {
                    showItem(index);
                });
                indicatorsContainer.appendChild(indicator);
            });
        }

        function showItem(index) {
            currentIndex = index;
            const offset = -currentIndex * 100;
            wrapper.style.transform = `translateX(${offset}%)`;

            // Update indikator
            document.querySelectorAll('.carousel-indicators button').forEach((indicator, i) => {
                indicator.classList.toggle('active', i === currentIndex);
            });
        }

        function showNextItem() {
            showItem((currentIndex + 1) % totalItems);
        }

        function startCarousel() {
            intervalId = setInterval(showNextItem, 3000);
        }

        function stopCarousel() {
            clearInterval(intervalId);
        }

        createIndicators();
        showItem(currentIndex);
        startCarousel();

        document.querySelector('.carousel-container').addEventListener('mouseover', stopCarousel);
        document.querySelector('.carousel-container').addEventListener('mouseout', startCarousel);
    </script>

</body>

</html>
