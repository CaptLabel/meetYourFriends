/**
 * Created by Tonio on 18/10/2016.
 */
var init_date = new Date();
var current_date = new Date();
var url_get_stay = "../getstay/";
var url_modify_stay = "../modifystay/";
var url_remove_stay = "../removestay/";

var main_calendar = $('#main_calendar');
var stay_popup = $('#stay_popup');
var match_popup = $('#match_popup');

var myStay = {};
var myMatch = {};

var label_month = {
    1 : "January",
    2 : "February",
    3 : "March",
    4 : "April",
    5 : "May",
    6 : "June",
    7 : "July",
    8 : "August",
    9 : "September",
    10: "October",
    11 : "November",
    12 : "December"
};

getStay();

function initSlide(current_slide){
    var y = current_slide.getFullYear();
    var m = current_slide.getMonth();
    var firstDay = new Date(y, m, 1);
    var html_tab = '<tr ui-month="'+parseFloat(m+1)+'">';
    for(var j = 0; j < getWhiteSpace(firstDay.getDay()); j++){
        html_tab += "<td></td>";
    }
    for(var i = 1; i <= getNumberOfDayInMonth(current_slide); i++){
        html_tab += '<td ui-day="'+i+'">'+i+'</td>';
        if(isThatDay(current_slide, i, 0)){
            html_tab += '</tr><tr ui-month="'+parseFloat(m+1)+'">';
        }
    }
    main_calendar.append(html_tab);
    fillCurrentMonthLbl(current_slide);
}

function initStay(current_slide){
    $('#block_schedule').find('tbody').html("");
    for(var key in myStay){
        if(myStay.hasOwnProperty(key)){
            fillSliderStay(current_slide, myStay[key]);
        }
    }
}

//TODO delete ?
function initMatch(current_slide){
    for(var key in myMatch){
        if(myMatch.hasOwnProperty(key)){
            fillSliderMatch(current_slide, myMatch[key]);
        }
    }
}

function isThatDay(this_date, current_day, slt_day) {
    var ret = false;
    var d = new Date(this_date.getFullYear(), this_date.getMonth(), current_day);
    if (d.getDay() == slt_day) {
        ret = true
    }
    return ret;
}

function getWhiteSpace(get_day){
    var ret;
    if(get_day == 0){
        ret = 6;
    }else{
        ret = get_day - 1
    }
    return ret;
}

function changeSlide(action){
    var cur_mon = current_date.getMonth();
    $('tr[ui-month]').remove();
    switch (action){
        case "next":
            current_date.setMonth(cur_mon+1);
            break;
        case "prev":
            current_date.setMonth(cur_mon-1);
            break;
    }
}

function fillSliderStay(this_date, item){
    var cur_d = strToDate(item.dateArrival.date);
    var end_d = strToDate(item.dateDeparture.date);
    var first_d = getFirstDayOfMonth(this_date);
    var last_d = getLastDayOfMonth(this_date);
    var nb_d = getNumberOfDayInMonth(this_date);
    var strt_comp;
    if(cur_d >= first_d && cur_d <= last_d){
        strt_comp = cur_d.getDate();
        fillBlockSchedule(item);
    }else if((end_d >= first_d && end_d <= last_d) || (cur_d <= first_d && end_d >= last_d)){
        strt_comp = 1;
        cur_d = first_d;
        fillBlockSchedule(item);
    }else{
        return;
    }
    for(var i=strt_comp; i<=nb_d; i++){
        if(cur_d <= end_d){
            if(typeof item.match != "undefined"){
                $('[ui-day="'+i+'"]').addClass('dm');
            }else{
                $('[ui-day="'+i+'"]').addClass('ds');
            }
            cur_d = cur_d.addDays(1);
        }
    }
}

function getStay(){
    main_calendar.addClass('loading');
    $('tr[ui-month]').remove();
    $.ajax({
        url: url_get_stay+$('#key_secure').val(),
        method: 'POST',
        dataType: 'json',
        success : function(data){
            myStay = data.stay;
            initSlide(current_date);
            initStay(current_date);
            main_calendar.removeClass('loading');
        }
    });
}

function modifyStay(id){
    if(typeof id == "undefined"){id = "add";}
    $.ajax({
        url: url_modify_stay+$('#key_secure').val()+'/'+id,
        method: 'POST',
        dataType: 'json',
        data: {
            'date_arr' : $('#stay_dateArrival_year').val()+"/"+$('#stay_dateArrival_month').val()+"/"+$('#stay_dateArrival_day').val(),
            'date_dep' : $('#stay_dateDeparture_year').val()+"/"+$('#stay_dateDeparture_month').val()+"/"+$('#stay_dateDeparture_day').val(),
            'city' : $('#stay_city').val()
        },
        success : function(data){
            stay_popup.find('.block_error').hide();
            if(data.status == "OK"){
                getStay();
                stay_popup.modal('hide');
                if(data.match.length > 0){
                    fillMatchList(data.match);
                    match_popup.modal('show');
                }
            }else{
                if(typeof data.message != "undefined"){
                    stay_popup.find('.block_error').html(data.message).show();
                }
            }
        }
    });
}

