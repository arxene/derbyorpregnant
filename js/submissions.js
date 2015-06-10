/**
 * Checks/unchecks all  
 */
function selectAll( selectAllBtn ) {
  var checkboxes = document.querySelectorAll( "tbody input[type='checkbox']" );
  
  // if the select/unselect all button is checked, check all
  // if it's unchecked, uncheck all
  for ( var i = 0; i < checkboxes.length; ++i ) {
    checkboxes[i].checked = selectAllBtn.checked;
  }
}

/**
 * @param action can be either 'approved' or 'removed' 
 *
 * Remove checked items from interface, mark as approved or removed in
 * user_submissions, add to quiz_questions if approved.
 */
function processSubmission(action) {
  var xhr = getXmlHttpRequest();
  
  xhr.send("action=" + action + "&items=" + getCheckedItemsJson());
}

function getCheckedItemsJson() {
  var checkedItems = getCheckedItems();
  var checkedJson = [];
  
  for (var i = 0; i < checkedItems.length; ++i) {
    var name = checkedItems[i].querySelector('.nameCol').innerHTML;
    var food = checkedItems[i].querySelector('.foodCol').innerHTML;
    var comment = checkedItems[i].querySelector('.commentCol').innerHTML;
    var option = checkedItems[i].querySelector('.optionCol').innerHTML;
    
    checkedJson.push({'name':name, 'food':food, 'comment':comment, 'option':option});
  }
  
  return JSON.stringify(checkedJson);
}

/**
 * returns array of checked items 
 */
function getCheckedItems() {
  // get all rows under tbody that are checked
  var checkedRows = [];
  var allRows = document.querySelectorAll('tbody tr');
  
  for (var i = 0; i < allRows.length; ++i) {
    var thisRowsCheckbox = allRows[i].querySelector("input[type='checkbox']");

    if (thisRowsCheckbox.checked) {
      checkedRows.push(allRows[i]);
    }
  }
  
  return checkedRows;
}

function getXmlHttpRequest() {
  var httpRequest;
  
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    httpRequest = new XMLHttpRequest();
  } else if (window.ActiveXObject) { // IE
    try {
      httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } 
    catch (e) {
      try {
        httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } 
      catch (e) {}
    }
  }
  
  if (!httpRequest) {
    console.log("Unable to create XMLHttpRequest. Can't proceed with approving user submission.");
  }
  
  httpRequest.onreadystatechange = function() {
    if (httpRequest.readyState === 4 && httpRequest.status === 200) {
      // if PHP script indicates database was updated successfully,
      // then update UI to reflect changes
      // else notify user that the database wasn't updated for some reason
      document.querySelector('.dbResult').innerHTML = httpRequest.responseText;
      
      updateUi();
    }
  };
  httpRequest.open("POST", "submission-controller.php");
  httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  
  return httpRequest;
}

/**
 * Remove the checked rows from the table excluding the thead row
 */
function updateUi() {
  var checkedRows = getCheckedItems();
  
  for (var i = 0; i < checkedRows.length; ++i) {
    checkedRows[i].remove(); // remove this row from the UI
  }
}

