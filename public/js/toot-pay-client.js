var loading = $('#loading');

$('#toot_idle').on('click', function () {
    $('#menu').modal('show');
    console.log('showing menu modal');
});

$('#check_balance').on('hidden.bs.modal', function () {
    console.log('page reloading!');
    location.reload();
});

var waiting_for_payment = $('#waiting_for_payment');
var enter_pin = $('#enter_pin');
var tap_card = $('#tap_card');
var pin_code = $('#pin_code');
var load_amount = $('#load_amount');
var enter_load_amount = $('#enter_load_amount');
var toot_card_id = $('#toot_card_id');

$('.backspace').click(function () {

    if (enter_pin.hasClass('in')) {
        pin_code.val(function (index, value) {
            return value.substr(0, value.length - 1);
        });
    }

    if (enter_load_amount.hasClass('in')) {
        load_amount.val(function (index, value) {
            return value.substr(0, value.length - 1);
        });
    }
});
$('.key').on('click', function () {

    if (enter_pin.hasClass('in')) {
        pin_code.val((pin_code.val()) + (this.value));
    }

    if (enter_load_amount.hasClass('in')) {
        load_amount.val((load_amount.val()) + (this.value));
    }
});

var menu_id = $('#menu_id');
$('#menu_reload').on('click', function () {
    $('#load_amount').val('');
    $('#menu').modal('toggle');
    $('#enter_load_amount').modal('show');
    console.log('showing enter_load_amount modal');
    menu_id.val(1);
    console.log('menu_id set to 1!');
});
$('#menu_balance').on('click', function () {
    $('#menu').modal('toggle');
    tap_card.modal('show');
    console.log('showing tap_card modal');
    menu_id.val(2);
    console.log('menu_id set to 2!');
});
$('#menu_order').on('click', function () {
    $('.modal-body p #loading_text').text('Loading menu items');
    $('#loading').modal('show');
    console.log('showing loading modal');
    $('#menu').modal('toggle');
    console.log('route to order!');
    window.location.replace('http://toot.pay/client/');
});

$('.modal').on('hidden.bs.modal', function () {
    $(this).find('#pin_code').val('');
});

tap_card.on('shown.bs.modal', function () {
    toot_card_id.focus();
    console.log('toot_card_id is on focus!');
    toot_card_id.blur(function () {
        setTimeout(function () {
            toot_card_id.focus();
        }, 0);
    });
    toot_card_id.change(function () {

        if ($(this).val().length == 10) {

            $.post('toot_card_check', {
                toot_card: $(this).val()
            }, function (response) {
                tap_card.modal('toggle');

                if (response == 'valid') {
                    $('#id').val(toot_card_id.val());
                    enter_pin.modal('show');
                    console.log('showing enter_pin modal');
                } else {
                    setTimeout(function () {
                        console.log('page reloading!');
                        location.reload();
                    }, 3000);
                    $('#invalid_card').modal('show');
                    console.log('showing invalid_card modal');
                }
                console.log(toot_card_id.val() + ' is ' + response + '!');
            });
        }
    });
});
enter_pin.on('hidden.bs.modal', function () {
    console.log(toot_card_id.val());
    toot_card_id.val('');
    console.log('toot_card_id has been reset!');
});

