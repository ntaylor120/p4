function Trim(strValue)
{	
	return LTrim(RTrim(strValue));
}

function LTrim(strValue)
{
	var LTRIMrgExp = /^\s */;
	return strValue.replace(LTRIMrgExp, '');
}

function RTrim(strValue)
{
	var RTRIMrgExp = /\s *$/;
	return strValue.replace(RTRIMrgExp, '');
}

function isNumeric(strString)
   //  check for valid numeric strings	
   {
   var strValidChars = "0123456789.-";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
   }

function selectCheckbox(form_name,check_name) {	
	var formObj = document.forms[form_name];	
	var boxes = formObj[check_name].length;
	var num = 0;
	if(formObj.selectAll.checked) {
		for (var i = 0; i < boxes; i++) {
			if (formObj[check_name][i].checked == false) {
				formObj[check_name][i].checked = true;
			}
		}
	} else {
		for (var i = 0; i < boxes; i++) {
			if (formObj[check_name][i].checked == true) {
				formObj[check_name][i].checked = false;
			}
		}
	}
	
}
function disableSelectAll(form_name,checked) {
	var formObj = document.forms[form_name];
	if(checked == false) {
		formObj.selectAll.checked=false;
	}
}
function checkBoxesSel(form_name,check_name) {
	var formObj = document.forms[form_name];
	var checkboxesL = formObj[check_name].length;
	var message = "";
	
	for(var i=0; i< checkboxesL; i++) {
		if(formObj[check_name][i].checked) {
			message = message + "a";
		}
		
	}
	if(message == "") {
		alert("No items selected");
		return false;
	} else {
		return true;
	}
}

function delItems(form_name,check_name,operation,alert_text) {
	var formObj = document.forms[form_name];
	if(checkBoxesSel(form_name,check_name)) {
		if(confirm(alert_text)) {
			formObj.operation.value=operation;
			formObj.submit();
		}
	}
}

