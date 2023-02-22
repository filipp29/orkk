const ClientList = {
    showOldClientList(){
        getXhr({
            action : "getOldClientList"
        }).then((html) => {
            newPage(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    sortTable(
            button,
            type
    ){
        let table = button.closest("table");
        let index = button.closest("th").cellIndex;
        let tbody = table.querySelector("tbody");
        let sortedIndex = tbody.sortedIndex;
        let sortFunc;
        if ((sortedIndex != undefined) && (sortedIndex == index) && (tbody.sortDir)){
            sortFunc = function(a,b){
                let valueA = a.cells[index].textContent;
                let valueB = b.cells[index].textContent;
                if (valueA < valueB){
                    return 1;
                }
                else if(valueB < valueA){
                    return -1;
                }
                else{
                    return 0;
                }
            };
            tbody.sortDir = false;
        }
        else{
            sortFunc = function(a,b){
                let valueA = a.cells[index].textContent.toLowerCase();
                let valueB = b.cells[index].textContent.toLowerCase();
                if (valueA < valueB){
                    return -1;
                }
                else if(valueB < valueA){
                    return 1;
                }
                else{
                    return 0;
                }
            };
            tbody.sortDir = true;
        }
        let rowList = Array.from(tbody.rows);
        rowList.sort(sortFunc);
        tbody.sortedIndex = index;
        tbody.innerHTML = "";
        let i = 0;
        for(let el of rowList){
            el.classList.remove("even");
            el.classList.remove("odd");
            if (!el.classList.contains("hidden")){
                i++;
            }
            let clas = (i % 2 !== 0) ? "even" : "odd";
            el.classList.add(clas);
            tbody.appendChild(el);
        }
    },
    
    /*---------------------------------------------------------------------------*/
    
    addMark(
            button
    ){
        let container = button.closest("td");
        let manager = getVarFromContainer(container,"manager");
        let id = getVarFromContainer(container,"id");
        let name = getVarFromContainer(container,"managerName");
        getXhr({
            action : "oldClientListAddMark",
            id : id,
            manager : manager
        }).then((html) => {
            container.textContent = name;
//            debug(html);
        });
    }
    
    /*---------------------------------------------------------------------------*/
    
};















