
let input = document.getElementById("sBar");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {

  if (event.keyCode === 13) {

    document.getElementById("hiddenSearch").submit();
  }
});