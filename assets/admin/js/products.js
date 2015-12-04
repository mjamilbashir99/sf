function loadFilterFields(){
    var cat_id = $('#cat_id').val();
    $.ajax({
            "url":"/www/product/categories_filter_fields?cat_id="+cat_id,
            "success":function(data){
                $('#filter_fields_container').html(data);
            }
        });
}


$(function () {
    $("#cat_id").change(loadFilterFields);

    //loadFilterFields();

});