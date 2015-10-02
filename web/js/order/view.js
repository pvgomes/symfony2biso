$(document).ready(function(){
    OrderView.init();
})


var OrderView = {

    init: function()
    {
        var orderJson = JSON.parse($('#orderRawData').val());
        var node = new PrettyJSON.view.Node({
            el:$('#orderJson'),
            data: orderJson
        })
    },
    history: function (id)
    {
        $("#item-"+id).toggle();
        //$("#item-"+id).show();
    }
}