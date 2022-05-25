jQuery(document).ready(function($) {
$("#contact").submit(function() {


    /*Validate Inputs */
    let customernumber = document.getElementById("customerNumber").value;
    let promptCalling = document.getElementById("promptCalling").value;
    let promptInvalidNumber = document.getElementById("promptInvalidNumber").value;
    let pbxURL = document.getElementById("pbxURL").value;
    let callButtonDisableTime = document.getElementById("callButtonDisableTime").value;
    let promptSubmit = document.getElementById("promptSubmit").value;
    let promptNotice = document.getElementById("promptNotice").value;
    

    // If x is Not a Number or less than one or greater than 10
  if (isNaN(customernumber)) {
      $('#note').text(promptInvalidNumber);
      return false;
    }

var str = $(this).serialize();

var delay = 3000;
$.ajax({
type: "POST",
url: pbxURL,
crossDomain: true,
data: str,
dataType: 'text',

success: function(msg) {
    //alert("Hello! First");
if(msg == 'OK') {
    //alert("Hello! OK");
  result = promptCalling;

  document.getElementById("contact-submit").disabled = true;

  } else {

  result = msg;
}//End If

$('#note').html(result);

},//End Function
      error: function () {
         
      },
      
      complete: function () {
           $('#note').text(promptCalling);
           
        // Handle the complete event
          
         var count = callButtonDisableTime;
         var Interval= setInterval(function() {
     
           count--;
           $('.contact-submit').removeClass('butstyle').addClass('AfterChange');
           document.getElementById('contact-submit').innerHTML =  count;

            //Disable Botton
          document.getElementById("contact-submit").disabled = true;
          document.getElementById('contact-submit').style.cssText = 'background:#383e49; ont-size: 14px; color: #ffffff;';
          //document.getElementById('contact-submit').removeClass('button').addClass('AfterChange');
    
           if (count === 0) {
                $('#note').text(promptNotice);
                document.getElementById("contact-submit").disabled = false;
                document.getElementById('contact-submit').innerHTML = promptSubmit;
                document.getElementById('contact-submit').style.cssText = 'background:#FDD017; color: #000000;';
      
           clearInterval(Interval);
             }
          }, 1000);


          
}//End Complete


}//End Ajax
);

return false;
});
});




