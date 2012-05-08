function pullquote_preview_pop(corePath, stylePath, styleFile) {
	var openPars = "height=10,width=10,scrollbars=yes,toolbar=yes";
	var varWin = window.open("","Pic", openPars);
	if (!varWin) {
		alert("I can't show the preview for some reason.  If you have a pop-up blocker enabled, turning it off might help.");
		return true;
	}
	varWin.document.open();
	var varWinDoc = varWin.document;
	
	varWinDoc.writeln("<!DOCTYPE html PUBLIC \"-\/\/W3C\/\/DTD XHTML 1.0 Transitional\/\/EN\"");
	varWinDoc.writeln("        \"http:\/\/www.w3.org\/TR\/2000\/REC-xhtml1-20000126\/DTD\/xhtml1-transitional.dtd\">");
	varWinDoc.writeln("<html xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\" xml:lang=\"en\" lang=\"en\">");
	varWinDoc.writeln("<head>");
	varWinDoc.writeln("<title>Style Preview<\/title>");
	varWinDoc.writeln('<link rel="stylesheet" href="' + corePath + '/jspullquotes-core.css" type="text/css" />');
	if (styleFile == 'default_style') {
		varWinDoc.writeln('<link rel="stylesheet" href="' + corePath + '/jspullquotes-default.css" type="text\/css" />');
	} else {
		varWinDoc.writeln('<link rel="stylesheet" href="' + stylePath + '/' + styleFile + '" type="text\/css" />');
	}
	varWinDoc.writeln("<\/head>"); 
	varWinDoc.writeln("<body>");
	varWinDoc.writeln("<p>Dolor amet nulla, ullamcorper luptatum nulla in nulla duis, iriuredolor illum et dolor, odio exerci commodo, esse commodo. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<\/p>");
	varWinDoc.writeln("<blockquote class=\"pullquote\"><p>Semper ubi sub ubi<\/p><\/blockquote>");
	varWinDoc.writeln("<p>Augue exerci esse autem, ex aliquam crisare ad esse at, nostrud quis dolore qui iusto in, magna adipiscing. <span class=\"pullquote\">Semper ubi sub ubi<\/span>. Hendrerit blandit te in et augue volutpat delenit consectetuer te delenit te ut iriuredolor ut eros accumsan facilisis nisl lorem. Molestie at feugait at exerci ea aliquip, euismod praesent, duis sed minim dolore! <\/p>"); 
	varWinDoc.writeln("<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<\/p>");	varWinDoc.writeln("<\/body>");
	varWinDoc.writeln("<\/html>");
	varWin.resizeTo(450, 400);
	varWinDoc.close();
	varWin.focus();
	return false;
}
