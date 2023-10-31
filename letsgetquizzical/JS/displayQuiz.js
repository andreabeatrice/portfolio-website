let url_string = window.location.href;
let url = new URL(url_string);
let quiz = url.searchParams.get("quiz");
let title = quiz.replace(/[-]/gm, ' ');
let q = 0;
let numRight = 0;
let rat = 0;

$("h5#quiztitle").html(title);

let jsonFile = quiz.replace(/[-|?]/gm, '');

getQuestions(`quizzes/${jsonFile}.json`);

function getQuestions(param){
  let url = param.includes(".");

  if(url){
    //The Promise must use jQuery’s getJSON function to load a JSON file which is specified by the URL-parameter.
    let getQuestion = new Promise((resolve, reject) => {
      $.getJSON(param, json =>{
        resolve(json);
      });
    });

    getQuestion.then(
      question => {

        numQ = 0;

        question.forEach((element) => {
          
          if(numQ == 0){
            $("h6.card-subtitle").html(question[0].QuizDescription);
          }
          else {
            if (numQ <= (question.length-1)){
              createQuestionCard(question[numQ], numQ);
            }
          }
          numQ++;
        });
        
      }

    );
    return getQuestion;
  }
}



function createQuestionCard(array, num) {
  let v = num.toString();

  let newQ =  $("<li></li>", {
        class: "list-group-item",
        html: `<h5>${array.QuestionName}</h5>`,
        id: `list-group-item${num}`
      }).append(
        $("<div></div>", {
          class: "form-check",
          id: `form-check${num}`,
          datacorrect: false
        }).append(
          $("<input></input>", {
            class: "form-check-input custom-control-input",
            type: "radio",
            name:`quizQuestion${num}`,
            id:`question${num}Answer1`
          })
        ).append(
          $("<label></label>", {
            class: "form-check-label custom-control-label styled",
            for: `question${num}Answer1`,
            html: array["1"]
          })
          )
      );

      $("ul.list-group").append(newQ);
      
      for (var i = 2; i <= array.numAnswers; i++) {
        $x = `${i}`;
        $(`li#list-group-item${num}`).append(
          $("<div></div>", {
          class: "form-check",
          id: `form-check${num}`,
          datacorrect: false
        }).append(
          $("<input></input>", {
            class: "form-check-input custom-control-input",
            type: "radio",
            name:`quizQuestion${num}`,
            id:`question${num}Answer${i}`
          })).append(
            $("<label></label>", {
            class: "form-check-label custom-control-label styled",
            for: `question${num}Answer${i}`,
            html: array[$x]
          })
          ));
      }
      $(`div#form-check${num}`).append("<br/>");

      $(`li#list-group-item${num}`).append(
        $("<div></div>", {
          class: "a",
        })

      );
      $(`input#question${num}Answer${array.Correct}`).parent().attr("datacorrect", true);

  addOnChangeEvent(newQ, array);
}

