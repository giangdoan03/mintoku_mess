document.addEventListener('DOMContentLoaded', function () {
    const postList = document.querySelector('.post-list');
    const isPostListPage = postList !== null;

    // if (!isPostListPage) {
    //     console.log('Not on the post list page');
    //     return; // Dừng thực thi nếu không phải trang danh sách bài viết
    // }

    let touchstartX = 0;
    let touchendX = 0;
    let mouseDownX = 0;
    let mouseUpX = 0;
    const threshold = 50; // Ngưỡng để xác định vuốt

    // function handleSwipe(post) {
    //     if (post) {
    //         post.classList.add('swipe-left');
    //         setTimeout(() => {
    //             const link = post.querySelector('.post-info a').href;
    //             console.log(`Redirecting to: ${link}`);
    //             window.location.href = link;
    //         }, 300); // Thời gian đồng bộ với transition trong CSS
    //     }
    // }
    //
    // function getPostFromPosition(positionX) {
    //     const postItems = postList.querySelectorAll('.post-item');
    //     let currentPost = null;
    //
    //     postItems.forEach(post => {
    //         const rect = post.getBoundingClientRect();
    //         // Check if the swipe position is within the boundaries of the post item
    //         if (positionX >= rect.left && positionX <= rect.right && positionX >= rect.top && positionX <= rect.bottom) {
    //             currentPost = post;
    //             console.log('currentPost:', currentPost);
    //         }
    //     });
    //
    //     return currentPost;
    // }

    // Xử lý sự kiện chạm trên thiết bị di động
    // postList.addEventListener('touchstart', function (event) {
    //     touchstartX = event.changedTouches[0].screenX;
    // });
    //
    // postList.addEventListener('touchend', function (event) {
    //     touchendX = event.changedTouches[0].screenX;
    //     if (touchendX < touchstartX - threshold) {
    //         const post = getPostFromPosition(touchendX);
    //         handleSwipe(post);
    //     }
    // });

    // Xử lý sự kiện kéo chuột trên máy tính
    // postList.addEventListener('mousedown', function (event) {
    //     mouseDownX = event.screenX;
    // });
    //
    // postList.addEventListener('mouseup', function (event) {
    //     mouseUpX = event.screenX;
    //     if (mouseUpX < mouseDownX - threshold) {
    //         const post = getPostFromPosition(mouseUpX);
    //         handleSwipe(post);
    //     }
    // });

    // Xử lý sự kiện kéo chuột trên máy tính (chạy khi di chuyển chuột)
    // postList.addEventListener('mousemove', function (event) {
    //     if (event.buttons === 1) { // Kiểm tra nếu nút chuột trái đang nhấn
    //         mouseUpX = event.screenX;
    //         if (mouseUpX < mouseDownX - threshold) {
    //             const post = getPostFromPosition(mouseUpX);
    //             if (post) {
    //                 const link = post.querySelector('.post-info a');
    //                 if (link) {
    //                     console.log(`Link: ${link.href}, Text: ${link.textContent}`);
    //                 }
    //             }
    //             handleSwipe(post);
    //         }
    //     }
    // });
});

// document.addEventListener('DOMContentLoaded', function() {
//     var backButton = document.getElementById('backButton');
//     var currentDomain = window.location.hostname;
//     var currentURL = window.location.href;
//
//     console.log(currentDomain);
//
//     // Kiểm tra xem trang hiện tại có phải là localhost không
//     if (currentDomain === 'localhost') {
//         // Nếu là localhost, hiển thị nút
//         // backButton.style.display = 'block';
//         backButton.onclick = function() {
//             window.location.href = 'http://localhost/mintoku_mobile'; // Đường dẫn đến trang chủ của localhost
//         };
//     } else if (currentDomain === 'mintoku.mobile.vccdev.vn' && currentURL === 'https://mintoku.mobile.vccdev.vn/') {
//         // Nếu là trang chính, ẩn nút
//         backButton.style.display = 'none';
//     } else {
//         // Nếu không phải localhost hay trang chính, hiển thị nút
//         // backButton.style.display = 'block';
//         backButton.onclick = function() {
//             window.location.href = 'https://mintoku.mobile.vccdev.vn'; // Đường dẫn đến trang chủ của bạn
//         };
//     }
// });

