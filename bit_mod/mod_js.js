var ns = (document.layers) ? 1 : 0;
var ie = (document.all) ? 1 : 0;

function justnumber() {
	if ((event.keyCode < 48 || event.keyCode > 57))
		event.returnValue = false;
}

function IsNumeric(sText) {
	var ValidChars = "0123456789.";
	var IsNumber = true;
	var Char;

	alert(sText);
	for (i = 0; i < sText.length && IsNumber == true; i++) {
		Char = sText.charAt(i);
		if (ValidChars.indexOf(Char) == -1) {
			IsNumber = false;
		}
	}
	return IsNumber;
}

function check_array(arrValue, value) {
	var i = 0;
	if (arrValue == '')
		return true;
	for (i = 0; i < arrValue.length; i++) {
		if (arrValue[i] == value)
			return false;
	}
	return true;
}

function checkEmail(value) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
		return (true)
	}
	alert("Invalid E-mail Address! Please re-enter.")
	return (false)
}

function check_enter(frm) {
	if (event.keyCode == 13) {
		frmMain.action = '?submit=1';
		document.frmMain.submit;
	}
}

function justmoney() {
	if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 44 && event.keyCode != 46)
		event.returnValue = false;
}

function justTelp() {
	if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 45 & event.keyCode != 46) {
		event.returnValue = false;
	}
}

function checkFileExtension(elem) {

	var filePath = elem.value;

	if (filePath.indexOf('.') == -1)
		return false;

	var validExtensions = new Array();
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

	validExtensions[0] = 'mp4';


	for (var i = 0; i < validExtensions.length; i++) {
		if (ext == validExtensions[i])
			return true;
	}

	return 'Extension File .' + ext.toLowerCase() + ' tidak di-perbolehkan!. Extension yang di-perbolehkan (.mp4) ';
}

function checkFileExtensionPdf(elem) {

	var filePath = elem.value;

	if (filePath.indexOf('.') == -1)
		return false;

	var validExtensions = new Array();
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

	validExtensions[0] = 'pdf';


	for (var i = 0; i < validExtensions.length; i++) {
		if (ext == validExtensions[i])
			return true;
	}

	return 'Extension File .' + ext.toLowerCase() + ' tidak di-perbolehkan!. Extension yang di-perbolehkan (.pdf) ';
}

function checkFileExtensionImage(elem) {

	return true;

	var filePath = elem.value;

	if (filePath.indexOf('.') == -1)
		return false;

	var validExtensions = new Array();
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

	validExtensions[0] = 'jpg';
	validExtensions[1] = 'bmp';
	validExtensions[2] = 'png';
	validExtensions[3] = 'gif';


	for (var i = 0; i < validExtensions.length; i++) {
		if (ext == validExtensions[i])
			return true;
	}

	return 'Extension File .' + ext.toLowerCase() + ' tidak di-perbolehkan!. Extension yang di-perbolehkan (.jpg .bmp .png .gif) ';
}

function checkFileExtensionInovasi(elem) {

	var filePath = elem.value;

	if (filePath.indexOf('.') == -1)
		return false;

	var validExtensions = new Array();
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

	validExtensions[0] = 'pdf';
	validExtensions[1] = 'doc';
	validExtensions[2] = 'xls';
	validExtensions[3] = 'docx';
	validExtensions[4] = 'xlsx';
	validExtensions[5] = 'ppt';
	validExtensions[6] = 'pptx';


	for (var i = 0; i < validExtensions.length; i++) {
		if (ext == validExtensions[i])
			return true;
	}

	return 'Extension File .' + ext.toLowerCase() + ' tidak di-perbolehkan!. Extension yang di-perbolehkan (.pdf .doc .xls .ppt .docx .xlsx .pptx) ';
}

function checkFileExtensionFlash(elem) {

	var filePath = elem.value;

	if (filePath.indexOf('.') == -1)
		return false;

	var validExtensions = new Array();
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

	validExtensions[0] = 'swf';


	for (var i = 0; i < validExtensions.length; i++) {
		if (ext == validExtensions[i])
			return true;
	}

	return 'Extension File .' + ext.toLowerCase() + ' tidak di-perbolehkan!. Extension yang di-perbolehkan (.swf) ';
}


function CleanWord(html) {

	html = html.replace(/<o:p>\s*<\/o:p>/g, "");
	html = html.replace(/<o:p>.*?<\/o:p>/g, "&nbsp;");

	// Remove mso-xxx styles.
	html = html.replace(/\s*mso-[^:]+:[^;"]+;?/gi, "");

	// Remove margin styles.
	html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*;/gi, "");
	html = html.replace(/\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"");

	html = html.replace(/\s*TEXT-INDENT: 0cm\s*;/gi, "");
	html = html.replace(/\s*TEXT-INDENT: 0cm\s*"/gi, "\"");

	html = html.replace(/\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"");

	html = html.replace(/\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"");

	html = html.replace(/\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"");

	html = html.replace(/\s*tab-stops:[^;"]*;?/gi, "");
	html = html.replace(/\s*tab-stops:[^"]*/gi, "");

	// Remove FONT face attributes.
	//if ( bIgnoreFont )
	//{
	html = html.replace(/\s*face="[^"]*"/gi, "");
	html = html.replace(/\s*face=[^ >]*/gi, "");

	html = html.replace(/\s*FONT-FAMILY:[^;"]*;?/gi, "");
	//}

	// Remove Class attributes
	html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3");

	// Remove styles.
	//if ( bRemoveStyles )
	html = html.replace(/<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3");

	// Remove empty styles.
	html = html.replace(/\s*style="\s*"/gi, '');

	html = html.replace(/<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;');

	html = html.replace(/<SPAN\s*[^>]*><\/SPAN>/gi, '');

	// Remove Lang attributes
	html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3");

	html = html.replace(/<SPAN\s*>(.*?)<\/SPAN>/gi, '$1');

	html = html.replace(/<FONT\s*>(.*?)<\/FONT>/gi, '$1');

	// Remove XML elements and declarations
	html = html.replace(/<\\?\?xml[^>]*>/gi, "");

	// Remove Tags with XML namespace declarations: <o:p></o:p>
	html = html.replace(/<\/?\w+:[^>]*>/gi, "");

	html = html.replace(/<H\d>\s*<\/H\d>/gi, '');

	html = html.replace(/<H1([^>]*)>/gi, '<div$1><b><font size="6">');
	html = html.replace(/<H2([^>]*)>/gi, '<div$1><b><font size="5">');
	html = html.replace(/<H3([^>]*)>/gi, '<div$1><b><font size="4">');
	html = html.replace(/<H4([^>]*)>/gi, '<div$1><b><font size="3">');
	html = html.replace(/<H5([^>]*)>/gi, '<div$1><b><font size="2">');
	html = html.replace(/<H6([^>]*)>/gi, '<div$1><b><font size="1">');

	html = html.replace(/<\/H\d>/gi, '</font></b></div>');

	html = html.replace(/<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;');

	// Remove empty tags (three times, just to be sure).
	html = html.replace(/<([^\s>]+)[^>]*>\s*<\/\1>/g, '');
	html = html.replace(/<([^\s>]+)[^>]*>\s*<\/\1>/g, '');
	html = html.replace(/<([^\s>]+)[^>]*>\s*<\/\1>/g, '');

	// Transform <P> to <DIV>
	var re = new RegExp("(<P)([^>]*>.*?)(<\/P>)", "gi"); // Different because of a IE 5.0 error
	html = html.replace(re, "<div$2</div>");

	return html;
}