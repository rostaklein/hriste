/**
 * Created by rostaklein on 20/05/2017.
 */
var date = moment().format('YYYY-MM-DD');
var field = 1;

$(document).ready(function(){
    $('.sport-choose .btn').click(function(){
        $(this).parent().find('.btn').removeClass('active');
        $(this).addClass('active');

        if(field!=this.dataset.field){
            field=this.dataset.field;
            getTimetable(date,field);
        }

    });
    $(document).on("click", ".time-cell:not(.disabled)", function(){
        $(this).toggleClass('active');
        selectTimecell(date, field, this.dataset.timestart);
    });
    getTimetable(date,field);
    $('.loader-overlay').hide();

    $(document).on("click", "#clear-times", function(){
        selectTimecell(1,1,1,true);
    });

    $('.change-form').click(function(){
        $('#change-form').slideToggle();
    })
});


function getTimetable(date, field){
    $('#times-loader').fadeIn("fast", function(){
        date = date || moment().format('YYYY-MM-DD');
        $.ajax({
            type:'POST',
            url: $('#getTimetableUrl').val(),
            data: {date: date, field: field},
            async: true,
            success: function(response) {
                $('#times-loader').fadeOut("fast",function(){
                    $('#enabled-times').html(response).fadeIn("fast");
                });
            }});
        return false;
    });
};

function selectTimecell(date, field, time, clear){
        $('#selected-times-loader').fadeIn("fast", function(){
            date = date || moment().format('YYYY-MM-DD');
            $.ajax({
                type:'POST',
                url: $('#selectedTimesUrl').val(),
                data: {date: date, field: field, time: time, clear: clear},
                async: true,
                success: function(response) {
                        $('#selected-times-loader').fadeOut("fast",function(){
                            $('#selected-times').html(response).fadeIn("fast");
                        });
                },
                error: function(){
                    setTimeout(function(){
                        window.location.reload();
                    },100);
                }
            });
            return false;
        });
        if(clear){
            getTimetable(window.date,window.field);
        }
}

$(function () {
    var now = moment();
    var until = moment().add(2, 'months').add(12, 'hours');
    $('#datetimepicker12').datetimepicker({
        inline: true,
        sideBySide: true,
        locale: 'cs',
        format: 'YYYY-MM-DD',
        minDate: now,
        maxDate: until,
    }).on("dp.change", function (e) {
        date=e.date.format('YYYY-MM-DD');
        //console.log(date);
        getTimetable(date, field);
    });
});


$('.login-submit').click(function(e) {
    e.preventDefault();
    form = $(this).parent();
    console.log(form);
    $.ajax({
        type: $(form).attr('method'),
        url: $(form).attr('action'),
        data: $(form).serialize(),
        success: function (data, status, object) {
            console.log(data);
            if (data.success) {
                makeMsg('Úspěšné přihlášení.', 'succ')
                location.reload();
            } else {
                console.log(data.message);
                makeMsg('Špatné uživatelské jméno, nebo heslo.', 'error');
            }
        }
    });
});

function makeMsg(message, type){
    if(type=="succ"){
        icon='fa fa-check';
        color='green';
    }else{
        icon='fa fa-exclamation-triangle';
        color='red';
    }
    $(document).ready(function(){
        iziToast.show({
            message: message,
            color: color,
            position: 'topRight',
            transitionIn: 'fadeInDown',
            icon: icon
        });
    });
}


$('.logout').click(function(e) {
    logout_path=$('#logout-path').val();
    console.log(logout_path);
    $.ajax({
        type: 'GET',
        url: logout_path,
        data: '',
        success: function () {
            location.reload();
        },
        error: function() {
            location.reload();
        }
    });
});