$('.submit-check').on('click', function () {
    $(this).button('loading').delay(1000).queue(function () {
        $(this).button('reset');
        $(this).dequeue();
    });

    if (enter_pin.hasClass('in')) {

        if (pin_code.val().length < 1) {
            setTimeout(function () {
                $('#empty_pin').modal('toggle');
            }, 3000);
            $('#empty_pin').modal({backdrop: false});
            console.log('showing empty_pin modal');
        } else {
            $.post('toot_card_authentication', {
                id: $('#id').val(),
                pin_code: pin_code.val()
            }, function (response) {

                if (response == 'correct') {
                    console.log('correct pin!');
                    enter_pin.modal('toggle');

                    if (menu_id.val() == 1) {
                        $('.modal-body p #loading_text').text('Processing load request. Please wait');
                        $('#loading').modal('show');

                        $.post('toot_card_reload_pending', {
                            id: $('#id').val(),
                            amount: load_amount.val()
                        }, function (response) {

                            if (response != null) {
                                console.log('reload_id is ' + response + '!');

                                var interval = setInterval(function () {
                                    $.post('toot_card_reload_status', {
                                        id: $('#id').val(),
                                        reload_id: response
                                    }, function (response) {

                                        if (response == 'pending') {

                                            if (!waiting_for_payment.hasClass('in')) {
                                                $('#loading').modal('toggle');
                                                $('#_amount').text(load_amount.val());
                                                console.log('_amount is set to ' + load_amount.val() + '!');

                                                setTimeout(function () {
                                                    console.log('page reloading!');
                                                    location.reload();
                                                }, 120000);
                                                waiting_for_payment.modal({backdrop: 'static'});
                                                console.log('showing waiting_for_payment modal');
                                            }
                                        } else {
                                            waiting_for_payment.modal('toggle');
                                            clearInterval(interval);

                                            if (response == 'paid') {
                                                $('#reload_paid').modal('show');
                                                console.log('showing reload_paid modal');

                                                $.post('toot_card_check_balance', {id: $('#id').val()}, function (response) {
                                                    $('#toot_card_details').html(response);
                                                }).done(function () {
                                                    setTimeout(function () {
                                                        console.log('page reloading!');
                                                        location.reload();
                                                    }, 30000);

                                                    $('#reload_paid').modal('toggle');
                                                    $('#check_balance').modal('show');
                                                    console.log('showing check_balance modal');
                                                });
                                            } else if (response == 'canceled') {
                                                setTimeout(function () {
                                                    console.log('page reloading!');
                                                    location.reload();
                                                }, 5000);

                                                $('#reload_canceled').modal('show');
                                                console.log('showing reload_canceled modal');
                                            }
                                        }
                                        console.log('reload status is ' + response + '!');
                                    });
                                }, 3000);
                            }
                        });
                    } else if (menu_id.val() == 2) {
                        $.post('toot_card_check_balance', {id: $('#id').val()}, function (response) {
                            $('#toot_card_details').html(response);
                        }).done(function () {
                            setTimeout(function () {
                                console.log('page reloading!');
                                location.reload();
                            }, 30000);

                            $('#check_balance').modal('show');
                            console.log('showing check_balance modal');
                        });
                    } else if (menu_id.val() == 3) {
                        sendMerchandisePurchase();
                    }
                } else if (response == 'incorrect') {
                    console.log('incorrect pin!');
                    setTimeout(function () {
                        $('#wrong_pin').modal('toggle');
                    }, 3000);
                    $('#wrong_pin').modal({backdrop: false});
                    console.log('showing wrong_pin modal');
                }
            });
        }
    }

    if (enter_load_amount.hasClass('in')) {

        if (load_amount.val().length < 1) {
            setTimeout(function () {
                $('#empty_load_amount').modal('toggle');
            }, 3000);
            $('#empty_load_amount').modal({backdrop: false});
            console.log('showing empty_load_amount modal');
        } else {
            enter_load_amount.modal('toggle');
            tap_card.modal('show');
            console.log('showing tap_card modal');
        }
    }
});

function sendMerchandisePurchase() {
    var table_data = [];

    $('tr.row-order').each(function () {
        var qty = parseFloat($('span.qty', this).text());
        var each_value = $('span.each', this);
        var each = parseFloat(each_value.text());
        var total = qty * each;
        var item = {};
        item['order_id'] = parseInt($('#order_id').text());
        item['toot_card_id'] = $('#id').val();
        item['merchandise_id'] = $(this).data('merchandise_id');
        item['quantity'] = qty;
        item['total'] = total;

        table_data.push(item);
    });

    $.post('merchandise_purchase',
        {table_data: JSON.stringify(table_data)},
        function (response) {
            console.log(response);

            if (response == 'insufficient_load') {
                setTimeout(function () {
                    $('#insufficient_load').modal('toggle');
                }, 3000);
                $('#insufficient_load').modal('show');
            } else if (response == 'success') {
                setTimeout(function () {
                    $('#payment_success').modal('toggle');
                }, 3000);
                $('#payment_success').modal('show');
                goToIdle();
            } else if (response == 'pending') {
                alert(response);

                //if (!waiting_for_payment.hasClass('in')) {
                //    $('#loading').modal('toggle');
                //    $('#_amount').text(load_amount.val());
                //    console.log('_amount is set to ' + load_amount.val() + '!');
                //
                //    setTimeout(function () {
                //        console.log('page reloading!');
                //        location.reload();
                //    }, 120000);
                //    waiting_for_payment.modal({backdrop: 'static'});
                //    console.log('showing waiting_for_payment modal');
                //}
            }
        });
}

$('#btn_cancel').on('click', function () {
    $(this).button('loading').delay(5000).queue(function () {
        $(this).button('reset');
        $(this).dequeue();
    });
    goToIdle();
});
$('#btn_pay_using_toot_card').on('click', function () {
    menu_id.val(3);
    console.log('menu_id set to 3!');
    tap_card.modal('show');
});
$('#btn_pay_using_cash').on('click', function () {
    //$('.modal-body p #loading_text').text('Processing order request. Please wait');
    //$('#loading').modal('show');
    sendMerchandisePurchase();
});

