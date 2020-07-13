import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrapPlugin from '@fullcalendar/bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const calendarTournamentEl = document.getElementById('calendarTournament');
    let calendar;

    if (!calendarTournamentEl) {
        return;
    }

    const createCalendar = function (node, options) {
        calendar = new Calendar(node, {
            plugins: [dayGridPlugin, bootstrapPlugin],
            header: {
                left: '',
                center: 'title',
                right: ''
            },
            ...options
        });

        return calendar;
    };

    // render tournament calendar on page load
    createCalendar(calendarTournamentEl).render();
    const tabHeight = $(calendarTournamentEl).height();

    const destroyCalendar = () => {
        calendar && calendar.destroy();
    };

    const clearContainer = (container) => {
        $(container).empty();
    };

    const renderCalendar = (calendar) => {
        return setTimeout(() => {
            hideLoader();
            calendar.render();
        }, 200)
    };

    const getTabPaneId = (e) => {
        const target = $(e.target);
        let tabPaneId;

        if (target.is('span')) {
            tabPaneId = target.parent().attr("href");
        } else {
            tabPaneId = target.attr("href");
        }

        return tabPaneId
    };

    const getEmptyCalendarContainer = (e) => {
        destroyCalendar();

        const tabPaneId = getTabPaneId(e);
        const container = document.querySelector(tabPaneId + ' .calendar-container');
        clearContainer(container);

        return container;
    };

    const showLoader = () => {
        $('.lds-dual-ring').each((i, el) => {
            $(el).height(tabHeight).show();
        });
    };

    const hideLoader = () => {
        $('.lds-dual-ring').each((i, el) => {
            $(el).hide();
        });
    };

    // render new calendar on tabs switch
    $('a[data-calendar="tab"]').on('click', function (e) {
        const container = getEmptyCalendarContainer(e);

        showLoader();

        createCalendar(container);
        renderCalendar(calendar);
    });
});
