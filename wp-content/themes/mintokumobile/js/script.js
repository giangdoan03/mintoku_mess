document.addEventListener('DOMContentLoaded', function () {
    const postList = document.querySelector('.post-list');
    const isPostListPage = postList !== null;

    if (!isPostListPage) {
        console.log('Not on the post list page');
        return; // Dừng thực thi nếu không phải trang danh sách bài viết
    }

    let touchstartX = 0;
    let touchendX = 0;
    let mouseDownX = 0;
    let mouseUpX = 0;
    const threshold = 50; // Ngưỡng để xác định vuốt

    function handleSwipe(post) {
        if (post) {
            post.classList.add('swipe-left');
            setTimeout(() => {
                const link = post.querySelector('.post-info a').href;
                console.log(`Redirecting to: ${link}`);
                window.location.href = link;
            }, 300); // Thời gian đồng bộ với transition trong CSS
        }
    }

    function getPostFromPosition(positionX) {
        const postItems = postList.querySelectorAll('.post-item');
        let currentPost = null;

        postItems.forEach(post => {
            const rect = post.getBoundingClientRect();
            if (positionX >= rect.left && positionX <= rect.right) {
                currentPost = post;
            }
        });

        return currentPost;
    }

    // Xử lý sự kiện chạm trên thiết bị di động
    postList.addEventListener('touchstart', function (event) {
        touchstartX = event.changedTouches[0].screenX;
    });

    postList.addEventListener('touchend', function (event) {
        touchendX = event.changedTouches[0].screenX;
        if (touchendX < touchstartX - threshold) {
            const post = getPostFromPosition(touchendX);
            handleSwipe(post);
        }
    });

    // Xử lý sự kiện kéo chuột trên máy tính
    postList.addEventListener('mousedown', function (event) {
        mouseDownX = event.screenX;
    });

    postList.addEventListener('mouseup', function (event) {
        mouseUpX = event.screenX;
        if (mouseUpX < mouseDownX - threshold) {
            const post = getPostFromPosition(mouseUpX);
            handleSwipe(post);
        }
    });

    // Xử lý sự kiện kéo chuột trên máy tính (chạy khi di chuyển chuột)
    postList.addEventListener('mousemove', function (event) {
        if (event.buttons === 1) { // Kiểm tra nếu nút chuột trái đang nhấn
            mouseUpX = event.screenX;
            if (mouseUpX < mouseDownX - threshold) {
                const post = getPostFromPosition(mouseUpX);
                if (post) {
                    const link = post.querySelector('.post-info a');
                    if (link) {
                        console.log(`Link: ${link.href}, Text: ${link.textContent}`);
                    }
                }
                handleSwipe(post);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var backButton = document.getElementById('backButton');
    var currentPath = window.location.pathname;

    console.log(currentPath)

    // Kiểm tra xem trang hiện tại có phải là trang chính không
    if (currentPath === '/mintoku.mobile.vccdev.vn/' || currentPath === '/mintoku.mobile.vccdev.vn/') {
        // Nếu là trang chính, ẩn nút
        backButton.style.display = 'none';
    } else {
        // Nếu không phải trang chính, hiển thị nút
        backButton.style.display = 'block';
    }
});

function goHome() {
    window.location.href = 'https://mintoku.mobile.vccdev.vn'; // Đường dẫn đến trang chủ của bạn
}

