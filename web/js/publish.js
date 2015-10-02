var refreshIntervalId;

var startNotify = function(load) {
    load.find('td.status span:first')
        .removeClass('fa-check')
        .addClass('fa-spinner fa-spin');
};

var stopNotify = function(load, error) {
    icon = error ? 'fa-close' : 'fa-check';
    load.find('td.status span:first')
        .removeClass('fa-spinner')
        .removeClass('fa-spin')
        .addClass(icon);

    clearInterval(refreshIntervalId);
};

var ajaxCall = function(loadId) {
    var result = null;

    $.ajax({
        url: "/product/load/report/"+loadId,
        async: false
    })
    .done(function(data) {
        result = data;
    });

    return result;
};

var loadReport = function(index) {
    load = $('#load-report tbody tr').eq(index);
    loadId = load.data('loadId');

    startNotify(load);

    result = ajaxCall(loadId);

    if (result && result.valid) {
        load.find('a.success').text(result.success);
        load.find('a.error').text(result.errors);
        stopNotify(load, false);
    } else {
        stopNotify(load, true);
    }
};

var loadReportCurrent = function() {
    load = $('#load-report tbody tr').eq(0);
    loadId = load.data('loadId');
    loadSuccess = load.data('loadSuccess');
    loadErrorr = load.data('loadErrorr');

    result = ajaxCall(loadId);

    if (result && result.valid) {
        if (loadSuccess == result.success &&
            loadErrorr == result.errors) {
            stopNotify(load, false);
            return;
        }

        startNotify(load);

        load.data('loadSuccess', result.success);
        load.data('loadErrorr', result.errors);

        load.find('a.success').text(result.success);
        load.find('a.error').text(result.errors);
    } else {
        stopNotify(load, true);
        return;
    }
};

$('#load-report tbody tr').each(function(index) {
    loadReport(index);
});

refreshIntervalId = setInterval(loadReportCurrent, 3000);



$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $('#publishSelectedFile'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });

});