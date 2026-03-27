let timeout;

function resetTimer() {
    clearTimeout(timeout);
    
    timeout = setTimeout(logoutUser, 60000);
}

function logoutUser() {
    alert("Odjavljeni ste zbog neaktivnosti.");
    window.location.href = 'izlogujse.php';
}

const activityEvents = [
    'mousedown', 'mousemove', 'keydown', 
    'scroll', 'touchstart'
];

activityEvents.forEach(event => {
    document.addEventListener(event, resetTimer, true);
});


resetTimer();
