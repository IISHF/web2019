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
});