// function goHome() {
//     window.location.href = 'https://mintoku.mobile.vccdev.vn'; // Đường dẫn đến trang chủ của bạn
// }
//
// document.addEventListener('DOMContentLoaded', function() {
//     // Lấy tất cả các liên kết trong danh sách bài viết
//     var postLinks = document.querySelectorAll('.post-info a');
//
//     // Thêm sự kiện click cho từng liên kết
//     postLinks.forEach(function(link) {
//         link.addEventListener('click', function(event) {
//             event.preventDefault(); // Ngăn chặn hành động mặc định (chuyển trang ngay lập tức)
//
//             var targetUrl = this.getAttribute('href'); // Lấy URL của trang đích
//
//             // Thêm hiệu ứng fade out cho toàn bộ trang
//             document.body.style.transition = 'opacity 0.5s';
//             document.body.style.opacity = 0;
//
//             // Sau khi hiệu ứng hoàn tất, chuyển hướng đến trang đích
//             setTimeout(function() {
//                 window.location.href = targetUrl;
//             }, 500); // Đợi 500ms để hoàn thành hiệu ứng fade out
//         });
//     });
// });

// document.addEventListener('DOMContentLoaded', function() {
//     // Khi trang đã tải xong, thêm lớp "loaded" vào body để ẩn spinner
//     document.body.classList.add('loaded');
//
//     // Lấy tất cả các liên kết trong danh sách bài viết
//     var postLinks = document.querySelectorAll('.post-info a');
//
//     // Thêm sự kiện click cho từng liên kết
//     postLinks.forEach(function(link) {
//         link.addEventListener('click', function(event) {
//             event.preventDefault(); // Ngăn chặn hành động mặc định (chuyển trang ngay lập tức)
//
//             var targetUrl = this.getAttribute('href'); // Lấy URL của trang đích
//
//             // Hiển thị lại spinner khi chuyển trang
//             document.body.classList.remove('loaded');
//
//             // Thêm hiệu ứng fade out cho toàn bộ trang
//             document.body.style.transition = 'opacity 0.5s';
//             document.body.style.opacity = 0;
//
//             // Sau khi hiệu ứng hoàn tất, chuyển hướng đến trang đích
//             setTimeout(function() {
//                 window.location.href = targetUrl;
//             }, 500); // Đợi 500ms để hoàn thành hiệu ứng fade out
//         });
//     });
// });

jQuery(document).ready(function($) {
    var currentUrl = new URL(window.location.href);
    var year = currentUrl.pathname.split('/').slice(-2, -1)[0];

    // Khi chọn tỉnh
    $('#province-filter').on('change', function() {
        var provinceSlug = $('#province-filter option:selected').text().toLowerCase().replace(/\s/g, '-');

        if (provinceSlug) {
            // Xóa tham số `university` khỏi URL
            currentUrl.searchParams.delete('university');
            history.pushState(null, '', currentUrl.toString());

            // Cập nhật URL với tham số `province`
            currentUrl.searchParams.set('province', provinceSlug);
            history.pushState(null, '', currentUrl.toString());

            // Gọi AJAX để lấy dữ liệu trường đại học và công việc
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_universities',
                    province_id: $(this).val(),
                    year: year
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#university-filter').html(data.universities);
                    $('#posts-container').html(data.jobs);
                }
            });
        }
    });

    // Khi chọn trường đại học
    $('#university-filter').on('change', function() {
        var universitySlug = $('#university-filter option:selected').text().toLowerCase().replace(/\s/g, '-');

        if (universitySlug) {
            // Cập nhật URL với tham số `university`
            currentUrl.searchParams.set('university', universitySlug);
            history.pushState(null, '', currentUrl.toString());

            // Gọi AJAX để lấy bài viết
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_posts_by_university',
                    university_id: $(this).val(),
                    year: year
                },
                success: function(response) {
                    $('#posts-container').html(response);
                }
            });
        }
    });

    // Tải lại trang và duy trì kết quả filter dựa trên URL
    var provinceSlug = currentUrl.searchParams.get('province');
    var universitySlug = currentUrl.searchParams.get('university');
    var firstLoad = true; // Cờ để kiểm tra lần tải đầu tiên

    if (provinceSlug) {
        // Tự động chọn giá trị tỉnh từ URL
        $('#province-filter option').each(function() {
            if ($(this).text().toLowerCase().replace(/\s/g, '-') === provinceSlug) {
                $(this).prop('selected', true);
            }
        });

        // Gọi AJAX để load các trường đại học khi tỉnh đã được chọn
        $('#province-filter').trigger('change');
    }

    if (universitySlug) {
        // Chỉ gọi `ajaxComplete` một lần khi lần đầu tải trang
        $(document).one('ajaxComplete', function() {
            if (firstLoad) {
                $('#university-filter option').each(function() {
                    if ($(this).text().toLowerCase().replace(/\s/g, '-') === universitySlug) {
                        $(this).prop('selected', true);
                    }
                });

                // Gọi AJAX để load bài viết khi trường đại học đã được chọn
                $('#university-filter').trigger('change');
                firstLoad = false; // Đánh dấu lần tải đầu tiên đã hoàn tất
            }
        });
    }
});









