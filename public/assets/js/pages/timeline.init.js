var swiper = new Swiper(".slider", {
        slidesPerView: 3,
        thumbs:{
            autoScrollOffset : 1
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        breakpoints: {
            768: {
                slidesPerView: 5
            },
            1024: {
                slidesPerView: 5
            }
        }
    })

