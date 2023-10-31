
$('input#uploadNewQuizIcon').on('change', function(){
	const [file] = uploadNewQuizIcon.files
  if (file) {
    $x = URL.createObjectURL(file)

    console.log(URL.createObjectURL(file));
    $('#dnd').attr("src",$x);
  }

});


$('input#uploadProfileIcon').on('change', function(){
  const [file] = uploadProfileIcon.files
  if (file) {
    $x = URL.createObjectURL(file)

    console.log(URL.createObjectURL(file));
    $('#profileIcon').attr("src",$x);
  }

});