function goToIdle() {
    setTimeout(function () {
        window.location.href = 'http://toot.pay/client/idle';
    }, 2000);
}

function todaysMenu() {
    $.post('todays_menu', function (response) {
        $('#todays_menu').html(response);

        $('.modal-footer').on('click', 'button.btn-add-order', function () {
            var merchandise_id = $(this).data('merchandise_id');
            var name = $(this).data('name');
            var price = $(this).data('price');
            var id = $(this).data('id');
            var qty = $('#' + id + ' .modal-dialog .modal-content .modal-body .col-md-6 span.qty').text();
            addOrder(merchandise_id, name, price, qty);
        });

        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('span.qty').text(1);
        });

        var modal_qty = $('.modal-body .row .col-md-6');
        modal_qty.on('click', 'button.plus', function () {
            var qty = parseInt($(this).prev('span.qty').text());
            var new_qty = qty + 1;
            $(this).prev('span.qty').text(new_qty);
            compute();
        });
        modal_qty.on('click', 'button.minus', function () {
            var qty = parseInt($(this).next('span.qty').text());
            var new_qty = ((qty - 1) < 1) ? 1 : qty - 1;
            $(this).next('span.qty').text(new_qty);
            compute();
        });
    });
}

$(function () {
    todaysMenu();

    window.addOrder = (function (merchandise_id, name, price, qty) {
        var order_exist = false;

        $('tr.row-order').each(function () {
            if ($(this).data('merchandise_id') == merchandise_id) {
                var _qty = parseInt($('span.qty', this).text());
                $('span.qty', this).text(_qty + parseInt(qty));
                order_exist = true;
            }
        });

        console.log(order_exist);

        if (!order_exist) {
            $('#table_orders tbody').append(
                '<tr class="row-order" id="merchandise_' + merchandise_id + '" data-merchandise_id="' + merchandise_id + '">' +
                '<td><span class="name">' + name + '</span></td>' +
                '<td class="text-center table-cell-qty">' +
                '<button class="btn btn-default btn-sm minus"><i class="fa fa-minus"></i></button>' +
                '<span class="qty">' + qty + '</span>' +
                '<button class="btn btn-default btn-sm plus"><i class="fa fa-plus"></i></button>' +
                '</td>' +
                '<td>P<span class="each">' + price + '</span></td>' +
                '<td>P<span class="total"></span></td>' +
                '<td class="text-center"><button class="btn btn-danger btn-sm remove"><i class="fa fa-remove"></i></button></td>' +
                '</tr>'
            );
        }
        compute();

        var order_qty = $('#merchandise_' + merchandise_id + '');
        order_qty.on('click', 'td button.plus', function () {
            var qty = parseInt($(this).prev('span.qty').text());
            var new_qty = qty + 1;
            $(this).prev('span.qty').text(new_qty);
            compute();
        });
        order_qty.on('click', 'td button.minus', function () {
            var qty = parseInt($(this).next('span.qty').text());
            var new_qty = ((qty - 1) < 1) ? 1 : qty - 1;
            $(this).next('span.qty').text(new_qty);
            compute();
        });
        $('td button.remove').on('click', function () {
            $(this).closest('tr').remove();
            compute();
        });
    });

    window.compute = (function () {
        var grand_total = 0;
        var decimal_place = 2;
        var row_count = $('#table_orders tbody tr.row-order').length;

        $('tr.row-order').each(function () {
            var qty = parseInt($('span.qty', this).text());
            var each_value = $('span.each', this);
            var each = parseFloat(each_value.text());
            each_value.text(each.toFixed(decimal_place));
            var total = qty * each;
            $('span.total', this).text(total.toFixed(decimal_place));
            grand_total += total;
        });

        $("#grand_total").text(grand_total.toFixed(decimal_place));

        if (row_count < 1) {
            $('#btn_pay_using_toot_card').attr('disabled', 'disabled');
            $('#btn_pay_using_cash').attr('disabled', 'disabled');
        } else {
            $('#btn_pay_using_toot_card').removeAttr('disabled');
            $('#btn_pay_using_cash').removeAttr('disabled');
        }
    });
});

(function ($) {
    $.fn.blink = function (options) {
        var defaults = {delay: 500};
        var options = $.extend(defaults, options);
        return $(this).each(function (idx, itm) {
            setInterval(function () {
                if ($(itm).css('visibility') === 'visible') {
                    $(itm).css('visibility', 'hidden');
                }
                else {
                    $(itm).css('visibility', 'visible');
                }
            }, options.delay);
        });
    }
}(jQuery));

$('#touch').blink();