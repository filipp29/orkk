initIndex();
document.getElementById("sidebar").classList.add("hidden");
document.getElementById("workfield").style.marginLeft = "10px";
document.addEventListener('keydown', (event) => {
    let keyName = event.key;
    let text = window.getSelection().toString();
    if (event.altKey){
        if (keyName == "o"){
            getXhr({
                action : "getContactListForm",
                dnum : text.trim()
            }).then((html) => {
                fMsgBlock("Номера " + text.trim(),html,700,700,"phoneNumberForm");
            });
        }
    }
},false);