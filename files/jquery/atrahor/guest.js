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
    //Index Countdown
    var itime5 = atrajQ("#index_time");
    if (itime5.is(":visible")) {
        var matches = itime5.html().match(/.*>([0-9]+)([^0-9]+)([0-9]+)([^0-9]+)([0-9]+)([^0-9]+)/);
        if (matches) {
            var s = parseInt(matches[1]);
            var m = parseInt(matches[3]);
            var sek = parseInt(matches[5]);
            if (timers[timmerCounter] == null) {
                timers[timmerCounter] = setInterval(function () {
                    sek--;
                    if (sek == -1) {
                        m--;
                        sek = 59;
                        if (m == -1) {
                            s--;
                            m = 59;
                            if (s == -1) {
                                s = 1
                            }
                        }
                    }
                    atrajQ("#index_time").html(
                        s + matches[2] + m + matches[4] + sek + matches[6]
                    )
                }, 1000);
                timmerCounter++
            }
        }
    }
}

atrajQ(document).ready(function () {
    refreshTimer();
});