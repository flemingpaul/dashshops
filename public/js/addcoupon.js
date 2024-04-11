$(document).ready(function (e) {
    $("#image").change(function () {
        let reader = new FileReader();

        reader.onload = (e) => {
            $("#dropcontainer").css("background-image", "url(" +e.target.result+ ")");
            $("#has_new_image").val(1);
        };

        reader.readAsDataURL(this.files[0]);
    });
    initDragDrop();
});

function initDragDrop() {
    const dropContainer = document.getElementById("dropcontainer");
    const fileInput = document.getElementById("image");

    dropContainer.addEventListener(
        "dragover",
        (e) => {
            // prevent default to allow drop
            e.preventDefault();
        },
        false
    );

    dropContainer.addEventListener("dragenter", () => {
        dropContainer.classList.add("drag-active");
    });

    dropContainer.addEventListener("dragleave", () => {
        dropContainer.classList.remove("drag-active");
    });

    dropContainer.addEventListener("drop", (e) => {
        e.preventDefault();
        dropContainer.classList.remove("drag-active");
        fileInput.files = e.dataTransfer.files;
        var event = new Event('change');
        fileInput.dispatchEvent(event);
    });
}
function setDiscountType(){
    let selectedDiscountType = $('input[name="selectedDiscountType"]:checked').val()
    if(selectedDiscountType == "Discount Percent"){
        $("#discountAmount").attr("type","number");
        if(parseFloat($("#discountAmount").val()) == NaN){
            $("#discountAmount").val(0);
        }
    }else{
        $("#discountAmount").attr("type","text");
    }
}
