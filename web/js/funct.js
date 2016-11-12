/**
 * Created by Tonio on 19/10/2016.
 */

function strToDate(str){
    var date_r = str.replace(' 00:00:00.000000', '');
    var date_s = date_r.split("-");
    if(date_s.length != 3){
        return "invalid format";
    }
    return new Date(date_s[0], parseFloat(date_s[1]-1), date_s[2]);
}

function strDateClean(str){
    var date_r = str.replace(' 00:00:00.000000', '');
    var date_s = date_r.split('-');
    return date_s[2]+"/"+date_s[1]+"/"+date_s[0];
}

function getDateObject(str){
    var date_r = str.replace(' 00:00:00.000000', '');
    var date_s = date_r.split("-");
    var obj;
    if(date_s.length == 3){
        obj = {
            day:  parseFloat(date_s[2]),
            month: date_s[1],
            year: date_s[0]
        };
    }else{
        obj = "invalid format"
    }
    return obj;
}

function getNumberOfDayInMonth(this_date) {
    var d = new Date(this_date.getFullYear(), this_date.getMonth() + 1, 0);
    return d.getDate();
}

function getLastDayOfMonth(this_date){
    return new Date(this_date.getFullYear(), this_date.getMonth() + 1, 0);
}

function getFirstDayOfMonth(this_date){
    return new Date(this_date.getFullYear(), this_date.getMonth(), 1);
}


Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
};