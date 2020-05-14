window.addEventListener("DOMContentLoaded", (e) => {
    var statusForm = document.querySelector("#input-command-status");
    var form = document.querySelector("#update-command-status");
    var btns = document.querySelectorAll(".btn-update-command-status");

    var str = form.attributes['action'].value;
    var path = str.substring(0, str.length - 1);

    btns.forEach((btn)=> {
        btn.addEventListener('click', (e)=>{
            var cId = e.target.attributes['data-id'].value;

            form.attributes['action'].value = path + cId;

            var status = document.querySelectorAll(".command-"+cId);

            status.forEach((s)=>{
                if (s.checked) {
                    statusForm.attributes['value'].value = s.attributes['value'].value;
                }
            });
        });
    });
});