!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e():"function"==typeof define&&define.amd?define(e):e()}(0,function(){"use strict";function t(e){return(t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(e)}var e=function(t){return"string"==typeof t},n=function(t){return t instanceof Blob};(function(){"navigator"in this||(this.navigator={});"function"!=typeof this.navigator.sendBeacon&&(this.navigator.sendBeacon=function(t,o){var i=this.event&&this.event.type,r="unload"===i||"beforeunload"===i,f="XMLHttpRequest"in this?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");f.open("POST",t,!r),f.withCredentials=!0,f.setRequestHeader("Accept","*/*"),e(o)?(f.setRequestHeader("Content-Type","text/plain;charset=UTF-8"),f.responseType="text"):n(o)&&o.type&&f.setRequestHeader("Content-Type",o.type);try{f.send(o)}catch(t){return!1}return!0}.bind(this))}).call("object"===("undefined"==typeof window?"undefined":t(window))?window:{})});

let sendB = function (state){
    if (!navigator.sendBeacon) return;

    let base = window.location.origin;
    let url = base+'/ia';
    state = Event.type ? Event.type : state;
    // Create the data to send
    let data = "state=" + state + "&location=" + window.location.href;
    let formData = new FormData();
    formData.append('state', state);
    formData.append('location', window.location.href);
    formData.append('referrer', document.referrer);
    formData.append('title', document.title);
    formData.append('origin', window.location.origin);
    // Send the beacon
    let status = navigator.sendBeacon(url, formData);
    // Log the data and result
    // console.log("sendBeacon: URL = ", url, "; data = ", data, "; status = ", status);
}

sendB('started');

// query current page visibility state: prerender, visible, hidden
var pageVisibility = document.visibilityState;

// subscribe to visibility change events
document.addEventListener('visibilitychange', function() {
    // fires when user switches tabs, apps, goes to homescreen, etc.
    if (document.visibilityState === 'hidden') {
        sendB('hidden');
    }

    // fires when app transitions from prerender, user returns to the app / tab.
    if (document.visibilityState === 'visible') {
        sendB('visible');
    }

    //console.log( "Page is at "+document.visibilityState );
});
