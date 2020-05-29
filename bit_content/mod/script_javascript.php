<script>
var ns = (document.layers)?1:0;
var ie = (document.all)?1:0;

function justnumber()
{
	if ((event.keyCode < 48 || event.keyCode > 57))
        event.returnValue = false;
}

function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
   
   alert(sText);
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
}

function check_array(arrValue,value) {
	var i=0;
	if (arrValue=='')
		return true;
	for (i=0;i<arrValue.length;i++) {
		if (arrValue[i]==value)
			return false;
	}
	return true;
}

function checkEmail(value) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)){
		return (true)
	}
	alert("Invalid E-mail Address! Please re-enter.")
	return (false)
}

function check_enter(frm) {
	if (event.keyCode==13) {
		frmMain.action='?submit=1';
		document.frmMain.submit;
	}
}

function justmoney()
{
    if ((event.keyCode < 48 || event.keyCode > 57)  && event.keyCode != 44 && event.keyCode != 46)
        event.returnValue = false;
}

function justTelp() {
	if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode!=45 & event.keyCode!=46) {
        event.returnValue = false;
	} 
}
</script>