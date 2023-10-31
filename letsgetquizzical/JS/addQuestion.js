let qn = 1;
let nextQ = false;
let a = 2;

function addQuestion(qNum) {

	let an = 2;
	$("div#questionsList").append(
		$("<div></div>", {
			class: "col-9 "
		}).append(
			$("<div></div>", {
			})
		).append(
			$("<div></div>", {
				html: `<span class="h4">Question ${qNum}:</span>`
			})
		).append(
			$("<div></div>", {
			}).append(
				$("<div></div>", {
					class: "form-check",
					id: `formCheckQuestion${qNum}`
				}).append(
					$("<label></label>", {
						class: "col-sm-3 col-form-label",
						for: `questionName${qNum}`,
						html: "Question Name: "
					})
				).append(
					$("<input></input>", {
						class: "form-check-input col-9",
						type: "text",
						name: `questionName${qNum}`,
						id:`questionName${qNum}`,
						required: "required"
					})
				).append("<br/> ").append(
					$("<label></label>", {
						class: "col-sm-3 col-form-label",
						for: `question${qNum}Answer1`,
						html: "Answer 1: "
					})
				).append(
					$("<input></input>", {
						class: "form-check-input col-9",
						type: "text",
						name: `question${qNum}Answer1`,
						id:`question${qNum}Answer1`,
						required: "required"
					})
				).append("<br/> <br/>").append(
					$("<p></p>", {
						class: "col-10 btn float-right addAns",
						name: `addAnswer`,
						html: "Add Multiple Choice Answer"
					}).on("click", function(){
						addAnswer(qNum, an++);
						//$("div#questionsList").append("<br/>");
					})
				)
			)
		)
	);

	if (nextQ){
		$(`#formCheckQuestion${qNum-1}`).append(`<input type="hidden" id="numAns" name="numAns${qNum-1}" value="${a-1}"/>`);
		//
		$(`#formCheckQuestion${qNum-1}`).append(
			$("<label></label>", {
						class: "col-sm-3 col-form-label",
						for: `questionName${qNum-1}`,
						html: "Correct Answer: "
					})
				).append(
			$("<select></select>", {
				class: "form-check-input col-3",
				type: "text",
				name: `question${qNum-1}RightAnswer`,
				id:`question${qNum-1}RightAnswer`,
				required: "required"
			}).append("<br/>")
		);

		for (var i = 1; i < a; i++) {
			$(`#question${qNum-1}RightAnswer`).append(
				$("<option></option>", {
					class: "form-check-input col-3",
					value: i,
					html: i
				})
			);
		}

		//Add lesson
		$(`#formCheckQuestion${qNum-1}`).append("<br/>").append(
			$("<label></label>", {
				class: "col-sm-3 col-form-label",
				for: `question${qNum-1}Lesson`,
				html: "Lesson/Explanation: "
			})
				
		).append(
			$("<textarea></textarea>", {
				class: "form-check-input col-9",
				type: "text",
				name: `question${qNum-1}Lesson`,
				id:`question${qNum-1}Lesson`,
				required: "required"
			})
		);

		$(`#formCheckQuestion${qNum-1}`).append('<div class="w-100"></div>').append("<br/>").append(
			$("<label></label>", {
				class: "col-sm-5 col-form-label",
				for: `question${qNum-1}LessonImage`,
				html: "(Optional) Lesson Image: "
			})
				
		).append(
			$("<input></input>", {
		      type: "file",
		      class: "form-control col-5 ",
		      name: `question${qNum-1}LessonImage`,
		      id:`question${qNum-1}LessonImage`
		    })
		);

		var item = document.getElementsByClassName("addAns")[0];
		item.parentNode.removeChild(item);
		a = 2;
	}
}
function addAnswer(qNum, aNum) {
	a++;
	$("p.addAns").parent().append(
		$("<label></label>", {
			class: "col-sm-3 col-form-label",
			for: `question${qNum}Answer${aNum}`,
			html: `Answer ${aNum}: `
		})
	).append(
		$("<input></input>", {
			class: "form-check-input col-9",
			type: "text",
			name: `question${qNum}Answer${aNum}`,
			id:`question${qNum}Answer${aNum}`,
		}).attr('required', 'required'));

	var item = document.getElementsByClassName("addAns")[0];
	item.parentNode.removeChild(item);

	$(`input#question${qNum}Answer${aNum}`).parent().append("<br/> <br/>").append(
			$("<p></p>", {
					class: "col-10 btn float-right addAns",
					name: `addAnswer`,
					html: "Add Multiple Choice Answer",
				}).on("click", function(){
					addAnswer(qNum, a);
					//$("div#questionsList").append("<br/>");
				})
		);


	console.log($(`input#question${qNum}Answer${aNum}`).parent());

}

$("p#addQ").on("click", function(){
	addQuestion(qn);
	$("div#questionsList").append("<br/>");
	qn++;
	nextQ = true;
})

$("p#lastQ").on("click", function(){
	lastQuestion(qn);
})

function lastQuestion(qNum){
	$(`#formCheckQuestion${qNum-1}`).append(`<input type="hidden" id="numAns" name="numAns${qNum-1}" value="${a-1}"/>`);

	$(`#formCheckQuestion${qNum-1}`).append(
			$("<label></label>", {
						class: "col-sm-3 col-form-label",
						for: `questionName${qNum-1}`,
						html: "Correct Answer: "
					})
				).append(
			$("<select></select>", {
				class: "form-check-input col-3",
				type: "text",
				name: `question${qNum-1}RightAnswer`,
				id:`question${qNum-1}RightAnswer`
			}).append("<br/>")
		);

		for (var i = 1; i < a; i++) {
			$(`#question${qNum-1}RightAnswer`).append(
				$("<option></option>", {
					class: "form-check-input col-3",
					value: i,
					html: i
				})
			);
		}

		//Add lesson
		$(`#formCheckQuestion${qNum-1}`).append("<br/>").append(
			$("<label></label>", {
				class: "col-sm-3 col-form-label",
				for: `question${qNum-1}Lesson`,
				html: "Lesson/Explanation: "
			})
				
		).append(
			$("<textarea></textarea>", {
				class: "form-check-input col-9",
				type: "text",
				name: `question${qNum-1}Lesson`,
				id:`question${qNum-1}Lesson`
			})
		);

		$(`#formCheckQuestion${qNum-1}`).append('<div class="w-100"></div>').append("<br/>").append(
			$("<label></label>", {
				class: "col-sm-5 col-form-label",
				for: `question${qNum-1}LessonImage`,
				html: "(Optional) Lesson Image: "
			})
				
		).append(
			$("<input></input>", {
		      type: "file",
		      class: "form-control col-5 ",
		      name: `question${qNum-1}LessonImage`,
		      id:`question${qNum-1}LessonImage`
		    })
		);

		$("form#quizForm :input").each(function(){
		 var input = $(this); // This is the jquery object of the input, do what you will
		 console.log(input);
		 //input.prop('required',true);
		});

		var item = document.getElementsByClassName("addAns")[0];
		item.parentNode.removeChild(item);
		var aQ = document.getElementById("addQ");
		aQ.parentNode.removeChild(aQ);
		var lQ = document.getElementById("lastQ");
		lQ.parentNode.removeChild(lQ);
		document.getElementById("subBut").style.opacity = "1"; 
		a = 2;

}