// var parent = document.getElementById("regForm");
// var child = document.getElementById("child");
// const myNodelist = document.querySelectorAll("div");
// var step = document.getElementById("step");
// for(var i=0 ; i<2 ; i++)
// {
//     const dash = document.createElement("span");
//     dash.className="step";
//     step.appendChild(dash);

//     const tab = document.createElement("div");
//     tab.className="tab";
//     parent.insertBefore(tab,child);

//     //parent.appendChild(tab);

//     const p = document.createElement("p");
//     const inpt = document.createElement("input");
//     inpt.placeholder="Enter Number of Meals";
//     inpt.id = "no_of_meals";
//     inpt.type="Number";
//     inpt.classList.add("input");
//     p.appendChild(inpt);
//     tab.appendChild(p);
//     inpt.onblur = "meals()";
// }

// function meals()
// {
//   var num = parseInt(document.getElementById("no_of_meals"));
//   var tab = document.getElementsByClassName("tab");
//   for ( var i = 0 ; i < 2 ; i++)
//   {
//     const p = document.createElement("p");
//     const inpt = document.createElement("input");
//     inpt.placeholder="Enter Number of Meals";
//     inpt.id = "no_of_meals";
//     inpt.classList.add("input");
//     p.appendChild(inpt);
//     tab.appendChild(p);
//   }
// }
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab
function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n);
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    console.log("Submit button working");
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, z, c, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  z = x[currentTab].getElementsByTagName("select");

    // A loop that checks every input field in the current tab:
    for (c = 0; c < y.length; c++) {
      // If a field is empty...
      if (y[c].value == "") {
        // add an "invalid" class to the field:
        y[c].className += " invalid";
        // and set the current valid status to false
        valid = false;
      }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("diet_plan_step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var c, x = document.getElementsByClassName("diet_plan_step");
  for (c = 0; c < x.length; c++) {
    x[c].className = x[c].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}