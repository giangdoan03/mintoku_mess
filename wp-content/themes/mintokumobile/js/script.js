document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.querySelector('.wrapper');
    const loading = document.getElementById('loading');
    const helloContainer = document.getElementById('hello-container');
    const postTitle = document.getElementById('post-title');
    const postBody = document.getElementById('post-body');
    const header_s = document.getElementById('header_s');
    const footer_s = document.getElementById('footer_s');
    let startY, startX;
    let currentIndex = 0;
    let isLoading = false;
    let isInitialLoad = true;
    let isEndOfPosts = false; // Flag to track end of posts

    const SWIPE_THRESHOLD = 50; // Threshold for swipe
    const ANGLE_THRESHOLD = 30;
    const BASE_URL = window.location.hostname === 'localhost' ? 'http://localhost/mintoku_mobile' : 'https://your-live-domain.com';
    let totalPages = null;

    // Fetch posts from WordPress API
    async function fetchPost(index) {
        if (isEndOfPosts) return; // Stop fetching if end of posts reached

        try {
            loading.style.display = 'flex';

            const response = await fetch(`${BASE_URL}/wp-json/wp/v2/posts?per_page=1&page=${index}`);

            if (response.ok) {
                const posts = await response.json();

                if (!totalPages) {
                    totalPages = parseInt(response.headers.get('X-WP-TotalPages'));
                }

                if (posts.length === 0 || index > totalPages) {
                    console.log('No more posts');
                    isEndOfPosts = true; // Set flag to true
                    isLoading = false;
                    return;
                }

                const post = posts[0];
                if (isInitialLoad || !document.querySelector(`.item[data-id="${post.id}"]`)) {
                    displayPost(post);
                }
                isInitialLoad = false; // After initial load, we don't need this check anymore
            } else {
                const errorData = await response.json();
                console.error('Failed to fetch posts:', response.status, errorData);
            }
        } catch (error) {
            console.error('Error fetching posts:', error);
        } finally {
            loading.style.display = 'none';
            isLoading = false;
        }
    }


    // Hàm lấy URL của ảnh đại diện từ ID ảnh
    async function getFeaturedImageUrl(mediaId) {
        try {
            const response = await fetch(`http://localhost/mintoku_mobile/wp-json/wp/v2/media/${mediaId}`);
            const media = await response.json();
            return media.source_url; // Hoặc thuộc tính phù hợp với URL ảnh
        } catch (error) {
            console.error('Error fetching media:', error);
            return 'path/to/placeholder-image.jpg'; // Ảnh placeholder nếu có lỗi
        }
    }

    // Display post in the wrapper
    // Cập nhật hàm displayPost
    async function displayPost(post) {
        const item = document.createElement('div');
        item.className = 'item';
        item.dataset.id = post.id; // Add data-id attribute to track the post

        // URL của ảnh placeholder mặc định
        // const placeholderImage = '../images/placeholder.png';
        //
        // let imageUrl = placeholderImage;
        let imageUrl = '';

        if (post.featured_media) {
            imageUrl = await getFeaturedImageUrl(post.featured_media);
        }

        item.innerHTML = `
        <div class="post-content">
            <img src="${imageUrl}" alt="${post.title.rendered}" class="post-image"/>
            <h2>${post.title.rendered}</h2>
            <p>${post.excerpt.rendered}</p>
        </div>
    `;
        wrapper.appendChild(item);

        item.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        item.addEventListener('touchend', function (e) {
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const distanceX = endX - startX;
            const distanceY = endY - startY;
            const absDistanceX = Math.abs(distanceX);
            const absDistanceY = Math.abs(distanceY);
            const angle = Math.atan2(absDistanceY, absDistanceX) * (180 / Math.PI);

            if (absDistanceX > SWIPE_THRESHOLD && angle < ANGLE_THRESHOLD) {
                if (distanceX < 0) {
                    showHelloPage(post);
                }
            }
        });
    }

    // Function to display detailed post view
    function showHelloPage(post) {
        if (postTitle && postBody) {
            postTitle.textContent = post.title.rendered;
            postBody.innerHTML = `
                <p>${post.content.rendered}</p>
            `;
        }
        helloContainer.style.display = 'flex';
        setTimeout(() => {
            helloContainer.classList.add('visible');
            page_content.classList.add('dl_show');
            header_s.classList.add('dl_show');
            footer_s.classList.add('dl_show');
        }, 10);
    }

    // Function to hide detailed post view and go back to list
    function hideHelloPage() {
        helloContainer.classList.remove('visible');
        page_content.classList.remove('dl_show');
        header_s.classList.remove('dl_show');
        footer_s.classList.remove('dl_show');
        setTimeout(() => {
            helloContainer.style.display = 'none';
        }, 500);
    }

    // Back button click event to hide detailed post view
    document.getElementById('back-button').addEventListener('click', hideHelloPage);

    // Handle vertical swipe to navigate through posts
    wrapper.addEventListener('touchstart', function (e) {
        startY = e.touches[0].clientY;
    });

    wrapper.addEventListener('touchend', function (e) {
        const endY = e.changedTouches[0].clientY;
        const distanceY = endY - startY;

        if (Math.abs(distanceY) > 50 && !isLoading) {
            if (distanceY < 0) {
                moveToNextItem();
            } else {
                moveToPreviousItem();
            }
        }
    });

    // Move to the next post
    function moveToNextItem() {
        if (!isLoading && !isEndOfPosts) {
            if (currentIndex < totalPages - 1) {
                isLoading = true;
                currentIndex++;
                fetchPost(currentIndex + 1).then(() => {
                    wrapper.style.top = `-${currentIndex * 100}vh`;
                }).catch(() => {
                    currentIndex--; // Revert index on error
                }).finally(() => {
                    isLoading = false;
                });
            } else {
                console.log('No more posts to fetch.');
                // Nếu đã đến bài viết cuối cùng, bạn có thể cần cập nhật totalPages hoặc xử lý khác
                isEndOfPosts = true;
                isLoading = false;
            }
        }
    }
