window.addEventListener("DOMContentLoaded", (e) => {
    var btns = document.querySelectorAll(".btn-rating");
    var btnContainer = document.querySelector("#rating-block");
    var starInput = document.querySelector("#input-star");

    btns.forEach((btn)=> {
        btn.addEventListener('click', (e)=>{
            // Highlight all previous stars
            // change the value of the input star
            for (var i = 0; i < btnContainer.children.length; i++) {
                if (btnContainer.children[i] == e.target) {
                    highlightArrowButtons(btnContainer, i);

                    starInput.attributes["value"].value = i;
                }
            }
        });
    });
});

function highlightArrowButtons(container, index) {
    for (var i = container.children.length-1; i > 0; i--) {
        container.children[i].classList.remove('btn-grey');
        container.children[i].classList.remove('btn-warning');
        if (i <= index) {
            container.children[i].classList.add('btn-warning');
        }
        else {
            container.children[i].classList.add('btn-grey');
        }
    }
}