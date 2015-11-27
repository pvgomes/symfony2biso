Configuration = {
    removeKey: function(id, key) {
        if (confirm("Tem certeza que deseja remover a chave?")) {
            // implement...
            $.ajax({
                url: "/system/configuration-remove",
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
                url: "/system/configuration-update",
                async: false,
                method: "POST",
                data: { key: key, value: $("#content_"+id).val() }
            }).done(function(data) {

            });

        }
    },
};