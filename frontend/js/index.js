//Afficher popin connexion au click sur le bouton connexion
function connexion() {
  let popin = document.getElementById("popinLogin");
  if(popin.style.display === "none") {
    popin.style.display = "block";
  } else {
    popin.style.display = "none";
  }
}
//enlever popin au click sur la croix
function exitPopin() {
  let exit = document.getElementById("exitPopin");
  popin.style.display = "none";
}

