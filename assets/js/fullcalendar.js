import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrapPlugin from '@fullcalendar/bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const calendarTournamentEl = document.getElementById('calendarTournament');
    const calendarTodayEl = document.getElementById('calendarToday');
    const calendarOtherEl = document.getElementById('calendarOther');

    if (calendarTodayEl) {
        const calendarToday = new Calendar(calendarTodayEl, {
            plugins: [dayGridPlugin, bootstrapPlugin],
            themeSystem: 'bootstrap',
            header: {
                left: '',
                center: 'title',
                right: ''
            }
        });
        calendarToday.render();
    }
    if (calendarTournamentEl) {
        const calendarTournament = new Calendar(calendarTournamentEl, {
            plugins: [dayGridPlugin, bootstrapPlugin],
            themeSystem: 'bootstrap',
            header: {
                left: '',
                center: 'title',
                right: ''
            },
            viewRender: function (view) {
                const title = view.title;
                $("#externalTitle").html(title);
            }
        });
        calendarTournament.render();
    }
    if (calendarOtherEl) {
        const calendarOther = new Calendar(calendarOtherEl, {
            plugins: [dayGridPlugin, bootstrapPlugin],
            themeSystem: 'bootstrap',
            header: {
                left: '',
                center: 'title',
                right: ''
            }
        });
        calendarOther.render();
    }
});
