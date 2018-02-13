$(function(){
    var $d = $(document);
    var $w = $(window);
    var $st = $('.arrow-top');

    var $hd = $('.header-area');
    var $sc = $('.search-container');

    if ($('.ichecks').length) {
        $('.ichecks').iCheck({checkboxClass: 'icheckbox_square-blue'});
    }
    if ($('.iradios').length) {
        $('.iradios').iCheck({radioClass: 'iradio_square-blue'});
    }

    // просмотр миниатюр в таблицах
    var $slider = $('.a-slider');
    if ($slider.length) {
        $slider.colorbox({
            photo: true,
            maxWidth: '80%',
            maxHeight: '90%'
        });
    }

    var $pages = $('.colorbox-page');
    if ($pages.length) {
        $pages.colorbox({
            width: 600,
            height: 400,
            maxWidth: '80%',
            maxHeight: '90%'
        });
    }

    // анимация для якорей
    $('a[href^="#"]').click(function (e) {
        var href = $(this).attr('href');

        if (href == '#' || $(this).hasClass('camp-tabs-item')) return;
        e.preventDefault();

        $('html, body').stop().animate({
            scrollTop: ($(href).length ? ($(href).offset().top - 100) : 0 + 'px')
        }, 500);
    });

    // скролл наверх страницы
    $d.on('scroll', function () {
        if ($d.scrollTop() > $w.height() / 2) {
            $st.addClass('fixed');
        } else {
            $st.removeClass('fixed');
        }

        if ($d.scrollTop() > $hd.height()) {
            $sc.addClass('fixed');
        } else {
            $sc.removeClass('fixed');
        }
    }).scroll(); // run

    set_datepickers();
    set_datepickers_time();

    var $s2 = $('.select2');
    if ($s2.length) {
        $s2.each(function(){
            switch ($(this).data('type')) {
                case 'camp':
                    $(this).select2({templateResult: formatRepoCamp});
                    break;

                case 'items':
                    $(this).select2({
                        minimumResultsForSearch: Infinity,
                        width: 'resolve'
                    });
                    break;

                case 'escort':
                    $(this).select2({
                        templateResult: formatRepoEscort,
                        tokenSeparators: [','],
                        tags: true
                    });
                    break;
            }
        });
    }

    // отправка формы
    $('form').on('beforeSubmit', function(){
        loader.show($(this).closest('div'));
    });

    var $sel_cur = $('#select_items_currency');
    if ($sel_cur.length) {
        $sel_cur.on('change', function(){
            $('.items-currency-txt').text(this.value);
            $('.items-currency-val').val(this.value);
        }).trigger('change');
    }

    // прокрутка левого блока с поиском
    var $search_sidebar = $('.page-filters');
    var $content_filters = $('.page-with-filters-content');

    if ($search_sidebar.length && $search_sidebar.outerHeight() < $content_filters.outerHeight()) {
        // сайдбар меньше контента - позволяем залипать при прокрутке
        var top_search = $search_sidebar.offset().top;
        var height_search = $search_sidebar.outerHeight();
        var height_content = $content_filters.outerHeight();
        var slide_margin = 20;
        var smax_top = (height_content - height_search + top_search - slide_margin - 2);

        $(document).on('scroll', function () {
            var s_top = $(this).scrollTop();

            if (s_top >= smax_top) {
                $search_sidebar.css('top', (smax_top - top_search + slide_margin) + 'px');
            } else if (s_top >= (top_search - slide_margin)) {
                $search_sidebar.css('top', (s_top - top_search + slide_margin) + 'px');
            } else {
                $search_sidebar.css('top', 0);
            }
        }).scroll();
    }

    // прокрутка правого блока бронирования
    var $camp_order = $('.camp-order-lg');
    var $camp_content = $('.camp-content');

    if ($camp_order.length && $camp_content.length && $camp_order.outerHeight() < $camp_content.outerHeight()) {
        // сайдбар меньше контента - позволяем залипать при прокрутке
        var top_order = $camp_order.offset().top;
        var height_o_content = $camp_content.outerHeight();
        var slide_o_margin = 20;

        var height_order;
        var max_top;

        $(document).on('scroll', function () {
            height_order = $camp_order.outerHeight();
            max_top = (height_o_content - height_order + top_order - slide_o_margin - 2);

            var o_top = $(this).scrollTop();

            if (o_top >= max_top) {
                $camp_order.css('top', (max_top - top_order + slide_o_margin) + 'px');
            } else if (o_top >= (top_order - slide_o_margin)) {
                $camp_order.addClass('fixed');
                $camp_order.css('top', o_top - top_order + slide_o_margin + 'px');
            } else {
                $camp_order.removeClass('fixed');
                $camp_order.css('top', 0);
            }
        }).scroll();
    }
});

