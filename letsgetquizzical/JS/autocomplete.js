
// NOTE : Parameters will be available either through $_GET or $_POST according
// to the method you choosed to use. 
// Here you will get your variable "variable1" this way : $_GET['variable1']
let arrList = [];


$(document).ready(function(){
    $('#addToList').on('keyup paste', function(){
        let input = $( this ).val();
        quiz_check(input);
    });
});

function quiz_check(input){
    
    $.ajax({
       url : 'searchQuizzes.php', //PHP file to execute
       type : 'GET', //method used POST or GET
       data : {term : input}, // Parameters passed to the PHP file
       success : function(result){ // Has to be there !
            let foundArray = JSON.parse(result)
           autocomplete(document.getElementById("addToList"), foundArray);

       },

       error : function(result, statut, error){ // Handle errors

       }

    });

}

function autocomplete(inp, arr) {
  var currentFocus;

  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;

      closeAllLists();

      if (!val) { return false;}
      currentFocus = -1;

      a = document.createElement("DIV");


      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");

      this.parentNode.appendChild(a);

      for (i = 0; i < arr.length; i++) {

        if (arr[i].toUpperCase().includes(val.toUpperCase())) {

          b = document.createElement("DIV");
          
          b.innerHTML += arr[i];

          b.innerHTML += `<input type="hidden" value="${arr[i]}">`;
              b.addEventListener("click", function(e) {
                addToList(this.getElementsByTagName("input")[0].value);
                inp.value = "";
                closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        currentFocus++;
        addActive(x);
      } else if (e.keyCode == 38) { //up
        currentFocus--;
        addActive(x);
      } else if (e.keyCode == 13) {
        e.preventDefault();
        if (currentFocus > -1) {
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
// <input type="hidden" id="custId" name="custId" value="3487">

function addToList(elem){
    arrList.push(elem);
    ///add value to an array & display on page
    $("#quizList").append(
        $("<li></li>", {
            class: "list-group-item",
            html: elem
        })
    );

    $("#qList").val(arrList);
}