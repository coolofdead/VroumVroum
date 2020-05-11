const STORAGE_NAME = "vroumvroum.basket";
const ITEM_SEPARATOR = ';';

window.addEventListener("DOMContentLoaded", (e)=>{
    var cols = document.querySelectorAll(".col");
    cols.forEach(col => {
        col.addEventListener('click',(e)=>{
            addToBasket(col);
        })
    });

    var basketNavBarContainer = document.querySelector("#toggle-basket");
    basketNavBarContainer.addEventListener("click", (e)=>{
        refreshBasketList();
    });

    var btnSendBasket = document.querySelector("#basket-form");

    btnSendBasket.addEventListener("submit", (e) => {
        console.log("cc");

        var inputBasket = document.querySelector("#basket-items-json");

        var items = getFormatedItems();

        var s = '{"items":[';
        for (var item of items) {
            s += JSON.stringify(item) + ",";
        }
        s = s.substring(0, s.length - 1);
        s += "]}";

        inputBasket.attributes["value"].value = s;
    });
});

function refreshBasketList() {
    var basketNavBarContainer = document.querySelector("#navbarmarket");

    var child = basketNavBarContainer.lastElementChild;  
    while (child) {
        basketNavBarContainer.removeChild(child); 
        child = basketNavBarContainer.lastElementChild; 
    } 
    const template = document.querySelector("#li-item-template");

    var items = getFormatedItems();

    var id = 0;
    items.forEach((i)=>{
        var c = template.content.children[0].cloneNode(true);

        c.querySelector(".item-image").attributes["alt"].value = i.https;
        c.querySelector(".item-image").attributes["src"].value = i.https;
        c.querySelector(".item-name").textContent = i.name;
        c.querySelector(".item-price").textContent = i.price + "€";
        c.querySelector(".remove-item-btn").attributes["item-id"].value = id;
        
        id++;

        basketNavBarContainer.appendChild(c);
    });

    var deleteItemButtons = document.querySelectorAll(".remove-item-btn");

    deleteItemButtons.forEach((deleteButton) => {
        deleteButton.addEventListener("click", (e) => {
            var id = e.target.attributes["item-id"].value;

            removeFromBasket(id);
        });
    });
}

function addToBasket(item) {
    var itemData = item.attributes['item-data'].value;
    
    var items = getFormatedItems();
    var item = JSON.parse(itemData);

    if(items.every((i)=>{return i.id_plat == item.id_plat}) || items.length == 0) {
        items.push(item);

        saveBasketInStorage(items);
    }
    else {
        var alert = document.querySelector("#alert-add-basket");
        alert.removeAttribute("hidden");
        setTimeout(()=>{
            alert.setAttribute("hidden", "");
        }, 10000)
    }
}

function readBasket() {
    return localStorage.getItem(STORAGE_NAME);
}

function removeFromBasket(id) {
    var items = getFormatedItems();

    items.splice(id, 1);

    saveBasketInStorage(items);
}

function saveBasketInStorage(items) {
    var s = "";
    for (var item of items) {
        s += JSON.stringify(item) + ITEM_SEPARATOR;
    }

    localStorage.setItem(STORAGE_NAME, s);
}

function getFormatedItems() {
    var itemsBrut = readBasket();
    var items = itemsBrut != null ? itemsBrut.split(ITEM_SEPARATOR) : [];

    items.pop();

    items = items.map(function(item) {
        return JSON.parse(item);
    });

    return items;
}