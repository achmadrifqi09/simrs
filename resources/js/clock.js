function refreshTime() {
    const currentDate = document.querySelector('#currentDate');
    const currentTime = document.querySelector('#currentTime');

   if(currentDate && currentTime) {
       let dateString = new Date().toLocaleString("id-ID", {timeZone: "Asia/Jakarta"});
       let today = new Date();

       let dd = today.getDate();
       let mm = today.getMonth() + 1;
       let yyyy = today.getFullYear();

       if (dd < 10) dd = '0' + dd;
       if (mm < 10)  mm = '0' + mm;

       let todayDate = dd + '-' + mm + '-' + yyyy;
       let formattedString = dateString.replace(",","-");

       currentDate.innerHTML = todayDate;

       let splitArray = formattedString.split(" ");
       let splitArrayTime= splitArray[1].split(".");

       currentTime.innerHTML = splitArrayTime[0]+':'+splitArrayTime[1]+':'+splitArrayTime[2];
   }
}

setInterval(refreshTime, 1000);
