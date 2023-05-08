var timers = [];
var timmerCounter = 0;

function refreshTimer() {
    for (var i = 0; i < timmerCounter; i++) {
        clearInterval(timers[i]);
        timers[i] = null;
    }
    timers = [];
    timmerCounter = 0;
    checktimer();
}

function checktimer() {
    {
        if (timers[timmerCounter] == null) {
            timers[timmerCounter] = setInterval(function () {
                refreshCounter();
            }, 1000);
            timmerCounter++
        }
    }
    if (atrajQ("#chat_area").is(":visible")) {
        if (timers[timmerCounter] == null) {
            timers[timmerCounter] = setInterval(function () {
                refreshChat();
            }, 10000);
            timmerCounter++
        }
    }
    if (atrajQ(".c_rpchat").length) {
        if (timers[timmerCounter] == null) {
            timers[timmerCounter] = setInterval(function () {
                refreshCChat()
            }, 15000);
            timmerCounter++
        }
    }
}

atrajQ(document).ready(function () {
    refreshTimer();
});