// Move to the previous post
    function moveToPreviousItem() {
        if (!isLoading) {
            if (currentIndex > 0) {
                isLoading = true;
                currentIndex--;
                // Cập nhật vị trí của wrapper để chuyển đến bài viết trước đó
                wrapper.style.top = `-${currentIndex * 100}vh`;

                // Kiểm tra xem bài viết đã được tải trước đó chưa
                const existingPost = document.querySelector(`.item[data-id="${currentIndex + 1}"]`);
                if (existingPost) {
                    isLoading = false;
                    return; // Không cần gọi API nếu bài viết đã được tải
                }

                // Nếu bài viết chưa được tải, gọi API để tải bài viết
                fetchPost(currentIndex + 1).catch(() => {
                    currentIndex++; // Revert index on error
                }).finally(() => {
                    isLoading = false;
                });
            } else {
                // Đang ở bài viết đầu tiên
                // Bạn có thể làm mới tổng số bài viết nếu cần, ví dụ:
                console.log('This is the first post.');
                // Có thể cập nhật tổng số bài viết hoặc các thao tác khác nếu cần
                // Tùy thuộc vào yêu cầu của bạn
                isEndOfPosts = false; // Đặt lại nếu cần
            }
        }
    }




    // Initial fetch for the first post
    fetchPost(currentIndex + 1);
});

$(document).ready(function () {
    var mySwiper = new Swiper(".swiper-container", {
        spaceBetween: 0,
        slidesPerView: 1,
        centeredSlides: true,
        roundLengths: true,
        loop: true,
        loopAdditionalSlides: 30,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const countryCheckboxes = document.querySelectorAll('input[name="taxonomy-country[]"]');
    const provinceWrappers = document.querySelectorAll('.taxonomy-province');

    countryCheckboxes.forEach(countryCheckbox => {
        countryCheckbox.addEventListener('change', function () {
            const countryId = this.value;
            const isChecked = this.checked;

            provinceWrappers.forEach(wrapper => {
                if (wrapper.dataset.parentCountryId === countryId) {
                    wrapper.style.display = isChecked ? '' : 'none';
                }
            });
        });
    });

    // Hiển thị các tỉnh cho quốc gia đã chọn khi trang được tải
    countryCheckboxes.forEach(countryCheckbox => {
        const countryId = countryCheckbox.value;
        const isChecked = countryCheckbox.checked;

        provinceWrappers.forEach(wrapper => {
            if (wrapper.dataset.parentCountryId === countryId) {
                wrapper.style.display = isChecked ? '' : 'none';
            }
        });
    });
});

