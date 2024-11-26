<!--
  Слайдер на Alpine.js
  x-data: photos (массив путей или объектов)
  currentIndex: текущий индекс отображаемой фотографии
-->
<div
    x-data="sliderComponent()"
    class="relative w-full mb-4"
>

    <!-- Основной блок для фото -->
    <div class="w-full h-64 bg-gray-200 flex items-center justify-center relative overflow-hidden">

        <!-- Кнопка "Назад" -->
        <button
            class="absolute left-2 bg-gray-600 text-white px-2 py-1 rounded"
            @click="prevSlide"
        >
            ←
        </button>

        <!-- Кнопка "Вперёд" -->
        <button
            class="absolute right-2 bg-gray-600 text-white px-2 py-1 rounded"
            @click="nextSlide"
        >
            →
        </button>

        <!-- Изображение (ленивая загрузка) -->
        <template x-if="photos.length > 0">
            <img
                class="max-h-64 object-contain"
                :src="lazyPhotoUrl(currentPhoto.url)"
                :alt="`Фото №${currentIndex + 1}`"
                x-transition
            />
        </template>

        <!-- Если нет фотографий -->
        <template x-if="photos.length === 0">
            <span class="text-gray-500">Нет фотографий</span>
        </template>
    </div>

    <!-- Превью (миниатюры) - горизонтальный скролл -->
    <div class="flex space-x-2 overflow-x-auto mt-2">
        <template x-for="(photo, index) in photos" :key="photo.id">
            <img
                :src="lazyPhotoUrl(photo.thumb)"
                class="w-16 h-16 flex-shrink-0 object-cover cursor-pointer border-2"
                :class="{'border-blue-500': index === currentIndex}"
                @click="goToSlide(index)"
                :alt="`Миниатюра №${index + 1}`"
            />
        </template>
    </div>

    <!-- Скрипты Alpine внутри или отдельным файлом -->
    <script>
        // 1) Компонент слайдера
        function sliderComponent() {
            return {
                // Массив фотографий (url, thumb, id...), можно подставить с бэкенда
                photos: [
                    {
                        id: 1,
                        url: 'https://media.istockphoto.com/id/518525465/ru/%D1%84%D0%BE%D1%82%D0%BE/statue-marcus-aurelius-campidoglio-%D1%80%D0%B8%D0%BC-%D0%B8%D1%82%D0%B0%D0%BB%D0%B8%D1%8F.jpg?s=612x612&w=0&k=20&c=OGI2HnrXpp1328sEUjb5CTlT5niqGDwu6a1GvLCSwnM=',   // полноразмер
                        thumb: 'https://media.istockphoto.com/id/518525465/ru/%D1%84%D0%BE%D1%82%D0%BE/statue-marcus-aurelius-campidoglio-%D1%80%D0%B8%D0%BC-%D0%B8%D1%82%D0%B0%D0%BB%D0%B8%D1%8F.jpg?s=612x612&w=0&k=20&c=OGI2HnrXpp1328sEUjb5CTlT5niqGDwu6a1GvLCSwnM='
                    },
                    {
                        id: 2,
                        url: 'https://media.istockphoto.com/id/1449448187/ru/%D1%84%D0%BE%D1%82%D0%BE/%D0%B2%D0%B8%D0%B4%D1%8B-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%BB%D0%B8%D0%BA%D1%83%D1%8E-%D0%BA%D1%80%D0%B0%D1%81%D0%BE%D1%82%D1%83-%D1%80%D0%B8%D0%BC%D0%B0-%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%BE%D0%BB%D0%B8%D0%B9%D1%81%D0%BA%D0%B8%D0%B9-%D1%85%D0%BE%D0%BB%D0%BC.jpg?s=612x612&w=0&k=20&c=Prb0ebXVHL6C5XkQL9SKxvf4nWSq0ZvOcnkhJE3Z3Ms=',
                        thumb: 'https://media.istockphoto.com/id/1449448187/ru/%D1%84%D0%BE%D1%82%D0%BE/%D0%B2%D0%B8%D0%B4%D1%8B-%D0%BD%D0%B0-%D0%B2%D0%B5%D0%BB%D0%B8%D0%BA%D1%83%D1%8E-%D0%BA%D1%80%D0%B0%D1%81%D0%BE%D1%82%D1%83-%D1%80%D0%B8%D0%BC%D0%B0-%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%BE%D0%BB%D0%B8%D0%B9%D1%81%D0%BA%D0%B8%D0%B9-%D1%85%D0%BE%D0%BB%D0%BC.jpg?s=612x612&w=0&k=20&c=Prb0ebXVHL6C5XkQL9SKxvf4nWSq0ZvOcnkhJE3Z3Ms='
                    },
                    {
                        id: 3,
                        url: 'https://st2.depositphotos.com/3886483/9320/i/450/depositphotos_93204844-stock-photo-equestrian-statue-of-marco-aurelio.jpg',
                        thumb: 'https://st2.depositphotos.com/3886483/9320/i/450/depositphotos_93204844-stock-photo-equestrian-statue-of-marco-aurelio.jpg'
                    },
                ],

                currentIndex: 0,

                // Получить текущую фотографию
                get currentPhoto() {
                    return this.photos[this.currentIndex];
                },

                // Перейти к следующему слайду
                nextSlide() {
                    if (this.currentIndex < this.photos.length - 1) {
                        this.currentIndex++;
                    } else {
                        // Можно зациклить: this.currentIndex = 0
                        // или ничего не делать
                    }
                },

                // Перейти к предыдущему слайду
                prevSlide() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    } else {
                        // Зациклить? this.currentIndex = this.photos.length - 1
                    }
                },

                // Перейти к конкретному индексу
                goToSlide(index) {
                    this.currentIndex = index;
                },

                // Ленивая загрузка: возвращаем URL только если в viewport.
                // Упрощённый подход: мы возвращаем URL всегда,
                // но можно доработать IntersectionObserver
                lazyPhotoUrl(url) {
                    // Допустим, у нас есть префикс /lazy/?img=...
                    // или мы просто возвращаем url напрямую
                    return url;
                },
            }
        }

        // 2) Компонент для ленивой загрузки видео
        function videoComponent() {
            return {
                isLoaded: false,
                videoSrc: '',

                loadVideo() {
                    this.isLoaded = true;
                    // Здесь подставляем реальный src (например, YouTube-видео)
                    // Изначально videoSrc = '' (пуста), чтобы не грузилось
                    this.videoSrc = 'https://www.youtube.com/embed/XXXXXXX';
                }
            }
        }
    </script>

</div>
<!-- /Слайдер -->
