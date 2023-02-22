const Phonebook = {
    editContact(
            button
    ){
        let row = button.closest("tr");
        let hiddenElements = Array.from(row.querySelectorAll(".hiddenElement"));
        swapDisableAll(row);
        for(let el of hiddenElements){
            el.classList.toggle("hidden");
        }
    },
    
    /*---------------------------------------------------------------------------*/
    
    addPhone(
            button
    ){
        let container = button.closest(".phoneNumberList");
        let row = document.createElement("div");
        row.classList.add("phoneRow");
        row.classList.add("divRow");
        row.style = "align-items: center; margin: 5px 0px;";
        row.innerHTML = `
<div class="divButton hiddenElement hidden" style="width: auto;height: 18px;" onclick="orkkDoCall(this,'.phoneRow','phoneNumber')">
    <img src="/_modules/orkkNew/img/phone.png" alt="alt" style="height: 100%; width: auto">
</div>


<div class="divButton phoneDeleteButton hiddenElement" style="width: auto;height: 18px;" onclick="Phonebook.deletePhone(this)">
    <img src="/_modules/orkkNew/img/button_close.png" alt="alt" style="height: 100%; width: auto">
</div>


<input onfocus="inputPhoneFocus(this)" onkeydown="inputPhoneKeyDown(event)" oninput="inputPhoneInput(event)" class="inp inputText inputPhone contactPhoneNumber" type="tel" name="name" id="contact_phoneNumber" value="+7 (___) ___-__-__" style="margin-left: 8px;font-size: 12px;padding: 2px 5px;">
`;
        container.insertBefore(row,button);
    },
    
    /*---------------------------------------------------------------------------*/
    
    deletePhone(
            button
    ){
        let container = button.closest(".phoneRow");
        let parent = container.parentNode;
        parent.removeChild(container);
    },
    
    /*---------------------------------------------------------------------------*/
    
    reloadContactTable(
            button
    ){
        let container = button.closest(".tableContainer");
        getXhr({
            action : "getPhoneBookTable"
        }).then((html) => {
            container.innerHTML = html;
            swapDisableAll(container);
            allInputAreaAutosize(container);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveContact(
            button
    ){
        let container = button.closest("tr");
        let phoneBuf = Array.from(container.querySelectorAll(".contactPhoneNumber"));
        let phoneList = [];
        for(let el of phoneBuf){
            let phone = getValue(el);
            phoneList.push(phone);
        }
        let keyList = [
            "id",
            "organization",
            "name",
            "role",
            "comment"
        ];
        let contact = {};
        for(let key of keyList){
            contact[key] = getVarFromContainer(container,"contact_" + key);
        }
        contact["phoneList"] = phoneList;
        contact["city"] = getValue(button.closest(".phoneTableContainer").querySelector("#contact_city"));
        let tableContainer = button.closest(".tableContainer");
        getXhr({
            action : "phonebookContactSave",
            contact : JSON.stringify(contact)
        }).then((html) => {
            tableContainer.innerHTML = html;
            swapDisableAll(tableContainer);
            allInputAreaAutosize(tableContainer);
        });
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    addContact(
            button
    ){
        let tbody = button.closest(".phoneTableContainer").querySelector("tbody");
        let tr = document.createElement("tr");
        tr.innerHTML = 
`
<td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    <input class="inp inputText" type="text" name="name" id="contact_organization" value="" style="font-size: 12px;padding: 2px 5px;">

</td><td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    <input class="inp inputText" type="text" name="name" id="contact_name" value="" style="font-size: 12px;padding: 2px 5px;">

</td><td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    <input class="inp inputText" type="text" name="name" id="contact_role" value="" style="font-size: 12px;padding: 2px 5px;">

</td><td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    
<div id="" class="phoneNumberList divColumn" style="align-items: flex-start;">

<div class="divButton hiddenElement" style="width: auto;height: 14px;margin: 5px 0px;" onclick="Phonebook.addPhone(this)">
    <img src="/_modules/orkkNew/img/button_plus.png" alt="alt" style="height: 100%; width: auto">
</div>

</div>

</td><td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    <textarea oninput="inputStretch(this);inputAreaAutoSize(this)" class="inp inputArea stretch inputAreaAutosize" type="text" name="name" id="contact_comment" style="max-width: 300px; min-width: 300px; height: auto; font-size: 12px; padding: 2px 5px; width: 320px;"></textarea></td><td class="" style="font-size: 12px;border: 1px var(--modColor_darkest) solid;padding: 2px 5px;">
    
<div id="" class=" divColumn" style="">
    <button onclick="Phonebook.editContact(this)" class="buttonNormal hiddenElement hidden" style="width: auto;">
    Редактировать</button>


<div id="" class="acceptBlock hiddenElement divRow" style="width: 100%;justify-content: center;align-items: center;height: 100%;">
    <div class="divButton " style="margin-right: 25px;" onclick="Phonebook.reloadContactTable(this)">
    <img src="/_modules/orkkNew/img/button_close.png" alt="alt" style="height: 100%; width: auto">
</div>


<div class="divButton " style="" onclick="Phonebook.saveContact(this,'tr')">
    <img src="/_modules/orkkNew/img/button_accept.png" alt="alt" style="height: 100%; width: auto">
</div>


</div>


<div id="contact_id" class="hidden var " style=""></div>
</div>

</td>    
`;
        tbody.appendChild(tr);
    } ,
    
    /*---------------------------------------------------------------------------*/
    
    deleteContact(
            button
    ){
        let contactId = getVarFromContainer(button.closest("tr"),"contact_id");
        let tableContainer = button.closest(".tableContainer");
        getXhr({
            action : "phonebookContactDelete",
            contactId : contactId
        }).then((html) => {
            tableContainer.innerHTML = html;
            swapDisableAll(tableContainer);
            allInputAreaAutosize(tableContainer);
        });
    }
    
    /*---------------------------------------------------------------------------*/
    
};
















