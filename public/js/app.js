document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.catalog-items-wrapper');
    const items = document.querySelector('.catalog-items');
    const pagination = document.querySelector('.catalog-pagination');

    // Проверяем существование элементов
    if (!wrapper || !items || !pagination) return;

    const cardsPerPage = 12;
    const totalCards = items.children.length;
    const totalPages = Math.ceil(totalCards / cardsPerPage);

    let currentPage = 0;

    // Создаём точки пагинации
    for(let i = 0; i < totalPages; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if(i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            currentPage = i;
            updateSlider();
        });
        pagination.appendChild(dot);
    }

    function updateSlider() {
        const rowsPerPage = 4; // 4 ряда × 3 карточки = 12 карточек
        const rowHeight = items.children[0].offsetHeight + 20; // высота карточки + gap
        const translateY = -(currentPage * rowsPerPage * rowHeight);
        items.style.transform = `translateY(${translateY}px)`;

        // Обновляем активную точку
        document.querySelectorAll('.dot').forEach((d, idx) => {
            d.classList.toggle('active', idx === currentPage);
        });
    }
});

// Мобильное меню
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const burger = document.querySelector('.mobile-menu-toggle');
    if (navMenu) {
        navMenu.classList.toggle('active');
    }
    if (burger) {
        burger.classList.toggle('active');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.querySelector('select[name="sort"]');
    if (!sortSelect) return;
    
    const form = sortSelect.closest('form');
    if (form) {
        sortSelect.addEventListener('change', function () {
            form.submit();
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {

    // popup
    const popup = document.getElementById('filterPopup');
    const openFilter = document.getElementById('openFilter');
    const closeFilter = document.getElementById('closeFilter');
    
    if (popup && openFilter) {
        openFilter.onclick = () => popup.style.display = 'block';
    }
    if (popup && closeFilter) {
        closeFilter.onclick = () => popup.style.display = 'none';
    }

    // авто-сортировка
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect && sortSelect.form) {
        sortSelect.addEventListener('change', () => sortSelect.form.submit());
    }

});

document.addEventListener('DOMContentLoaded', () => {

    const slider = document.getElementById('reviewsSlider');
    if (!slider) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', e => {
        isDown = true;
        startX = e.clientX;
        scrollLeft = slider.scrollLeft;
        slider.style.cursor = 'grabbing';
    });

    window.addEventListener('mouseup', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mousemove', e => {
        if (!isDown) return;
        e.preventDefault();
        const dx = e.clientX - startX;
        slider.scrollLeft = scrollLeft - dx;
    });

});



document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reviewForm');

    if (!form) return;

    form.addEventListener('submit', (e) => {
        if (!window.isAuth) {
            e.preventDefault();
            showToast('Сначала нужно авторизоваться');
        }
    });
});

function showToast(text) {
    let toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerText = text;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}


document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.rating .star');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.dataset.value;
            ratingInput.value = value;

            stars.forEach(s => {
                s.classList.toggle('active', s.dataset.value <= value);
            });
        });
    });
});

/*Лайки и дизлайки*/

document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const reviewId = btn.dataset.id;
        const type = btn.classList.contains('like-btn') ? 'like' : 'dislike';

        if (!window.isAuth) {
            showToast('Сначала нужно авторизоваться');
            return;
        }

        try {
            const res = await fetch(`/reviews/${reviewId}/react-simple`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ type })
            });

            const data = await res.json();

            if (data.success) {
                const review = btn.closest('.review-reactions').parentNode;

                review.querySelector('.likes-count').innerText = data.likes;
                review.querySelector('.dislikes-count').innerText = data.dislikes;

                const likeBtn = review.querySelector('.like-btn');
                const dislikeBtn = review.querySelector('.dislike-btn');

                likeBtn.classList.toggle('active', data.userReaction === 'like');
                dislikeBtn.classList.toggle('active', data.userReaction === 'dislike');
            }
        } catch (err) {
            console.error(err);
        }
    });
});

/*Галерея*/



/* Смена фотки */
function changeImage(src) {
    document.getElementById('activeImage').src = src;
}

/*Маршрут и точки*/
document.addEventListener("DOMContentLoaded", function () {

    const mapBlock = document.getElementById('map');
    if (!mapBlock) return;

    const points = JSON.parse(mapBlock.dataset.route);

    if (points.length === 0) return;

    // создаём карту
    const map = L.map('map').setView([points[0].lat, points[0].lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let latlngs = [];

    points.forEach(point => {

        const latlng = [point.lat, point.lng];
        latlngs.push(latlng);

        const popupContent = `
            <div style="width:200px">
                ${point.image ? `<img src="/storage/${point.image}" style="width:100%; border-radius:10px; margin-bottom:5px;">` : ''}
                <strong>${point.title}</strong>
                <p style="font-size:13px">${point.description ?? ''}</p>
            </div>
        `;

        L.marker(latlng)
            .addTo(map)
            .bindPopup(popupContent);
    });

    // линия маршрута
    L.polyline(latlngs).addTo(map);

    map.on('click', function(e) {
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');

        if (latInput && lngInput) {
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;
        }

});

});


