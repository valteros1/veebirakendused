let modal;
let modalImg;
let captionText;
let photoId;
let photoDir = "../upload_photos_normal/";

window.onload = function(){
    modal = document.getElementById("modalarea");
    modalImg = document .getElementById("modalimg");
    captionText = document.getElementById("modalcaption");
    // lisame kõigile thumbidele klikikuulaja
    let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
    for(let i=0 ; i< allThumbs.length; i++){
        allThumbs[i].addEventListener("click", openModal);
    }
    document.getElementById("modalclose").addEventListener("click", closeModal)
}

function openModal(e){
    modalImg.src = photoDir + e.target.dataset.fn;
    captionText.innerHTML = e.target.alt;
    modal.style.display = "block";

}

function closeModal(){
    modal.style.display = "none";
    modalImg.src = "../images/empty.png";

}