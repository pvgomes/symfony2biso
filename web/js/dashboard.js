$(window).load(function(){
    Dashboard.init();
});


Dashboard = {
    lock : false,
    init: function() {
        console.log("Starting dashboard...");
        this.order();
        this.product();
    },
    order: function() {

        $.ajax({
            url: "/dashboard/order",
            async: true,
            method: "POST",
            data: { }
        }).done(function(data) {
            $("#orderStatistics_Today").text(data.orderStatistics.todayQuantity);
        });

    },
    product: function() {

        $.ajax({
            url: "/dashboard/product",
            async: true,
            method: "POST",
            data: { }
        }).done(function(data) {
            Morris.Donut({
                element: 'morris-donut-chart',
                data:data.productStatistics.quantity,
                resize: false
            });
        });

    }
};


$(function() {

    Morris.Area({
        element: 'morris-area-chart',
        data: [
//                    {
//                    period: '',
//                    iphone: 10687,
//                    ipad: 4460,
//                    itouch: 2028
//                }, {
//                    period: '2015 Q2',
//                    fralda: 8432,
//                    berco: 5713
//                }
        ],
        xkey: 'period',
        ykeys: ['fralda', 'berco'],
        labels: ['fralda', 'berco'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });


});