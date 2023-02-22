const Debtor = {
    
    /*---------------------------------------------------------------------------*/
    
    showDebtorTable(){
        getXhr({
            action : "getDebtorTable"
        }).then((html) => {
            newPage(html,true,function(page){
                let containerList = Array.from(page.querySelectorAll(".tableContainer"));
                for(let el of containerList){
                    swapDisableAll(el);
                }
            });
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    reloadDebtorTable(
            button
    ){
        let page = button.closest(".page");
        getXhr({
            action : "getDebtorTable"
        }).then((html) => {
            page.innerHTML = html;
            let containerList = Array.from(page.querySelectorAll(".tableContainer"));
            for(let el of containerList){
                swapDisableAll(el);
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    tableMenuSelect(
            el
    ){
        let menuBlock = el.closest(".menuBlock");
        let itemList = Array.from(menuBlock.querySelectorAll(".menuItem"));
        for(let menuItem of itemList){
            menuItem.classList.remove("menuItem_selected");
        }
        el.classList.add("menuItem_selected");
        Debtor.tableSelect(el);
    },
    
    /*---------------------------------------------------------------------------*/
    
    tableSelect(
            el
    ){
        let page = el.closest(".page");
        let menuItem = page.querySelector(".menuItem.menuItem_selected");
        let containerList = Array.from(page.querySelectorAll(".tableContainer"));
        for(let container of containerList){
            container.classList.add("hidden");
        }
        let id = menuItem.id;
        page.querySelector("#" + id + "_table").classList.remove("hidden");
    },
    
    /*---------------------------------------------------------------------------*/
    
    edit(
            button
    ){
        tableCellEditClick(button,"tr");
    },
    
    /*---------------------------------------------------------------------------*/
    
    editCancel(
            button
    ){
        tableCellEditClose(button,false,"tr",".acceptBlock","#debtor_edit_button");
    },
    
    /*---------------------------------------------------------------------------*/
    
    editAccept(
            button
    ){
        tableCellEditClose(button,true,"tr",".acceptBlock","#debtor_edit_button");
        Debtor.save(button);
    },
    
    /*---------------------------------------------------------------------------*/
    
    save(
            button,
            type = "none"
    ){
        let container = button.closest("tr");
        let get = function(id){
            return getVarFromContainer(container,id);
        };
        let lock = get("debtor_lock");
        let exclude = get("debtor_exclude");
        let dnum = get("debtor_dnum");
        let comment = get("debtor_comment");
        let date = get("debtor_date");
        let currentType = get("debtor_type");
        let remove = true;
        let lockRow = "none";
        let excludeRow = "none";
        if (type == currentType){
            type = "debtor";
            remove = true;
        }
        else if (type == "none"){
            type = currentType;
            remove = false;
        }
        else if (type == "lock"){
            if (lock){
                lock = "";
                lockRow = "unlock";
                setVarToContainer(container,"debtor_lock","");
            }
            else {
                lock = "1";
                lockRow = "lock";
                setVarToContainer(container,"debtor_lock","1");
            }
            type = currentType;
            remove = false;
        }
        else if (type == "exclude"){
            if (exclude){
                exclude = "";
                excludeRow = "unlock";
                setVarToContainer(container,"debtor_exclude","");
            }
            else {
                exclude = "1";
                excludeRow = "lock";
                setVarToContainer(container,"debtor_exclude","1");
            }
            type = currentType;
            remove = false;
        }
        getXhr({
            action : "debtorSave",
            type : type,
            dnum : dnum,
            comment : comment,
            date : date,
            lock : lock,
            exclude : exclude
        }).then((html) => {
            if (remove){
                container.parentNode.removeChild(container);
            }
            
            if (lockRow){
                if (lockRow == "lock"){
                    container.style.backgroundColor = "#DAA520";
                }
                else if (lockRow == "unlock"){
                    container.style.backgroundColor = "";
                }
            }
            if (excludeRow){
                if (excludeRow == "lock"){
                    container.style.backgroundColor = "#7FFFD4";
                }
                else if (excludeRow == "unlock"){
                    container.style.backgroundColor = "";
                }
            }
//            debug(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    shift(
            button
    ){

        getXhr({
            action : "debug"
        }).then((html) => {
            fMsgBlock("¬ведите дату",html,160,300,"dateForm");
            let msgContainer = document.getElementById("dateForm");
            let container = button.closest("tr");
            let callBack = function(){
                let value = getVarFromContainer(msgContainer,"date_input");
                setVarToContainer(container,"debtor_date",value);
                fUnMsgBlock("dateForm");
                Debtor.save(button,"shift");
            };
            msgContainer.querySelector("#ok_button").addEventListener("click",callBack);
        });
    }
    
    /*---------------------------------------------------------------------------*/
};


















