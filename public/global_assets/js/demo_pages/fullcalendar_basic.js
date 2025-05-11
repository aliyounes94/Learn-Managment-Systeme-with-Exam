// Setup module
var FullCalendarBasic = function() {

    //
    // Setup module components
    //

    // Basic calendar
    var _componentFullCalendarBasic = function() {
        if (!$().fullCalendar) {
            console.warn('Warning - fullcalendar.min.js is not loaded.');
            return;
        }

        // Add demo events for May 2025
        var events = [
            {
                title: 'Military Celebrate',
                start: '2025-05-06'
            },
            {
                title: 'Cleaning Event',
                start: '2025-05-10',
                end: '2025-05-15'
            },
            {
                id: 999,
                title: 'walk Event',
                start: '2025-05-18T16:00:00'
            },
            {
                id: 999,
                title: 'walk Event',
                start: '2025-05-22T16:00:00'
            },
            {
                title: 'Conference',
                start: '2025-05-25',
                end: '2025-05-27'
            },
            {
                title: 'Meeting',
                start: '2025-05-12T10:30:00',
                end: '2025-05-12T12:30:00'
            },
            {
                title: 'Lunch',
                start: '2025-05-12T12:00:00'
            },
            {
                title: 'Birthday Party',
                start: '2025-05-15T07:00:00'
            },
            {
                title: 'Search on Google',
                url: 'http://google.com/',
                start: '2025-05-28'
            }
        ];

        // Initialization
        $('.fullcalendar-basic').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: '2025-05-01', // First day of current month
            editable: true,
            events: events,
            eventLimit: true,
            isRTL: false, // Set to true if you're using RTL layout
            defaultView: 'month',
            navLinks: true,
            selectable: true,
            selectHelper: true
        });
    };

    return {
        init: function() {
            _componentFullCalendarBasic();
        }
    }
}();


// Initialize module
document.addEventListener('DOMContentLoaded', function() {
    FullCalendarBasic.init();
});