function removeStay(id){
    $.ajax({
        url: url_remove_stay+$('#key_secure').val()+'/'+id,
        method: 'POST',
        dataType: 'json',
        success : function(){
            $('#delete_popup').modal('hide');
            getStay();
        }
    });
}


function fillCurrentMonthLbl(current_slide){
    $('#current_month').html(label_month[(current_slide.getMonth()+1)] + " " + current_slide.getFullYear());
}

function fillBlockSchedule(item){
    var ifMatch = typeof item.match != "undefined";
    var html = "";
    html += "<tr"+((ifMatch)?" class='match'":"")+">";
    html += "<td>"+item.city+"</td>";
    html += "<td>"+strDateClean(item.dateArrival.date)+"</td>";
    html += "<td>"+strDateClean(item.dateDeparture.date)+"</td>";
    html += "<td class='c-pointer td-center' data-toggle='modal' data-target='#stay_popup' ui-stay-id="+item.id+"><i class='glyphicon glyphicon-pencil'></i></td>";
    html += "<td class='c-pointer td-center' data-toggle='modal' data-target='#delete_popup' onclick='putIdPD("+item.id+")'><i class='glyphicon glyphicon-trash'></i></td>";
    html += "</tr>";
    if(ifMatch){
        for(var key in item.match){
           if(item.match.hasOwnProperty(key)){
               var it = item.match[key];
               html += "<tr class='match-line'>";
               html += "<td colspan='3' class='match-name'>- "+it.name+"</td>";
               html += "<td colspan='1'>"+it.date_arrival+"</td>";
               html += "<td colspan='1'>"+it.date_departure+"</td>";
               html += "</tr>";
           }
        }
    }
    $('#block_schedule').find('tbody').append(html);
}

function putIdPD(id){
    $('.delete_stay').attr('ui-id-d', id);
}

function fillMatchList(list){
    var match_list = $('#match_list');
    match_list.html('');
    var html = "";
    for(var key in list){
        if(list.hasOwnProperty(key)){
            html += "<tr>";
            html += "<td>"+list[key].name+"</td>";
            html += "<td>"+list[key].date_arrival+"</td>";
            html += "<td>"+list[key].date_departure+"</td>";
            html += "</tr>";
        }
    }
    match_list.html(html);
}

function fromPickerToSelect(date, target){
    var date_s = date.split('/');
    $('#'+target).find('select').each(function(idx){
        $(this).val(parseFloat(date_s[idx]));
    });
}

function initMinDate(){



    //console.log(strToDate());

    console.log($('#stay_dateArrival_picker').val());

    return init_date;
}

$(document).ready(function(){
    $('.ui_change_month').click(function(){
        var ui_action = $(this).attr('ui-action');
        changeSlide(ui_action);
        initSlide(current_date);
        initStay(current_date);
    });
    $('.save_stay').click(function(){
        modifyStay($(this).attr('ui-id'));
    });
    $("body").delegate('[data-target="#stay_popup"]', "click", function(){
        var this_id = $(this).attr('ui-stay-id');
        if(typeof this_id == "undefined"){
            $('.ui_add_evt').show();
            $('.ui_mod_evt').hide();
            $('#stay_city').val('');
            $('.picker-init').each(function(){
                $(this).val(convertDate(current_date));
            });
            $('.selectDate').find('select').each(function(){
                var key = $(this).attr('id').split('_');
                var obj_date = getDateObject(convertDate(current_date, "Y-m-d"));
                $(this).val(obj_date[key[2]]);
            });
        }else{
            $('.ui_add_evt').hide();
            $('.ui_mod_evt').show();
            $('.save_stay.ui_mod_evt').attr('ui-id', this_id);
            for(var key in myStay){
                if(myStay.hasOwnProperty(key)){
                    if(myStay[key].id == this_id){
                        var date_arr = myStay[key].dateArrival.date;
                        var date_dep = myStay[key].dateDeparture.date;
                        $('#stay_city').val(myStay[key].city);
                        $('.picker-init').each(function(){
                            var dS;
                            (($(this).attr('id').indexOf('Arrival') != '-1')?dS = date_arr:dS = date_dep);
                            $(this).val(strDateClean(dS));
                        });
                        $('.selectDate').find('select').each(function(){
                            var dS;
                            (($(this).attr('id').indexOf('Arrival') != '-1')?dS = date_arr:dS = date_dep);
                            var key = $(this).attr('id').split('_');
                            var obj_date = getDateObject(dS);
                            $(this).val(obj_date[key[2]]);
                        });
                    }
                }
            }
        }
    });
    $('.delete_stay').click(function(){
        removeStay($(this).attr('ui-id-d'));
    });

    $(".picker-init").datepicker({
        dateFormat: 'dd/mm/yy',
        minDate : init_date,
        onSelect : function(){
            fromPickerToSelect($(this).val(), $(this).attr('ui-select-target'));
        },
        beforeShow: function(){
            if($(this).attr('ui-select-target') == "stay_dateDeparture"){
                $(this).datepicker('option', 'minDate', $('#stay_dateArrival_picker').datepicker('getDate'))
            }
        }
    });
});