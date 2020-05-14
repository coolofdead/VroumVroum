window.addEventListener("DOMContentLoaded", (e) => {
    var form = document.querySelector("#update-plat-form");
    var selectInputs = document.querySelectorAll(".select-input");
    var str = form.attributes['action'].value;
    var path = str.substring(0, str.length - 1);

    var btns = document.querySelectorAll(".toggle");

    btns.forEach((b) => {
        b.addEventListener("click", (e) => {
            var pId = e.target.attributes["data-id"].value;
            form.attributes["action"].value = path + pId;

            var inputs = document.querySelectorAll(".data-" + pId);
            for (var i = 0; i < form.length; i++) {
                inputs.forEach((input) => {
                    if (form[i].attributes["name"] != undefined && form[i].attributes["value"] != undefined) {
                        if (form[i].attributes["name"].value == input.attributes["name"].value) {
                            form[i].attributes["value"].value = input.textContent;
                        }
                    }
                });
            }

            selectInputs.forEach((selectInput)=>{
                inputs.forEach((input) => {
                    if (selectInput.attributes["name"].value == input.attributes["name"].value) {
                        for (var i = 0; i < selectInput.length; i++) {
                            if (selectInput[i].attributes["value"].value == input.textContent) {
                                selectInput[i].selected = true;
                            }
                        }
                    }
                });
            });
        });
    });
});