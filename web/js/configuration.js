Configuration = {
    lock : false,
    removeKey: function(id, key) {
        if (confirm("Tem certeza que deseja remover a chave?")) {

            $.ajax({
                url: "/system/removekey",
                async: false,
                method: "POST",
                data: { key: key }
            }).done(function(data) {
                $("#row_"+id).remove();
            });

        }

    },
    updateKey: function(id, key) {
        if (confirm("Tem certeza que deseja atualizar a chave?")) {

            $.ajax({
                url: "/system/updatekey",
                async: false,
                method: "POST",
                data: { key: key, value: $("#content_"+id).val() }
            }).done(function(data) {

            });

        }
    },
    productsCache: function () {

        if (!this.lock) {
            if (confirm("Tem certeza que deseja atualizar o cache dos produtos?")) {


                this.lock = true;
                $.ajax({
                    url: "/system/productcache",
                    async: false,
                    method: "POST"
                }).done(function(data) {
                    alert("Feito");
                    this.lock = false;
                });

            }
        }
    }
};