// Display pop-up exit window
function PopUpShow(){
    let popup = document.getElementById('exit_confirmation');
    popup.style.display = 'block';
}

// Hide pop-up exit window
function PopUpHide(){
    let popup = document.getElementById('exit_confirmation');
    popup.style.display = 'none';
}

// Display pop-up pic window
function PopUpPicShow(filename) {
    let popupPic = document.getElementById('picture');
    let popup = document.getElementById('picture_box');
    popupPic.setAttribute('src', filename);
    popup.style.display = 'block';
}

// Hide pop-up pic window
function PopUpPicHide() {
    let popupPic = document.getElementById('picture');
    let popup = document.getElementById('picture_box');
    popupPic.setAttribute('src', '');
    popup.style.display = 'none';
}
