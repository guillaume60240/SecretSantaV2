/** ANIMATION SCROLL BACKTOTOP */
//on sélectionne le bouton
let mybutton = document.getElementById("btn-back-to-top");

//on ajoute un évènement au clic pour lancer la fonction backtotop
mybutton.addEventListener("click", backToTop);
// on utilise l'évènement onscroll pour lancer une fonction
window.onscroll = function () {
  scrollFunction();
};

//on change le display du boutton en fonction de la hauteur du scroll(<300)
function scrollFunction() {
  if (
    document.body.scrollTop > 300 ||
    document.documentElement.scrollTop > 300
  ) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

//la fonction modifie la hauteur du scroll à 0
function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}