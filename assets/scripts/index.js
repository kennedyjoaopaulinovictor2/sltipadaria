var Index = function () {


    return {

        initIntro: function () {
            
          
            if ($.cookie('intro_show')) {
                return;
            }
            if ($('#header_notification_bar .badge').size() > 0 && parseInt($('#header_notification_bar .badge').text()) > 0) {
                var number = parseInt($('#header_notification_bar .badge').text());
                $.cookie('intro_show', 1);
                setTimeout(function () {
                    $.extend($.gritter.options, {position: 'top-left'});
                    var unique_id = $.gritter.add({
                        title: 'Atenção',
                        text: (number + ' nova' + (number > 1 ? 's notificações' : 'notificação') + '! Clique no ícone piscando para ver'),
                        sticky: true,
                        time: '',
                    });
                    setTimeout(function () {
                        $.gritter.remove(unique_id, {
                            fade: true,
                            speed: 'slow'
                        });
                    }, 12000);
                    $.extend($.gritter.options, {position: 'top-right'});
                    $('#header_notification_bar').pulsate({color: "#66bce6", repeat: 5});
                }, 2000);
            }
        }

    };

}();