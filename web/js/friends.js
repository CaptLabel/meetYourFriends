var min_start_search = 3;
var url_search_friend = "../searchfriend/";
var url_add_friend = "../addfriend/";
var url_delete_friend = "../deletefriend/";
var table_search = $('#table_search');

function searchFriend(val){
    if(val.length > min_start_search){
        $.ajax({
            url: url_search_friend+$('#key_secure').val()+'/'+val,
            method: 'POST',
            dataType: 'json',
            success : function(data){
                if(data.status == "OK"){
                    displaySearch(data.res_search);
                }
            }
        });
    }
}

function displaySearch(res_search){
    table_search.find('tr').remove();
    var html = "";
    if(res_search.length > 0){
        html += "<tr><th>Name</th><th colspan='2'>Email</th></tr>";
        for(var key in res_search){
            html += "<tr>";
            html += "<td>"+res_search[key].name+"</td>";
            html += "<td>"+res_search[key].email+"</td>";
            html += '<td onclick="add_friend(this, '+res_search[key].id+')"><span class="c-pointer">+<i class="glyphicon glyphicon-user"></i></span></td>';
            html += "</tr>";
        }
    }else{
        html += "<tr><td>No result</td></tr>";
    }
    table_search.append(html);
}
/**
 * Call after search
 * @param id
 */
function add_friend(this_elt, id){
    $.ajax({
        url: url_add_friend+$('#key_secure').val()+'/'+id,
        method: 'POST',
        dataType: 'json',
        success : function(data){
            if(data.status == "OK"){
                $(this_elt).parents('tr').remove();
            }
        }
    });
}

function delete_friend(this_elt, id){
    $.ajax({
        url: url_delete_friend+$('#key_secure').val()+'/'+id,
        method: 'POST',
        dataType: 'json',
        success : function(data){
            if(data.status == "OK"){
                $(this_elt).parents('tr').remove();
            }
        }
    });
}

$(document).ready(function(){
    $('#search').keyup(function () {
        searchFriend($(this).val());
    });
});
