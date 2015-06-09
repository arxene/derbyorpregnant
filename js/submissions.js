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