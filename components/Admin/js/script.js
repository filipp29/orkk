const Admin = {
    showMain(){
        getXhr({
            action : "getAdminMainPage"
        }).then((html) => {
            newPage(html,true,function(page){
                swapDisableAll(page);
            });
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
        Admin.tableSelect(el);
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
    
    salary : {
        save(
                button,
                containerSelector = ".editBlock"
        ){
            let container = button.closest(containerSelector);
            let paramList = Array.from(container.querySelectorAll(".salary_param"));
            let result = {};
            for(let el of paramList){
                let buf = el.id.split(".");
                if (buf.length < 2){
                    continue;
                }
                if (result[buf[0]] == undefined){
                    result[buf[0]] = {};
                }
                result[buf[0]][buf[1]] = getValue(el);
            }
            getXhr({
                action : "adminSalarySave",
                salary : JSON.stringify(result)
            }).then((html) => {
                tableCellEditClose(button,true);
//                debug(html);
            });
        },
        
        /*---------------------------------------------------------------------------*/
        
        bonusTable : {
            showAddForm(
                    button
            ){
                getXhr({
                    action : "getAdminSalaryBonusForm"
                }).then((html) => {
                    fMsgBlock("Добавить бонус",html,170,600,"addBonusForm");
                    let container = document.getElementById("addBonusForm");
                    let saveButton = container.querySelector(".adminSalaryBonusSaveButton");
                    saveButton.addEventListener("click",function(){
                        let key = getVarFromContainer(container,"key").trim();
                        let value = getVarFromContainer(container,"value").trim();
                        if (!key || !value){
                            fMsgBlock("Ошибка","<h2 style='width:100%;text-align:center;'>Введите данные</h2>",130,400,"errorMsg");
                            return;
                        }
                        let table = button.closest("table");
                        let tbody = table.querySelector("tbody");
                        let tr = button.closest("tr");
                        let role = getVarFromContainer(tr,"role");
                        let bonusList = {};
                        bonusList[key] = value;
                        getXhr({
                            action : "saveAdminSalaryBonus",
                            role : role,
                            bonusList : JSON.stringify(bonusList)
                        }).then((html) => {
                            tbody.innerHTML += html;
                            fUnMsgBlock("addBonusForm");
//                            newPage(html);
                        });
                    });
                });
                
                
                
            },
            
            /*---------------------------------------------------------------------------*/
            
            remove(
                    button
            ){
                let tr = button.closest("tr");
                let tbody = button.closest("tbody");
                let key = getVarFromContainer(tr,"bonusKey");
                let role = getVarFromContainer(tr,"role");
                let bonusList = [
                    key
                ];
                getXhr({
                    action : "removeAdminSalaryBonus",
                    bonusList : JSON.stringify(bonusList),
                    role : role
                }).then((html) => {
                    tbody.removeChild(tr);
                    debug(html);
                });
                
            },
            
            /*---------------------------------------------------------------------------*/
            
            save(
                    button
            ){
                let tr = button.closest("tr");
                let tbody = button.closest("tbody");
                let key = getVarFromContainer(tr,"bonusKey");
                let role = getVarFromContainer(tr,"role");
                let value = getVarFromContainer(tr,"bonusValue");
                let bonusList = {};
                bonusList[key] = value;
                getXhr({
                    action : "saveAdminSalaryBonus",
                    bonusList : JSON.stringify(bonusList),
                    role : role
                }).then((html) => {
                    tableCellEditClose(button,true);
//                    debug(html);
                });
            }
            
            /*---------------------------------------------------------------------------*/
            
        },
        
        
        /*---------------------------------------------------------------------------*/
    },
    
    /*---------------------------------------------------------------------------*/
    
    plan : {
        save(
                button,
                containerSelector = "td"
        ){
            let container = button.closest(containerSelector);
            let year = getVarFromContainer(container,"plan_year");
            let month = getVarFromContainer(container,"plan_month");
            let elemList = Array.from(container.querySelectorAll(".plan_param"));
            let params = {};
            for(let el of elemList){
                let key = el.id;
                let value = getValue(el);
                params[key] = value;
            }
            getXhr({
                action : "adminPlanSave",
                year : year,
                month : month,
                data : JSON.stringify(params)
            }).then((html) => {
                tableCellEditClose(button,true);
//                debug(html);
            });
        },
        
        /*---------------------------------------------------------------------------*/
        
        update(
                button
        ){
            let container = button.closest(".yearSelectBlock");
            let year = getVarFromContainer(container,"plan_year");
            let tableContainer = button.closest(".tableContainer");
            getXhr({
                action : "getAdminPlanTable",
                year : year
            }).then((html) => {
                tableContainer.innerHTML = html;
                swapDisableAll(tableContainer);
            });
        }
        
        /*---------------------------------------------------------------------------*/
        
    }
    
    /*---------------------------------------------------------------------------*/
    
};
