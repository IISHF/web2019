import $ from 'jquery';

$(document).ready(function () {
    $("#mainTopSlider").slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        dotsClass: "vertical-dots"
    });

    $("#newsSlider").slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        dotsClass: "slick-dots custom-dots"
    });

    $("#photoSlider").slick({
        centerMode: true,
        centerPadding: '90px',
        slidesToShow: 3,
        arrows: true,
        dots: true,
        dotsClass: "slick-dots custom-dots",
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    centerMode: false,
                    slidesToShow: 1
                }
            }
        ]
    });

    $("#memberCarousel").slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        centerPadding: '90px',
        arrows: true,
        dots: true,
        dotsClass: "slick-dots custom-dots",
        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 5
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: false,
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 768,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 1
                }
            }
        ]
    });

    $("#partnersCarousel").slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        centerPadding: '90px',
        arrows: true,
        dots: true,
        dotsClass: "slick-dots custom-dots",
        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 5
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: false,
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 768,
                settings: {
                    arrows: true,
                    centerMode: false,
                    slidesToShow: 1
                }
            }
        ]
    });

    $('.committees-carousel')
        .on('init', function (event, slick) {
            $('.committees-carousel').each(function (index, element) {
                const dots = $(this).find('.circle-dots');
                const right_arrow = $(this).find('.slick-next');
                const left_arrow = $(this).find('.slick-prev');
                const dots_width = dots.innerWidth();
                const arrow_width = right_arrow.width();
                const right = dots_width + arrow_width;

                left_arrow.css('right', right + 'px');
            })
        })
        .slick({
            dots: true,
            dotsClass: "slick-dots circle-dots",
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        dots: false,
                        arrows: false,
                        centerMode: true,
                        slidesToShow: 1
                    }
                }
            ]
        });
});