function addOnChangeEvent(element, array){
  let inputs = $(element.find("input"));
  
  inputs.on("change", function(){
    q++;

    for (var i = 0, r=inputs, l=r.length; i < l;  i++){
        r[i].disabled = true;

    }
    let alert = $(this).parent().siblings('.a');

    if ($(this).parent().attr('datacorrect') == "true"){
      numRight++;
      if(alert.hasClass("alert-danger"))
        alert.removeClass("alert-danger");

      alert.addClass("alert alert-success mt-0 m-2");
      alert.attr("style", "opacity: 1;");
      alert.html(`<i class='fas fa-check-circle mr-1'></i><b>Correct: ${element.find(`[datacorrect=true]`).find('label').html()}</b><br/> `+array.Lesson);

    }
    else {
      alert.addClass("alert alert-danger mt-0 m-2");
      alert.attr("style", "opacity: 1;");
      alert.html(`<i class='fas fa-times-circle mr-1'></i><b>Incorrect<br/> Correct Answer: ${element.find(`[datacorrect=true]`).find('label').html()}</b><br/>`+array.Lesson);
    }

    if (array.LessonImage === "") {

    }
    else {
      $(this).parent().siblings('.a').append(
        $("<img></img>", {
          src: `gallery/${array.LessonImage}`,
          class: 'img-fluid quizLessonImage',
          alt: `Lesson Image: ${array.LessonImage}`
        })
      );
    }

    let qs = $($("ul").find("li"));
    if(q == qs.length-7){
      //quiz complete
      $("div.card-body").append("<br/>").append(
          $("<div></div>", {
            class: "alert alert-light",
            role: "alert"
          }).append(
            $("<h4></h4>", {
              class: "alert-heading",
              html: `You got ${numRight} out of ${q}`
            })
          ).append(
            $("<div></div>", {
              class: 'stars float-left'
            }).append(
              $("<form></form>", {
                action: "completeQuiz.php",
                id: "rateForm"
              }).append(
                $("<p></p>", {
                  html: "rate this quiz?",
                  class: "btn float-left",
                  id: "rateButton"
                })
              ).append("<br/><br/><br/>").append(
                $("<p></p>", {
                  class: "btn float-left",
                  name: `completedQuiz`,
                  html: "Add To Completed Quizzes",
                  id: "doneQuiz"
                })
              )
            )
          )
        );

      $("#doneQuiz").click(function(event){
        event.preventDefault();

        addToDone(numRight, title, 0, qs.length-7)
      });

      //Add To Completed Quizzes action 
      $("p#rateButton").on("click", function(){
        $("form#rateForm").empty("#rateButton");

        $("form#rateForm").append(
          $("<div></div>", {
            class: "float-left"          
          }).append(
          $("<input></input>", {
            class: "star star-5",
            id: "star-5",
            type: "radio",
            value: "5",
            name: "star"
          })
          ).append(
            $("<label></label>", {
              class: "star star-5",
              for: "star-5",
            })).append(
                $("<input></input>", {
                  class: "star star-4",
                  id: "star-4",
                  type: "radio",
                  value: "4",
                  name: "star"
                })
              ).append(
                $("<label></label>", {
                  class: "star star-4",
                  for: "star-4",

                })
              ).append(
                $("<input></input>", {
                  class: "star star-3",
                  id: "star-3",
                  type: "radio",
                  value: "3",
                  name: "star"
                })
              ).append(
                $("<label></label>", {
                  class: "star star-3",
                  for: "star-3",

                })
              ).append(
                $("<input></input>", {
                  class: "star star-2",
                  id: "star-2",
                  type: "radio",
                  value: "2",
                  name: "star"
                })
              ).append(
                $("<label></label>", {
                  class: "star star-2",
                  for: "star-2",

                })
              ).append(
                $("<input></input>", {
                  class: "star star-1",
                  id: "star-1",
                  type: "radio",
                  value: "1",
                  name: "star"
                })
              ).append(
                $("<label></label>", {
                  class: "star star-1",
                  for: "star-1",

                })
              
          ).append("<br/><br/><br/>").append(
            $("<input></input>", {
              type: "submit",
              class: "btn float-left",
              name: `completedQuiz`,
              value: "Add To Completed Quizzes",
              id: "doneQuiz"
            })
        ));


        //Add To Completed Quizzes action 
       $('input[name="star"]').on("change", function() {
         rat = $('input[name="star"]:checked').val();
        });

       $("#doneQuiz").click(function(event){
          event.preventDefault();

          addToDone(numRight, title, rat, qs.length-7);
          

        });


      });

      
      $("div.card").addClass("mb-5");
    }
  });
}




function addToDone(numRight, title, rating, outOf){

  if ($("p#doneQuiz").attr("disabled") != "disabled"){
    $("p#doneQuiz").attr("disabled", true);
      document.getElementById('doneQuiz').style.backgroundColor = "grey";
      document.getElementById('doneQuiz').innerHTML = "Added to Completed Quizzes";
 //activity_id quiz_id user_id score user_rating

  $.ajax({
       url : 'completeQuiz.php', //PHP file to execute
       type : 'POST', //method used POST or GET
       data : {
        quiz_name: title,
        score : numRight,
        user_rating: rating,
        from: outOf
        }, // Parameters passed to the PHP file
       success : function(result){ // Has to be there !
            /*let foundArray = JSON.parse(result)
           autocomplete(document.getElementById("addToList"), foundArray);*/
           console.log(result);
           $(document).ajaxStop(function(){
            window.location.href = "home.php";
          });
       },

       error : function(result, statut, error){ // Handle errors

       }

    });
  }
}

