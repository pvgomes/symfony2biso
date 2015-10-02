Product = {
    categories: null,
    toggleJson: function(id) {

        if (!$('#wrapperJsonPretty-'+id).html()) {
            var productJson = JSON.parse($('#productJsonData-' + id).val());
            var node = new PrettyJSON.view.Node({
                el: $('#wrapperJsonPretty-' + id),
                data: productJson
            });
            $('#btnJsonRaw-' + id).fadeIn();

        } else {
            $('#wrapperJsonPretty-'+id).html('')
            $('#btnJsonRaw-'+id).fadeOut();
        }

    },

    showJsonRaw: function(id){
        $('#wrapperJsonPretty-' + id).html('');
        $('#jsonRaw-'+id).show();
    },
    prettyJson: function (id)
    {
        var jsonPrettyData = JSON.parse($('#jsonRawData'+id).val());
        var node = new PrettyJSON.view.Node({
            el:$('#jsonPrettyData'+id),
            data: jsonPrettyData
        })
    },
    loadCategories: function(){
        $('#form_category').attr('placeholder','Carregando categorias ...');
        $.get('/category', {}, function(response){
            Product.categories = response['categories'];
            $('#form_category').attr('placeholder','Categorias');
        })
    },
    selectCategory: function(nameKey) {
        $('#form_category').val(nameKey);
        $('#categories-list').fadeOut();
    }
};

$(function() {

    Product.loadCategories();

    // Shows the ExternalProducts that belongs to the selected Product
    $('.product-main').click(function() {
        product = $(this);
        productId = product.data('productId');
        productIsActive = product.data('isActive');
        productContent = $('#product-content-'+productId);
        productContentHidden = product.data('contentHidden');
        productContentLoaded = product.data('contentLoaded');

        toggle = function () {
            if (productContentHidden && productIsActive) {
                productContent.show();
                product.data('contentHidden', false);
            } else {
                productContent.hide();
                product.data('contentHidden', true);
            }
        };

        loadContent = function() {
            if (false == productContentLoaded && productIsActive) {
                $.ajax({
                    url: "/external-product-table/"+productId
                }).done(function(data) {
                    productContent.find('td').html(data);
                    product.data('contentLoaded', true);
                });
            }
        }

        loadContent();
        toggle();
    });

    // Adds a flag (active|inactive) for each Product listed in page
    $('.product-main').each(function() {
        product = $(this);
        productId = product.data('productId');

        $.ajax({
            url: "/external-product-status/"+productId,
            async: false
        }).done(function(data) {
            if (data.isActive) {
                product.data('isActive', true);
                $('#product-status-'+productId+' > .label-success').show();
            } else {
                product.data('isActive', false);
                $('#product-status-'+productId+' > .label-danger').show();
            }
            $('#product-status-'+productId+' > .fa').hide();
        });
    });


    $('#form_category').keyup(function(){
        $('#categories-list').fadeIn();
        var contentTable = '<ul class="dropdown-menu" id="menu-categories-list" style="display: block">';
        Product.categories.forEach(function(obj){
            var searchTerm = $('#form_category').val();
            var categoryName = obj.name;
            res = categoryName.match(new RegExp(searchTerm,"gi"));

            if(res) {
                contentTable += '<li><a href="javascript:Product.selectCategory(\'' + obj.nameKey + '\')">' + obj.name + '</a></li>';
            }
        });
        $('#menu-categories-list').show();

        contentTable += '</ul>';

        $('#categories-list').html(contentTable);
    });
});
