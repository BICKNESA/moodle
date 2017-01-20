$(document).ready(function() {
    var oneSecond = 1000;
    var oneMinute = oneSecond * 60;
    var oneHour = oneMinute * 60;

    function updateTimeString() {
        var nowDate = new Date();

        var eventStartDate = new Date();
        eventStartDate.setDate(eventStartDate.getDate() + ((7 - eventStartDate.getDay()) % 7 + eventDay) % 7);
        eventStartDate.setHours(eventStartHour, 0, 0, 0);

        var eventEndDate = new Date();
        eventEndDate.setDate(eventEndDate.getDate() + ((7 - eventEndDate.getDay()) % 7 + eventDay) % 7);
        eventEndDate.setHours(eventEndHour + 1, 0, 0, 0);

        var timeUntilStart = eventStartDate.getTime() - nowDate.getTime();
        var timeUntilEnd = eventEndDate.getTime() - nowDate.getTime();


        if(timeUntilStart <= 0 && timeUntilEnd >= 0) {
            $("#et-event-timer").text("Event finishes in " + Math.floor(timeUntilEnd / oneHour) + " hours, " + Math.floor((timeUntilEnd % oneHour) / oneMinute) + " minutes, " + Math.floor(((timeUntilEnd % oneHour) % oneMinute) / oneSecond) + " seconds.");
        } else {
            if(timeUntilStart < 0) {
                eventStartDate.setDate(eventStartDate.getDate() + 7);
                eventEndDate.setDate(eventEndDate.getDate() + 7);
                var timeUntilStart = eventStartDate.getTime() - nowDate.getTime();
                var timeUntilEnd = eventEndDate.getTime() - nowDate.getTime();
            }
            $("#et-event-timer").text("Event starts in " + Math.floor(timeUntilStart / oneHour) + " hours, " + Math.floor((timeUntilStart % oneHour) / oneMinute) + " minutes, " + Math.floor(((timeUntilStart % oneHour) % oneMinute) / oneSecond) + " seconds.");
        }
    }

    window.setInterval(updateTimeString, 1000);

});
