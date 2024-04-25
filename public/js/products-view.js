$(document).ready(function () {
    if (document.getElementById("selectRetailer")) {
      var tags = document.getElementById("selectRetailer");
      new Choices(tags, { shouldSort: false });
    }
    if (document.getElementById('selectCategory')) {
        var tags = document.getElementById('selectCategory');
        new Choices(tags, { shouldSort: false });
    }
    hSearch();
});

function hSearch() {
    var url = "";
    url = get_base_url(
      `products/search?category_id=${$("#selectCategory").val()}&retailer_id=${$("#selectRetailer").val()}&status=${$("#cmbStatus").val()}`
    );

    console.log(url);
    $("#divTableProducts").html('<div style="width:180px;height:180px;">please wait...</div>');
    getData2(url, 'divTableProducts', enableVTable);

}

function enableVTable() {

    if (document.getElementById('products-list')) {
        const dataTableSearch = new DataTable("#products-list", {
            searchable: true,
            fixedHeight: false,
            perPage: 25
        });
    };

}

function deleteProduct(id) {
    Swal.fire({
        icon: 'question',
        title: 'Please confirm',
        text: 'Are you sure you want to delete this product',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            d = {
                'id': id,
                '_token': $('[name="_token"]').val(),
            }
            $.ajax({
                url: get_base_url('sellers/deleteproduct'),
                method: "POST",
                data: d,
                cache: false,
                processData: true,
                success: function(data) {
                    // console.log(data)

                    try {
                        var dat = JSON.parse($.trim(data));
                        if (typeof dat.result !== 'undefined') {
                            if (dat.result === "success") {
                                talert("Product has been deleted", "", "success");
                                hSearch()

                            } else {
                                xalert(dat.data.message, "", "error")
                            }
                        }

                    } catch (ee) {
                        xalert(ee.toString());
                        console.log(data);
                    }
                },
                error: function(data) {
                    console.log(data);
                    xalert(data.responseJSON.message, "Oops", "error");
                    //$("#tchatsbody").html("");
                }
            });

        }
    })
}