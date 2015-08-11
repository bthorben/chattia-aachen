function einblenden(bereich){
   $(bereich).fadeIn('slow', function() {
      $(bereich).show();
   });
}
 
function ausblenden(bereich){
   $(bereich).fadeOut('slow', function() {
      $(bereich).hide();
   });
}

function einblendenWithoutAnimation(bereich){
      $(bereich).show();
}
 
function ausblendenWithoutAnimation(bereich){
      $(bereich).hide();
}

function readMore(bereichAus, bereichEin) {  
  $(bereichAus).hide();
  $(bereichEin).show();
}

function umschalten(bereich) {
   $(bereich).fadeToggle();
}