window.addEventListener("DOMContentLoaded", (e) => {
    var btns = document.querySelectorAll(".btn-update-restaurant");
    var form = document.querySelector("#update-restaurant");

    var inputs = document.querySelectorAll(".form-control");

    inputs.forEach((input)=>{
        input.addEventListener("change", (e)=>{
            e.target.attributes["value"].value = e.target.value;
        });
    });

    btns.forEach((btn)=> {
        btn.addEventListener('click', (e)=>{
            var formId = document.querySelector("#r-id");

            var rId = e.target.attributes["data-id"].value;
            formId.attributes["value"].value = rId;

            var inputs = document.querySelectorAll(".detail-"+rId);

            for(var i = 0; i < form.length; i++) {
                inputs.forEach(input => {
                    if (input.attributes["name"] != undefined) {
                        if (input.attributes["name"].value == form[i].attributes["name"].value) {
                            form[i].attributes["value"].value = input.attributes["value"].value;
                        }

                        if (input.attributes["type"].value == "radio" && form[i].attributes["name"].value == "category") {
                            if (input.checked) {
                                console.log(input);
                                form[i].attributes["value"].value = input.attributes["value"].value;
                            }
                        }
                    }
                });
            }
        });
    });
});