/* This is loaded in the <head> of each page */

var pullquote = {
	init : function(arrOptions) {

	// Get options and set defaults as needed
		var $skiplinks = arrOptions[0];
		var $skipintlinks = arrOptions[1];
		var $defside = arrOptions[2];
		var $altsides = arrOptions[3];
		var $alttext = arrOptions[4];
//		var $capfirst = arrOptions[5];
		var $qcontainer = arrOptions[5];
		if ($qcontainer=='') {$qcontainer='blockquote';};
		var $defquoteclass = arrOptions[6];
		if ($defquoteclass=='') {$defquoteclass='pullquote';};
		var $defquoteclassAlt = arrOptions[7];
		if ($defquoteclassAlt=='') {$defquoteclass='pullquote pqRight';};

		if ($defside == 'right') {
			var $quoteclass = $defquoteclassAlt;
		} else {
			var $quoteclass = $defquoteclass;
		}

	// Check that the browser supports the methods used
		if (!document.getElementById || !document.createElement || !document.appendChild) return false;
		
		var oElement, oClassName, oPullquote, oPullquoteP, oQuoteContent, i, j, k;
	// Find all span elements
		var arrElements = document.getElementsByTagName('span');
		var oRegExp = new RegExp("(^|\\s)pullquote(\\s|$)");

	// loop through all span elements
		for (i=0; i<arrElements.length; i++) {
			oElement = arrElements[i];
	// Proceed if current element is pullquote
			oClassName = oElement.className
			if (oRegExp.test(oClassName)) {
		// re-init oAltQuote
				var oAltQuote = undefined;
		// Create the blockquote and p elements
				oPullquote = document.createElement($qcontainer);
				oPullquoteP = document.createElement('p');


		// If a side is user-specified, use that, otherwise follow the alternation
				var oSideRegExp = new RegExp("(^|\\s)(pqRight|pqLeft)(\\s|$)");
				var oSideFound = oSideRegExp.exec(oClassName);
				if (oSideFound && (oSideFound[2]=="pqLeft" || oSideFound[2]=="pqRight")) {
					if (oSideFound[2]=="pqRight") {
						$quoteclass = $defquoteclassAlt;
						$defside = ''; // doesn't matter if first quote or not....
					} else {
						$quoteclass = $defquoteclass;
						$defside = '';
					}
				} else if ($altsides) {
			// If alternating sides, add "pqRight" class every second loop
					if ($defside != '') {
						$defside = ''; // skip first quote
					} else if ($quoteclass == $defquoteclass) {
						$quoteclass = $defquoteclassAlt;
					} else {
						$quoteclass = $defquoteclass;
					}
				} else {
					if ($defside == 'right') {
						var $quoteclass = $defquoteclassAlt;
					} else {
						var $quoteclass = $defquoteclass;
					}
				}
				oPullquote.className = $quoteclass;

// If the first child of the span is a comment, its content is our quote...
// thx: https://www.engr.uga.edu/adminsite/modules/htmlarea/example-fully-loaded.html
				if ($alttext && oElement.firstChild && oElement.firstChild.nodeType == 8) { // 8 == comment
						oAltQuote = document.createTextNode(pullquote.trim(oElement.firstChild.data));
						oPullquoteP.appendChild(oAltQuote);
				} else { // otherwise get all content as normal
					for(j=0;j<oElement.childNodes.length;j++) { //loop through the children of <span>
						var oCurrChild = oElement.childNodes[j];
						if (oCurrChild.nodeType == 8) {
							// if an HTML comment, do nothing!

						} else if ( oCurrChild.nodeType == 1 && oCurrChild.tagName.toLowerCase() == "a" && ( // if it's an HTML "a" tag, and...
							( $skiplinks && '#' != oCurrChild.getAttribute('href').substr(0,1) ) || // external link, or...
							( $skipintlinks && '#' == oCurrChild.getAttribute('href').substr(0,1) )  || // internal (href="#id") link, or...
							( !oCurrChild.getAttribute('href') ) || // no href, or...
							( '' == oCurrChild.getAttribute('href') ) // href is blank
						) ) {

							// Apply the append loop to node's decendants, but not the A tag itself  (assumes there is not another A tag within the A tag, as that would be illegal)
							for(k=0;k<oCurrChild.childNodes.length;k++) {
								// oAltQuote = oCurrChild.childNodes[k].cloneNode(true);
								oAltQuote = pullquote.dupeNode(oCurrChild.childNodes[k],true);
								oPullquoteP.appendChild(oAltQuote);
							}

						} else {
							// Standard "copy everything and append to P node"
							// oAltQuote = oElement.childNodes[j].cloneNode(true);
							if (oCurrChild.nodeType == 1 && oCurrChild.tagName.toLowerCase() == "a") {
								//strip out "name" attributes
							}
							if ( oAltQuote = pullquote.dupeNode(oCurrChild,true) ) {
								oPullquoteP.appendChild(oAltQuote);
							}
						}
					}
				}
			// only insert the pull-quote if it is not empty!
				if(oAltQuote != undefined && oAltQuote.data != '') {
			// append text to the paragraph node
					oPullquote.appendChild(oPullquoteP);
			// Insert the blockquote element before the span element's parent element
					oElement.parentNode.parentNode.insertBefore(oPullquote,oElement.parentNode);
				}
			}
		}
	}, // end function init

	dupeNode : function($the_node, $include_all) {
/*
based on dupeNode function by Stephen Rider
http://striderweb.com/nerdaphernalia/features/js-dupenode-function/
*/
		var i;
		var $new_node = $the_node.cloneNode(false);

		// remove "id" and "name" attributes from tags
		if ($new_node.nodeType == 1) {
			if ( $new_node.getAttribute('name') ) { $new_node.removeAttribute('name') };
			if ( $new_node.getAttribute('id') ) { $new_node.removeAttribute('id') };
		}
		// if there are children...
		if ($include_all && $the_node.hasChildNodes()) {
			for(i=0;i<$the_node.childNodes.length;i++) {
				// recursively pass the child back to THIS function
				$child_node = arguments.callee($the_node.childNodes[i],true);
				$new_node.appendChild($child_node);
			}
		}
		return $new_node;
	},

	/*
	*  Javascript trim, ltrim, rtrim
	*  http://www.webtoolkit.info/javascript-trim.html
	*/

	trim : function($str, $chars) {
		return pullquote.ltrim(pullquote.rtrim($str, $chars), $chars);
	},

	ltrim : function($str, $chars) {
		$chars = $chars || "\\s";
		return $str.replace(new RegExp("^[" + $chars + "]+", "g"), "");
	},

	rtrim : function($str, $chars) {
		$chars = $chars || "\\s";
		return $str.replace(new RegExp("[" + $chars + "]+$", "g"), "");
	},

	// addEvent function from http://www.quirksmode.org/blog/archives/2005/10/_and_the_winner_1.html
	addEvent : function(obj, type, fn) {
		if (obj.addEventListener)
			obj.addEventListener( type, fn, false );
		else if (obj.attachEvent)
		{
			obj["e"+type+fn] = fn;
			obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
			obj.attachEvent( "on"+type, obj[type+fn] );
		}
	}

}; // end var $pullquote

// Adds init to window.load event and passes along settings pulled from DB
function pullQuoteOpts(arrOptions) {
	pullquote.addEvent(window, 'load', function(){pullquote.init(arrOptions);});
}
