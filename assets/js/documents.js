const items = $('.document-item');
const count = items.length;
const width = 240;

if (items && window.matchMedia("(max-width: 767px)").matches) {
    const result_width = width * count;

    $('.documents-row').css('width', result_width + 'px')
}
