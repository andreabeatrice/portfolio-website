let url_string = window.location.href;
let url = new URL(url_string);
let conversationID = url.searchParams.get("rid");

const myArr = conversationID.split("a");
from = $('#sender').val();

if (from == myArr[0])
	to = myArr[1];
else
	to = myArr[0];

var start =0, u= '/IMY220/u19130938/messageFunction.php';

$(document).ready(function(){
	
	load();

	$('form').submit(function(e) {
		let ms = fixThis($('#message').val());

		$.ajax({
       url : u, //PHP file to execute
       type : 'POST', //method used POST or GET
       data : {
       	message: ms,
			from: from,
			to: to}, // Parameters passed to the PHP file
       success : function(result){ // Has to be there !
          //const res = JSON.parse(result);
       },

       error : function(result, statut, error){ // Handle errors
       }

    });

		$('#message').val('');

		return false;
	})
})

function load() {
	$.get(u + "?start=" + start, function(result){
		if(result.items){
			result.items.forEach(item => {
				start = item.id;
				$('#messages').append(renderMessage(item));

			})
		};

		load();
	});
}

function renderMessage(item){
	let userN = "";
	$.ajax({
	  	 async: false,
       url : 'user-details.php', //PHP file to execute
       type : 'POST', //method used POST or GET
       data : {
       	id : item.sent_by
       }, // Parameters passed to the PHP file
       success : function(result){ // Has to be there !
          const res = JSON.parse(result);
          userN = res.email_address.split('@')[0];
       },

       error : function(result, statut, error){ // Handle errors

       }

    });


	if (item.conversation_code.includes(from) && item.conversation_code.includes(to)){
		let cc = item.conversation_code.split("a");

		if(item.sent_by === to){
			return `<div class="msg-dark mb-1 p-3 w-100 "><b>${userN}</b>: ${item.message}</div><br/>`;
		}
		else {
			return `<div class="msg p-3 w-100 "><b>${userN}</b>: ${item.message}</div><br/>`;
		}

	}

}

fixThis = (mess) => {
	var newStr = mess.replace(/'/g, '’');
	newStr = newStr.replace(/"/g, '”');

	return newStr;
}