function show_yandex_map(e, sel) {
    e.preventDefault();

    $.colorbox({
        href: $(sel).attr('href'),
        width: 800,
        height: 600,
        maxWidth: '80%',
        maxHeight: '90%'
    });

    return false;
}

function show_camp_points(e, sel) {
    e.preventDefault();

    $.colorbox({
        href: $(sel).attr('href'),
        width: 600,
        height: 400,
        maxWidth: '80%',
        maxHeight: '90%'
    });

    return false;
}

function formatRepoCamp(state) {
    if (!state.img) return;

    var html = '';
    html+= '<span class="h-50">';
    html+= '<img src="' + state.img + '" width="50" height="50" class="pull-left" />&nbsp;&nbsp;' + state.text.substr(0, 60);

    if (state.full) html += '<br/>&nbsp;&nbsp;<small>' + state.full.substr(0, 60) + '</small>';
    if (state.org) html += '<br/>&nbsp;&nbsp;<small>' + state.org.substr(0, 60) + '</small>';

    html+= '<span class="clearfix"></span></span>';

    return $(html);
}

function formatRepoEscort(state) {
    if (!state.country) return;

    return $('<span>' + state.text + '<br/><small>' + state.country + ', ' + state.region +'</small></span>');
}

function set_datepickers(sel) {
    var $dt = sel ? $(sel) : $('.datepickers');
    if ($dt.length) {
        $dt.datepicker({
            format: 'dd.mm.yyyy',
            language: 'ru'
        }).on('change', function (o, n) {
            $(this).datepicker("hide");
        });
    }
}

function set_datepickers_time(sel) {
    var $dt = sel ? $(sel) : $('.datepickers-time');
    if ($dt.length) {
        $dt.datetimepicker({
            format: 'dd.mm.yyyy hh:ii',
            weekStart: 1,
            autoclose: true,
            language: 'ru'
        });
    }
}

$.ajaxSetup({
    type: 'POST',
    dataType: 'JSON',
    data: {'_csrf': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function(){

    },
    complete: function(){
        loader.hide();
    },
    error: function(jqXHR, textStatus, errorThrown) {
        loader.hide();
        console.log(jqXHR);
    }
});

jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};

var loader = {
    show: function(selector, timer, options) {
        var $selector = $(selector);
        var t = timer || 0;

        $selector.css({'position':'relative'});
        $selector.append('<div class="loader"></div>');
        var $loader = $('.loader', $selector);

        if (options) $loader.css(options);

        setTimeout(function(){
            $loader.css({height: $selector.outerHeight(),
                width: $selector.outerWidth()});
            $loader.show();
        }, t);
    },
    hide: function() {
        $('.loader').remove();
    }
};

function ajaxData(from, to, url, data, callback) {
    $.ajax({
        url: url,
        data: data,
        beforeSend: function(){
            loader.show(from);
        },
        success: function(resp) {
            if (callback) {
                callback(resp);
            } else {
                $(to).html(resp.content);
            }
        }
    });
}

function scrollTo(top) {
    $('html,body').stop().animate({scrollTop: top});
}

function change_order_base_item(from, to) {
    var $from = $(from);
    var $to = $(to);

    var data = $from.find('option[value="' + $from.val() + '"]').data();
    $to.html(data['price']);
}

function set_vote(obj) {
    var $obj = $(obj);

    var $items = $obj.closest('.review-votes').find('li');
    var $parent = $obj.parent();

    $items.removeClass('active');
    $items.find('input').prop('disabled', true);

    $parent.addClass('active');
    $parent.find('input').prop('disabled', false);
}

function word_amount(amount, words, full) {
    var w;

    switch (amount % 10) {
        case 1:
            w = words[1];
            break;

        case 2:
        case 3:
        case 4:
            w = words[2];
            break;

        default:
            w = words[0];
    }

    if (amount % 100 >= 11 && amount % 100 <= 20) w = words[0];
    if (full) w = (amount + ' ' + w);

    return w;
}

function toggle_map(sel) {
    var $map = $(sel);
    $map.toggleClass('hidden');
}

function slide_menu(sel) {
    var $sel = $(sel);
    var h = $sel.find('li').eq(1).outerHeight();
    var t;

    $sel.toggleClass('opened');
    if ($sel.hasClass('opened')) {
        $sel.css('height', (h * $sel.find('li').length) + 20 + 'px');
        clearTimeout(t);
    } else {
        $sel.css('height', 0);
        t = setTimeout(function(){
            // чтобы меню не съеживалось при разворачивании
            $sel.attr('style', '');
        }, 500);
    }
}

function show_items(obj) {
    $(obj).closest('.base-items').find('.row-prices-more, .row-prices').toggleClass('hidden');
}