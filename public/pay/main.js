// let targetDate = new Date(timeout);
// targetDate = new Date(targetDate.setMinutes(targetDate.getMinutes() + 30));
// setInterval(()=>{
//     const now = new Date();
//     const timeLeft = targetDate - now;
//     if(timeLeft < 0){
//         location.reload();
//     }
//     let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
//         , minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))
//         , seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
//     hours = (String(hours).length >= 2) ? hours : "0" + hours;
//     minutes = (String(minutes).length >= 2) ? minutes : "0" + minutes;
//     seconds = (String(seconds).length >= 2) ? seconds : "0" + seconds;
//     $(".hours").text(hours);
//     $(".minutes").text(minutes);
//     $(".seconds").text(seconds);
//
// },1000);