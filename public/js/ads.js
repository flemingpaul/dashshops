$(document).ready(function() {
    new DataTable('#table_ads');
    $('#image').change(function() {

        let reader = new FileReader();

        reader.onload = (e) => {

            $('#preview-image-before-upload').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
})

function edit(id){
    $.ajax({
        url: get_base_url('ads/get/' + id),
        type: 'GET',
        success: function(response){
            console.log(response);
            $('#preview-image-before-upload').attr('src',get_base_url(`images/${response.data.image}`));
            $('#edit_id').val(response.data.id);
            $('#total_clicks').val(response.data.total_clicks);
            $('#url').val(response.data.url);
            document.getElementById("start_date").valueAsDate = new Date(response.data.start_date);
            document.getElementById("end_date").valueAsDate = new Date(response.data.end_date);
            
            $('#exampleModal').modal('show');
        },
        error: function(response){
            alert(response);
        }
    })
}