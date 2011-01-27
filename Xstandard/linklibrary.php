<?php
/*************************************** IMPORTANT **************************************
** - If you are having problems getting this or other Web Services to work, the most likely
** - cause is security settings.  This Web Service runs in the security context of the Web
** - server - other words, it does not have many rights.  Make sure file permissions on this
** - PHP file, the destination folder and the temp folder is set to Everyone.
**
**
** - To customize this service, search for "ADD CUSTOM CODE HERE" below.
*************************************** IMPORTANT **************************************/



/*************************** OPTIONAL - CHANGE THESE SETTINGS **************************/
define("XS_NAME", "Link Library");
define("XS_LOG_FILE", "%Y-%m-%d.log"); // Log file for errors or debug information. Can be a template like "%Y-%m-%d.log" where %Y is 4 digit year, %m is 2 digit month and %d is 2 digit day.  This will produce a log file like "2003-12-29.log".
define("XS_BROWSE_ENABLED", true); //Enable the ablity to browse this library.
define("XS_SEARCH_ENABLED", true); //Enabled the ability to search this library.
define("XS_AUTHORIZATION_CODE", ""); //An authorization code used to restrict access to this Web Service. You get this code from your account on the xstandard.com Web site.
/*************************** OPTIONAL - CHANGE THESE SETTINGS ***************************/

















/****************************************************************************************
** - Purpose:	Library Service
**
** - Input:	SOAP
** - Output:	SOAP
**
** - Version:	2.0
** - Date:	2007-02-14
**
** - Note:	This script requires the following:
**			- PHP 4.3.0+
**
** - Copyright (c) 2002 Belus Technology Inc and its licensors. All rights reserved.
****************************************************************************************/














/**
* SAXY Lite is a non-validating, but lightweight and fast SAX parser for PHP, modelled on the Expat parser
* @package saxy-xmlparser
* @subpackage saxy-xmlparser-lite
* @version 0.17
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/saxy/ SAXY Home Page
* SAXY is Free Software
**/

if (!defined('SAXY_INCLUDE_PATH')) {
	define('SAXY_INCLUDE_PATH', (dirname(__FILE__) . "/"));
}

/** current version of SAXY Lite */
define ('SAXY_LITE_VERSION', '0.17');

/** initial saxy lite parse state, before anything is encountered */
define('SAXY_STATE_NONE', 0);
/** saxy lite parse state, processing main document */
define('SAXY_STATE_PARSING', 1);

/**
* SAXY_Parser_Base is a base class for SAXY and SAXY Lite
* @package saxy-xmlparser
* @version 0.87
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/saxy/ SAXY Home Page
* SAXY is Free Software
**/

/** the initial characters of a cdata section */
define('SAXY_SEARCH_CDATA', '![CDATA[');
/** the length of the initial characters of a cdata section */
define('SAXY_CDATA_LEN', 8);
/** the initial characters of a notation */
define('SAXY_SEARCH_NOTATION', '!NOTATION');
/** the initial characters of a doctype */
define('SAXY_SEARCH_DOCTYPE', '!DOCTYPE');
/** saxy parse state, just before parsing an attribute */
define('SAXY_STATE_ATTR_NONE', 0);
/** saxy parse state, parsing an attribute key */
define('SAXY_STATE_ATTR_KEY', 1);
/** saxy parse state, parsing an attribute value */
define('SAXY_STATE_ATTR_VALUE', 2);

/**
* The base SAX Parser class
*
* @package saxy-xmlparser
* @author John Heinstein <johnkarl@nbnet.nb.ca>
*/
class SAXY_Parser_Base {
	/** @var int The current state of the parser */
	var $state;
	/** @var int A temporary container for parsed characters */
	var $charContainer;
	/** @var Object A reference to the start event handler */
	var $startElementHandler;
	/** @var Object A reference to the end event handler */
	var $endElementHandler;
	/** @var Object A reference to the data event handler */
	var $characterDataHandler;
	/** @var Object A reference to the CDATA Section event handler */
	var $cDataSectionHandler = null;
	/** @var boolean True if predefined entities are to be converted into characters */
	var $convertEntities = true;
	/** @var Array Translation table for predefined entities */
	var $predefinedEntities = array('&amp;' => '&', '&lt;' => '<', '&gt;' => '>',
							'&quot;' => '"', '&apos;' => "'"); 
	/** @var Array User defined translation table for entities */
	var $definedEntities = array();
	
		
	/**
	* Constructor for SAX parser
	*/					
	function SAXY_Parser_Base() {
		$this->charContainer = '';
	} //SAXY_Parser_Base
	
	/**
	* Sets a reference to the handler for the start element event 
	* @param mixed A reference to the start element handler 
	*/
	function xml_set_element_handler($startHandler, $endHandler) {
		$this->startElementHandler = $startHandler;
		$this->endElementHandler = $endHandler;
	} //xml_set_element_handler
	
	/**
	* Sets a reference to the handler for the data event 
	* @param mixed A reference to the data handler 
	*/
	function xml_set_character_data_handler($handler) {
		$this->characterDataHandler =& $handler;
	} //xml_set_character_data_handler
	
	/**
	* Sets a reference to the handler for the CDATA Section event 
	* @param mixed A reference to the CDATA Section handler 
	*/
	function xml_set_cdata_section_handler($handler) {
		$this->cDataSectionHandler =& $handler;
	} //xml_set_cdata_section_handler
	
	/**
	* Sets whether predefined entites should be replaced with their equivalent characters during parsing
	* @param boolean True if entity replacement is to occur 
	*/
	function convertEntities($truthVal) {
		$this->convertEntities = $truthVal;
	} //convertEntities
	
	/**
	* Appends an array of entity mappings to the existing translation table
	* 
	* Intended mainly to facilitate the conversion of non-ASCII entities into equivalent characters 
	* 
	* @param array A list of entity mappings in the format: array('&amp;' => '&');
	*/
	function appendEntityTranslationTable($table) {
		$this->definedEntities = $table;
	} //appendEntityTranslationTable
	

	/**
	* Gets the nth character from the end of the string
	* @param string The text to be queried 
	* @param int The index from the end of the string
	* @return string The found character
	*/
	function getCharFromEnd($text, $index) {
		$len = strlen($text);
		$char = $text{($len - 1 - $index)};
		
		return $char;
	} //getCharFromEnd
	
	/**
	* Parses the attributes string into an array of key / value pairs
	* @param string The attribute text
	* @return Array An array of key / value pairs
	*/
	function parseAttributes($attrText) {
		$attrText = trim($attrText);	
		$attrArray = array();
		$maybeEntity = false;			
		
		$total = strlen($attrText);
		$keyDump = '';
		$valueDump = '';
		$currentState = SAXY_STATE_ATTR_NONE;
		$quoteType = '';
		
		for ($i = 0; $i < $total; $i++) {								
			$currentChar = $attrText{$i};
			
			if ($currentState == SAXY_STATE_ATTR_NONE) {
				if (trim($currentChar != '')) {
					$currentState = SAXY_STATE_ATTR_KEY;
				}
			}
			
			switch ($currentChar) {
				case "\t":
					if ($currentState == SAXY_STATE_ATTR_VALUE) {
						$valueDump .= $currentChar;
					}
					else {
						$currentChar = '';
					}
					break;
				
				case "\x0B": //vertical tab	
				case "\n":
				case "\r":
					$currentChar = '';
					break;
					
				case '=':
					if ($currentState == SAXY_STATE_ATTR_VALUE) {
						$valueDump .= $currentChar;
					}
					else {
						$currentState = SAXY_STATE_ATTR_VALUE;
						$quoteType = '';
						$maybeEntity = false;
					}
					break;
					
				case '"':
					if ($currentState == SAXY_STATE_ATTR_VALUE) {
						if ($quoteType == '') {
							$quoteType = '"';
						}
						else {
							if ($quoteType == $currentChar) {
								if ($this->convertEntities && $maybeEntity) {
								    $valueDump = strtr($valueDump, $this->predefinedEntities);
									$valueDump = strtr($valueDump, $this->definedEntities);
								}
								
								$attrArray[trim($keyDump)] = $valueDump;
								$keyDump = $valueDump = $quoteType = '';
								$currentState = SAXY_STATE_ATTR_NONE;
							}
							else {
								$valueDump .= $currentChar;
							}
						}
					}
					break;
					
				case "'":
					if ($currentState == SAXY_STATE_ATTR_VALUE) {
						if ($quoteType == '') {
							$quoteType = "'";
						}
						else {
							if ($quoteType == $currentChar) {
								if ($this->convertEntities && $maybeEntity) {
								    $valueDump = strtr($valueDump, $this->predefinedEntities);
									$valueDump = strtr($valueDump, $this->definedEntities);
								}
								
								$attrArray[trim($keyDump)] = $valueDump;
								$keyDump = $valueDump = $quoteType = '';
								$currentState = SAXY_STATE_ATTR_NONE;
							}
							else {
								$valueDump .= $currentChar;
							}
						}
					}
					break;
					
				case '&':
					//might be an entity
					$maybeEntity = true;
					$valueDump .= $currentChar;
					break;
					
				default:
					if ($currentState == SAXY_STATE_ATTR_KEY) {
						$keyDump .= $currentChar;
					}
					else {
						$valueDump .= $currentChar;
					}
			}
		}

		return $attrArray;
	} //parseAttributes		
	
	/**
	* Parses character data
	* @param string The character data
	*/
	function parseBetweenTags($betweenTagText) {
		if (trim($betweenTagText) != ''){
			$this->fireCharacterDataEvent($betweenTagText);
		}
	} //parseBetweenTags	
	
	/**
	* Fires a start element event
	* @param string The start element tag name
	* @param Array The start element attributes
	*/
	function fireStartElementEvent($tagName, $attributes) {
		call_user_func($this->startElementHandler, $this, $tagName, $attributes);
	} //fireStartElementEvent		
	
	/**
	* Fires an end element event
	* @param string The end element tag name
	*/
	function fireEndElementEvent($tagName) {
		call_user_func($this->endElementHandler, $this, $tagName);
	} //fireEndElementEvent
	
	/**
	* Fires a character data event
	* @param string The character data
	*/
	function fireCharacterDataEvent($data) {
		if ($this->convertEntities && ((strpos($data, "&") != -1))) {
			$data = strtr($data, $this->predefinedEntities);
			$data = strtr($data, $this->definedEntities);
		}
		
		call_user_func($this->characterDataHandler, $this, $data);
	} //fireCharacterDataEvent	
	
	/**
	* Fires a CDATA Section event
	* @param string The CDATA Section data
	*/
	function fireCDataSectionEvent($data) {
		call_user_func($this->cDataSectionHandler, $this, $data);
	} //fireCDataSectionEvent	
} //SAXY_Parser_Base


/**
* The SAX Parser class
*
* @package saxy-xmlparser
* @subpackage saxy-xmlparser-lite
* @author John Heinstein <johnkarl@nbnet.nb.ca>
*/
class SAXY_Lite_Parser extends SAXY_Parser_Base {
	/**
	* Constructor for SAX parser
	*/
	function SAXY_Lite_Parser() {
		$this->SAXY_Parser_Base();
		$this->state = SAXY_STATE_NONE;
	} //SAXY_Lite_Parser

	/**
	* Returns the current version of SAXY Lite
	* @return Object The current version of SAXY Lite
	*/
	function getVersion() {
		return SAXY_LITE_VERSION;
	} //getVersion

	/**
	* Processes the xml prolog, doctype, and any other nodes that exist outside of the main xml document
	* @param string The xml text to be processed
	* @return string The preprocessed xml text
	*/
	function preprocessXML($xmlText) {
		//strip prolog
		$xmlText = trim($xmlText);
		$total = strlen($xmlText);

		for ($i = 0; $i < $total; $i++) {
			if ($xmlText{$i} == '<') {
				switch ($xmlText{($i + 1)}) {
					case '?':
					case '!':
						break;
					default:
						$this->state = SAXY_STATE_PARSING;
						return (substr($xmlText, $i));
				}
			}
		}
	} //preprocessXML

	/**
	* The controlling method for the parsing process
	* @param string The xml text to be processed
	* @return boolean True if parsing is successful
	*/
	function parse ($xmlText) {
		$xmlText = $this->preprocessXML($xmlText);
		$total = strlen($xmlText);

		for ($i = 0; $i < $total; $i++) {
			$currentChar = $xmlText{$i};

			switch ($this->state) {
				case SAXY_STATE_PARSING:

					switch ($currentChar) {
						case '<':
							if (substr($this->charContainer, 0, SAXY_CDATA_LEN) == SAXY_SEARCH_CDATA) {
								$this->charContainer .= $currentChar;
							}
							else {
								$this->parseBetweenTags($this->charContainer);
								$this->charContainer = '';
							}
							break;

						case '>':
							if ((substr($this->charContainer, 0, SAXY_CDATA_LEN) == SAXY_SEARCH_CDATA) &&
								!(($this->getCharFromEnd($this->charContainer, 0) == ']') &&
								($this->getCharFromEnd($this->charContainer, 1) == ']'))) {
								$this->charContainer .= $currentChar;
							}
							else {
								$this->parseTag($this->charContainer);
								$this->charContainer = '';
							}
							break;

						default:
							$this->charContainer .= $currentChar;
					}

					break;
			}
		}

		return true;
	} //parse

	/**
	* Parses an element tag
	* @param string The interior text of the element tag
	*/
	function parseTag($tagText) {
		$tagText = trim($tagText);
		$firstChar = $tagText{0};
		$myAttributes = array();

		switch ($firstChar) {
			case '/':
				$tagName = substr($tagText, 1);
				$this->fireEndElementEvent($tagName);
				break;

			case '!':
				$upperCaseTagText = strtoupper($tagText);

				if (strpos($upperCaseTagText, SAXY_SEARCH_CDATA) !== false) { //CDATA Section
					$total = strlen($tagText);
					$openBraceCount = 0;
					$textNodeText = '';

					for ($i = 0; $i < $total; $i++) {
						$currentChar = $tagText{$i};

						if (($currentChar == ']') && ($tagText{($i + 1)} == ']')) {
							break;
						}
						else if ($openBraceCount > 1) {
							$textNodeText .= $currentChar;
						}
						else if ($currentChar == '[') { //this won't be reached after the first open brace is found
							$openBraceCount ++;
						}
					}

					if ($this->cDataSectionHandler == null) {
						$this->fireCharacterDataEvent($textNodeText);
					}
					else {
						$this->fireCDataSectionEvent($textNodeText);
					}
				}
				else if (strpos($upperCaseTagText, SAXY_SEARCH_NOTATION) !== false) { //NOTATION node, discard
					return;
				}
				else if (substr($tagText, 0, 2) == '!-') { //comment node, discard
					return;
				}

				break;

			case '?':
				//Processing Instruction node, discard
				return;

			default:
				if ((strpos($tagText, '"') !== false) || (strpos($tagText, "'") !== false)) {
					$total = strlen($tagText);
					$tagName = '';

					for ($i = 0; $i < $total; $i++) {
						$currentChar = $tagText{$i};

						if (($currentChar == ' ') || ($currentChar == "\t") ||
							($currentChar == "\n") || ($currentChar == "\r") ||
							($currentChar == "\x0B")) {
							$myAttributes = $this->parseAttributes(substr($tagText, $i));
							break;
						}
						else {
							$tagName .= $currentChar;
						}
					}

					if (strrpos($tagText, '/') == (strlen($tagText) - 1)) { //check $tagText, but send $tagName
						$this->fireStartElementEvent($tagName, $myAttributes);
						$this->fireEndElementEvent($tagName);
					}
					else {
						$this->fireStartElementEvent($tagName, $myAttributes);
					}
				}
				else {
					if (strpos($tagText, '/') !== false) {
						$tagText = trim(substr($tagText, 0, (strrchr($tagText, '/') - 1)));
						$this->fireStartElementEvent($tagText, $myAttributes);
						$this->fireEndElementEvent($tagText);
					}
					else {
						$this->fireStartElementEvent($tagText, $myAttributes);
					}
				}
		}
	} //parseTag
	
	/**
	* Returns the current error code (non-functional for SAXY Lite)
	* @return int The current error code
	*/
	function xml_get_error_code() {
		return -1;
	} //xml_get_error_code
	
	/**
	* Returns a textual description of the error code (non-functional for SAXY Lite)
	* @param int The error code
	* @return string The error message
	*/
	function xml_error_string($code) {
		return "";
	} //xml_error_string
} //SAXY_Lite_Parser





/****************************************************************************************
** Library Web Service
****************************************************************************************/
$order = 0;
function xs_debug_to_file($msg) {
	$filename = XS_LOG_FILE;
	$parts = getdate();
	$year = $parts["year"];
	if($parts["mon"] < 10) {
		$month = "0" . $parts["mon"];
	} else {
		$month = $parts["mon"];
	}
	if($parts["mday"] < 10) {
		$day = "0" . $parts["mday"];
	} else {
		$day = $parts["mday"];
	}

	$filename = str_replace("%Y", $year, $filename);
	$filename = str_replace("%m", $month, $filename);
	$filename = str_replace("%d", $day, $filename);

	if (file_exists($filename)) {
		if (is_writable($filename)) {
			if (!$handle = fopen($filename, 'a')) {
				return false;
			}
		} else {
			return false;
		}
	} else {
		if (!$handle = fopen($filename, 'w')) {
			return false;
		}
	}

	if (fwrite($handle, date("Y-m-d") . "\t" . date("G-i-s") . "\t" . $msg . chr(13) . chr(10)) === false) {
		return false;
	}

	if (!fclose($handle)) {
		return false;
	}
	return true;
}


function xs_is_valid_relative_path($relative_path) {
	$path = trim($relative_path);
	
	$invalid_chars = array(":", "*", "?", "\"", "<", ">", "|", "..", ".\\", "./", "\\.", "/.");
	foreach ($invalid_chars as $invalid_char) {
		if (strpos($path, $invalid_char) !== false) {
			return false;	
		}
	}

	if (substr($path, 0, 1) == ".")
	{
		return false;
	}

	if (substr($path, 0, 1) == "/")
	{
		return false;
	}

	if (substr($path, 0, 1) == "\\")
	{
		return false;
	}
	
	if (substr($path, strlen($path) - 1, 1) == "/")
	{
		return false;
	}

	if (substr($path, strlen($path) - 1, 1) == "\\")
	{
		return false;
	}

	if (strpos($path, $invalid_char) !== false) {
		return false;	
	}

	return true;
}



function xs_respond_error($msg, $soap12) {
	if ($soap12) {
		header("Content-Type: application/soap+xml");
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">";
			echo "<soap:Body>";
				echo "<soap:Fault>";
					echo "<soap:Code>";
						echo "<soap:Value>soap:Receiver</soap:Value>";
					echo "</soap:Code>";
					echo "<soap:Reason>";
						echo "<soap:Text xml:lang=\"en\">" . xs_xml_escape($msg) . "</soap:Text>";
					echo "</soap:Reason>";
					echo "<soap:Detail></soap:Detail>";
				echo "</soap:Fault>";
			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} else {
		header("Content-Type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">";
			echo "<soap:Body>";
				echo "<soap:Fault>";
					echo "<faultcode>soap:Server</faultcode>";
					echo "<faultstring>" . xs_xml_escape($msg) . "</faultstring>";
					echo "<detail />";
				echo "</soap:Fault>";
			echo "</soap:Body>";
		echo "</soap:Envelope>";
	}
}


function xs_add_container($object_name, $path, $label, $base_url, $empty, $icon, $metadata, $options) {
	$stream = array();
	$GLOBALS["order"] = $GLOBALS["order"] + 1;

	$stream[] = "<libContainer>";
		$stream[] = "<objectName>" . xs_xml_escape($object_name) . "</objectName>";
		$stream[] = "<path>" . xs_xml_escape($path) . "</path>";
		$stream[] = "<label>" . xs_xml_escape($label) . "</label>";
		$stream[] = "<baseURL>" . xs_xml_escape($base_url) . "</baseURL>";
		if ($empty) {
			$stream[] = "<empty>true</empty>";
		}
		if ($icon != "") {
			$stream[] = "<icon>" . xs_xml_escape($icon) . "</icon>";
		}
		if ($metadata != "") {
			$stream[] = "<metadata>" . xs_xml_escape($metadata) . "</metadata>";
		}
		if ($options > 0) {
			$stream[] = "<options>" . $options . "</options>";
		}
		$stream[] = "<order>" . $GLOBALS["order"] . "</order>";
	$stream[] = "</libContainer>";

	return implode("", $stream);
}

function xs_add_object($object_name, $path, $label, $attributes, $properties, $icon, $metadata, $options) {
	$stream = array();
	$GLOBALS["order"] = $GLOBALS["order"] + 1;

	$stream[] = "<libObject>";
		$stream[] = "<objectName>" . xs_xml_escape($object_name) . "</objectName>";
		$stream[] = "<path>" . xs_xml_escape($path) . "</path>";
		$stream[] = "<label>" . xs_xml_escape($label) . "</label>";
		if (is_array($attributes)) {
			$stream[] = "<attrs>";
			foreach($attributes as $attr)
			{
				$stream[] = "<attr>";
					$stream[] = "<name>" . xs_xml_escape($attr["name"]) . "</name>";
					$stream[] = "<value>" . xs_xml_escape($attr["value"]) . "</value>";
				$stream[] = "</attr>";
			}
			$stream[] = "</attrs>";
		}
		if (is_array($properties)) {
			$stream[] = "<props>";
			foreach($properties as $prop)
			{
				$stream[] = "<prop>";
					$stream[] = "<name>" . xs_xml_escape($prop["name"]) . "</name>";
					$stream[] = "<value>" . xs_xml_escape($prop["value"]) . "</value>";
				$stream[] = "</prop>";
			}
			$stream[] = "</props>";
		}
		
		if ($icon != "") {
			$stream[] = "<icon>" . xs_xml_escape($icon) . "</icon>";
		}
		if ($metadata != "") {
			$stream[] = "<metadata>" . xs_xml_escape($metadata) . "</metadata>";
		}
		if ($options > 0) {
			$stream[] = "<options>" . $options . "</options>";
		}
		$stream[] = "<order>" . $GLOBALS["order"] . "</order>";
	$stream[] = "</libObject>";

	return implode("", $stream);
}


class XS_XML_Parser {
	var $xpath = "";
	var $qpath = "";
	var $xpath_stack = array();
	var $qpath_stack = array();
	var $ordinal = array();
	var $request = array();

	function parse($xml) {
		$this->chars = array();
		$sp = new SAXY_Lite_Parser();
		$sp->xml_set_element_handler(array(&$this, "startElement"), array(&$this, "endElement"));

		$sp->xml_set_character_data_handler(array(&$this, "charData"));

		$sp->parse($xml);
		return $this->chars;
	}

	function startElement($parser, $name, $attributes) {
		//Get XPath
		array_push($this->xpath_stack, $name);
		$this->xpath = "/" . implode("/", $this->xpath_stack);

		//Get position
		if (array_key_exists($this->xpath, $this->ordinal)) {
			$this->ordinal[$this->xpath] += 1;
		} else {
			$this->ordinal[$this->xpath] = 1;
		}

		//Get QPath
		array_push($this->qpath_stack, $name . "[" . $this->ordinal[$this->xpath] . "]");
		$this->qpath = "/" . implode("/", $this->qpath_stack);
		
		//Create a key for this path
		if (!array_key_exists($this->qpath, $this->chars)) {
			$this->chars[$this->qpath] = "";
		}
	}

	function endElement($parser, $name) {
		//Reset position
		$length = strlen($this->xpath);
		foreach ($this->ordinal as $key => $item) {
			if (substr($key, $length, 1) == "/" and substr($key, 0, $length) == $this->xpath and strlen($key) > $length) {
				$this->ordinal[$key] = 0;
			}
		}

		//Get XPath
		array_pop($this->xpath_stack);
		$this->xpath = "/" . implode("/", $this->xpath_stack);

		//Get QPath
		array_pop($this->qpath_stack);
		$this->qpath = "/" . implode("/", $this->qpath_stack);
	}

	function charData($parser, $text) {
		if (array_key_exists($this->qpath, $this->chars)) {
			$this->chars[$this->qpath] .= str_replace("\t", "", "$text");
		} else {
			$this->chars[$this->qpath] = str_replace("\t", "", "$text");
		}
	}
}

function xs_build_path($path, $name) {
	$p = str_replace("\\", "/", trim($path));
	$n = trim($name);
	
	if (strlen($p) > 0 and strlen($n) > 0) {
		if (substr($p, strlen($p) - 1, 1) == "/") {
			return $p . $n;
		} else {
			return $p . "/" . $n;
		}
	} else {
		return $p . $n;
	}
}


function xs_xml_escape($text) {
	return str_replace(array("&", "<", ">", "\'", "\""), array("&amp;", "&lt;", "&gt;", "&apos;", "&quot;"), $text);
}

function xs_urlencode($text) {
	$text = urlencode($text);
	$text = str_replace("+", "%20", $text);
	return $text;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Read SOAP message
	$soap = utf8_decode(@file_get_contents("php://input"));
	$soap12 = false;
	$soap_namespace_uri = "http://schemas.xmlsoap.org/soap/envelope/";
	if (!strpos($soap, "http://www.w3.org/2003/05/soap-envelope") === false) {
		$soap12 = true;
		$soap_namespace_uri = "http://www.w3.org/2003/05/soap-envelope";
	}
	
	
	//Check authorization code
	if (XS_AUTHORIZATION_CODE != "") {
		if (!isset($_SERVER["HTTP_X_LICENSE_ID"])) {
			xs_debug_to_file("No authorization code set.");
			xs_respond_error("No authorization code set. Please contact your System Administrator.", $soap12);
			exit(0);
		} else {
			if ($_SERVER["HTTP_X_LICENSE_ID"] != XS_AUTHORIZATION_CODE) {
				xs_debug_to_file("Invalid authorization code: " . $_SERVER["HTTP_X_LICENSE_ID"]);
				xs_respond_error("Invalid authorization code. Please contact your System Administrator.", $soap12);
				exit(0);
			}
		}
	}
	
	
	// Parse SOAP message
	$xml_parser = new XS_XML_Parser();
	$request = $xml_parser->parse($soap);
	
	// Determine what to do
	if (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryDescribe[1]", $request)) {
		// Description of the service

		// Get data from SOAP message
		$lang = $request["/soap:Envelope[1]/soap:Body[1]/doLibraryDescribe[1]/lang[1]"];

		$search_filters = array();
		
		
		/*************************** ADD CUSTOM CODE HERE **************************/
		array_push($search_filters, array("id" => "", "label" => "(no filter)", "icon" => ""));
		array_push($search_filters, array("id" => "annual-reports", "label" => "Annual Reports", "icon" => ""));
		array_push($search_filters, array("id" => "product-info", "label" => "Product Info", "icon" => ""));
		array_push($search_filters, array("id" => "qa-reports", "label" => "Quality Assurance Reports", "icon" => ""));
		array_push($search_filters, array("id" => "sales-reports", "label" => "Sales Reports", "icon" => ""));
		/*************************** ADD CUSTOM CODE HERE **************************/
		
		
		// Respond
		if ($soap12) {
			header("Content-Type: application/soap+xml");
		} else {
			header("Content-Type: text/xml");
		}
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"" . $soap_namespace_uri . "\">";
			echo "<soap:Body>";
    				echo "<doLibraryDescribeResponse xmlns=\"http://xstandard.com/2004/web-services\">";
      					echo "<doLibraryDescribeResult>";
						echo "<library>";
							echo "<name>" . xs_xml_escape(XS_NAME) . "</name>";
							echo "<description></description>";
							echo "<baseURL></baseURL>";
						echo "</library>";
						echo "<libraryBrowse>";
							echo "<enabled>";
							if(XS_BROWSE_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</enabled>";
						echo "</libraryBrowse>";
						echo "<librarySearch>";
							echo "<enabled>";
							if(XS_SEARCH_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</enabled>";
						echo "</librarySearch>";
						echo "<libraryUpload>";
							echo "<uploadToRootContainerEnabled>false</uploadToRootContainerEnabled>";
							echo "<uploadToSubContainerEnabled>false</uploadToSubContainerEnabled>";
							echo "<uploadContainerMaxSize>0</uploadContainerMaxSize>";
							echo "<uploadObjectMaxSize>0</uploadObjectMaxSize>";
						echo "</libraryUpload>";
						echo "<libraryDownload>";
							echo "<downloadContainerEnabled>false</downloadContainerEnabled>";
							echo "<downloadObjectEnabled>false</downloadObjectEnabled>";
							echo "<downloadContainerMaxSize>0</downloadContainerMaxSize>";
							echo "<downloadObjectMaxSize>0</downloadObjectMaxSize>";
						echo "</libraryDownload>";
						echo "<libraryDelete>";
							echo "<deleteConfirmationEnabled>true</deleteConfirmationEnabled>";
							echo "<deleteContainerEnabled>false</deleteContainerEnabled>";
							echo "<deleteObjectEnabled>false</deleteObjectEnabled>";
						echo "</libraryDelete>";
						echo "<libraryRename>";
							echo "<renameContainerEnabled>false</renameContainerEnabled>";
							echo "<renameObjectEnabled>false</renameObjectEnabled>";
						echo "</libraryRename>";
						echo "<libraryCreate>";
							echo "<createObjectTypes>";
							echo "</createObjectTypes>";
						echo "</libraryCreate>";
						echo "<librarySearchFilters>";
						foreach ($search_filters as $key => $item) {
							echo "<librarySearchFilter>";
								echo "<id>" . xs_xml_escape($item["id"]) . "</id>";
								echo "<label>" . xs_xml_escape($item["label"]) . "</label>";
								echo "<icon>" . xs_xml_escape($item["icon"]) . "</icon>";
							echo "</librarySearchFilter>";
						}
						echo "</librarySearchFilters>";
						echo "<acceptedObjectTypes>";
						echo "</acceptedObjectTypes>";
      					echo "</doLibraryDescribeResult>";
				echo "</doLibraryDescribeResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]", $request)) {
		// Browse

		// Get data from SOAP message
		$lang = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]/lang[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]/path[1]"]);


		$containers = array();
		$objects = array();
		
		$containers_results = array();
		$objects_results = array();

		/*
		** -------------------------------------------------------
		** BROWSE THE LIBRARY
		** (Simulated browse results below)
		** -------------------------------------------------------
		*/
		/*************************** ADD CUSTOM CODE HERE **************************/
		if ($path == "pdf") {
			array_push($objects_results, array("id" => "1000", "url" => "cms.aspx?id=1000", "label" => "2002 Annual Report"));
			array_push($objects_results, array("id" => "1001", "url" => "cms.aspx?id=1001", "label" => "2003 Annual Report"));
			array_push($objects_results, array("id" => "1002", "url" => "cms.aspx?id=1002", "label" => "2004 Annual Report"));
			array_push($objects_results, array("id" => "1003", "url" => "cms.aspx?id=1003", "label" => "RJ 700 cell phone"));
			array_push($objects_results, array("id" => "1004", "url" => "cms.aspx?id=1004", "label" => "Wide-aspect 24 inch LCD display"));
			array_push($objects_results, array("id" => "1005", "url" => "cms.aspx?id=1005", "label" => "5GB MP3 player"));
			array_push($objects_results, array("id" => "1006", "url" => "cms.aspx?id=1006", "label" => "Dual processor 3 GHz motherboard"));
			array_push($objects_results, array("id" => "1007", "url" => "cms.aspx?id=1007", "label" => "1GB memory key"));
		} elseif ($path == "word") {
			array_push($objects_results, array("id" => "1008", "url" => "cms.aspx?id=1008", "label" => "QA Report - 1 meter drop test"));
			array_push($objects_results, array("id" => "1009", "url" => "cms.aspx?id=1009", "label" => "QA Report - noise level test"));
			array_push($objects_results, array("id" => "1010", "url" => "cms.aspx?id=1010", "label" => "Q1 2005 Region: North America"));
			array_push($objects_results, array("id" => "1011", "url" => "cms.aspx?id=1011", "label" => "Q1 2005 Region: South America"));
			array_push($objects_results, array("id" => "1012", "url" => "cms.aspx?id=1012", "label" => "Q1 2005 Region: Europe"));
			array_push($objects_results, array("id" => "1013", "url" => "cms.aspx?id=1013", "label" => "Q1 2005 Region: Asia"));
			array_push($objects_results, array("id" => "1014", "url" => "cms.aspx?id=1014", "label" => "Q1 2005 Region: Africa"));
			array_push($objects_results, array("id" => "1015", "url" => "cms.aspx?id=1015", "label" => "Q1 2005 Region: Australia"));
		} elseif ($path == "") {
			array_push($containers_results, array("id" => "word", "label" => "Word Documents"));
			array_push($containers_results, array("id" => "pdf", "label" => "PDF Documents"));
		}
		/*************************** ADD CUSTOM CODE HERE **************************/


		foreach ($containers_results as $key) {
			$containers[] = xs_add_container($key["id"], "", $key["label"], "", false, "", "", 0);
		}

		foreach ($objects_results as $key) {
			$attributes = array();
			$properties = array();
			array_push($attributes, array("name" => "href", "value" => $key["url"]));
			$objects[] = xs_add_object($key["id"], "", $key["label"], $attributes, $properties, "document", "", 0);
		}
		

		// Respond
		if ($soap12) {
			header("Content-Type: application/soap+xml");
		} else {
			header("Content-Type: text/xml");
		}
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"" . $soap_namespace_uri . "\">";
			echo "<soap:Body>";
				echo "<doLibraryBrowseResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryBrowseResult>";
						echo "<libContainers>";
						foreach ($containers as $key) {
							echo $key;
						}
						echo "</libContainers>";
						echo "<libObjects>";
						foreach ($objects as $key) {
							echo $key;
						}
						echo "</libObjects>";
					echo "</doLibraryBrowseResult>";
				echo "</doLibraryBrowseResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
		$GLOBALS["order"] = 0;
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibrarySearch[1]", $request)) {
		// Search

		// Get data from SOAP message
		$lang = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibrarySearch[1]/lang[1]"]);
		$search_for = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibrarySearch[1]/searchFor[1]"]);
		$filter_by = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibrarySearch[1]/filterBy[1]"]);

		$objects = array();
		
		$objects_results = array();
								
		/*
		** -------------------------------------------------------
		** SEARCH THE LIBRARY
		** (Simulated search results below)
		** -------------------------------------------------------
		*/
		/*************************** ADD CUSTOM CODE HERE **************************/
		if ($filter_by == "annual-reports") {
			array_push($objects_results, array("id" => "1000", "url" => "cms.aspx?id=1000", "label" => "2002 Annual Report"));
			array_push($objects_results, array("id" => "1001", "url" => "cms.aspx?id=1001", "label" => "2003 Annual Report"));
			array_push($objects_results, array("id" => "1002", "url" => "cms.aspx?id=1002", "label" => "2004 Annual Report"));
		} elseif ($filter_by == "product-info") {
			array_push($objects_results, array("id" => "1003", "url" => "cms.aspx?id=1003", "label" => "RJ 700 cell phone"));
			array_push($objects_results, array("id" => "1004", "url" => "cms.aspx?id=1004", "label" => "Wide-aspect 24 inch LCD display"));
			array_push($objects_results, array("id" => "1005", "url" => "cms.aspx?id=1005", "label" => "5GB MP3 player"));
			array_push($objects_results, array("id" => "1006", "url" => "cms.aspx?id=1006", "label" => "Dual processor 3 GHz motherboard"));
			array_push($objects_results, array("id" => "1007", "url" => "cms.aspx?id=1007", "label" => "1GB memory key"));
		} elseif ($filter_by == "qa-reports") {
			array_push($objects_results, array("id" => "1008", "url" => "cms.aspx?id=1008", "label" => "QA Report - 1 meter drop test"));
			array_push($objects_results, array("id" => "1009", "url" => "cms.aspx?id=1009", "label" => "QA Report - noise level test"));
			
		} elseif ($filter_by == "sales-reports") {
			array_push($objects_results, array("id" => "1010", "url" => "cms.aspx?id=1010", "label" => "Q1 2005 Region: North America"));
			array_push($objects_results, array("id" => "1011", "url" => "cms.aspx?id=1011", "label" => "Q1 2005 Region: South America"));
			array_push($objects_results, array("id" => "1012", "url" => "cms.aspx?id=1012", "label" => "Q1 2005 Region: Europe"));
			array_push($objects_results, array("id" => "1013", "url" => "cms.aspx?id=1013", "label" => "Q1 2005 Region: Asia"));
			array_push($objects_results, array("id" => "1014", "url" => "cms.aspx?id=1014", "label" => "Q1 2005 Region: Africa"));
			array_push($objects_results, array("id" => "1015", "url" => "cms.aspx?id=1015", "label" => "Q1 2005 Region: Australia"));
		} else {
			array_push($objects_results, array("id" => "1000", "url" => "cms.aspx?id=1000", "label" => "2002 Annual Report"));
			array_push($objects_results, array("id" => "1001", "url" => "cms.aspx?id=1001", "label" => "2003 Annual Report"));
			array_push($objects_results, array("id" => "1002", "url" => "cms.aspx?id=1002", "label" => "2004 Annual Report"));
			array_push($objects_results, array("id" => "1003", "url" => "cms.aspx?id=1003", "label" => "RJ 700 cell phone"));
			array_push($objects_results, array("id" => "1004", "url" => "cms.aspx?id=1004", "label" => "Wide-aspect 24 inch LCD display"));
			array_push($objects_results, array("id" => "1005", "url" => "cms.aspx?id=1005", "label" => "5GB MP3 player"));
			array_push($objects_results, array("id" => "1006", "url" => "cms.aspx?id=1006", "label" => "Dual processor 3 GHz motherboard"));
			array_push($objects_results, array("id" => "1007", "url" => "cms.aspx?id=1007", "label" => "1GB memory key"));
			array_push($objects_results, array("id" => "1008", "url" => "cms.aspx?id=1008", "label" => "QA Report - 1 meter drop test"));
			array_push($objects_results, array("id" => "1009", "url" => "cms.aspx?id=1009", "label" => "QA Report - noise level test"));
			array_push($objects_results, array("id" => "1010", "url" => "cms.aspx?id=1010", "label" => "Q1 2005 Region: North America"));
			array_push($objects_results, array("id" => "1011", "url" => "cms.aspx?id=1011", "label" => "Q1 2005 Region: South America"));
			array_push($objects_results, array("id" => "1012", "url" => "cms.aspx?id=1012", "label" => "Q1 2005 Region: Europe"));
			array_push($objects_results, array("id" => "1013", "url" => "cms.aspx?id=1013", "label" => "Q1 2005 Region: Asia"));
			array_push($objects_results, array("id" => "1014", "url" => "cms.aspx?id=1014", "label" => "Q1 2005 Region: Africa"));
			array_push($objects_results, array("id" => "1015", "url" => "cms.aspx?id=1015", "label" => "Q1 2005 Region: Australia"));
		}
		/*************************** ADD CUSTOM CODE HERE **************************/


		foreach ($objects_results as $key) {
			$attributes = array();
			$properties = array();
			array_push($attributes, array("name" => "href", "value" => $key["url"]));
			$objects[] = xs_add_object($key["id"], "", $key["label"], $attributes, $properties, "document", "", 0);
		}

		// Respond
		if ($soap12) {
			header("Content-Type: application/soap+xml");
		} else {
			header("Content-Type: text/xml");
		}
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"" . $soap_namespace_uri . "\">";
			echo "<soap:Body>";
				echo "<doLibrarySearchResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibrarySearchResult>";
						echo "<libObjects>";
						foreach ($objects as $key) {
							echo $key;
						}
						echo "</libObjects>";
					echo "</doLibrarySearchResult>";
				echo "</doLibrarySearchResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
		$GLOBALS["order"] = 0;
	} else {
		xs_debug_to_file("Invalid SOAP message: " . $soap);
		xs_respond_error("Invalid SOAP message.", $soap12);
		exit(0);
	}
} else {
	// Check PHP version
	if (version_compare(phpversion(), "4.3.0", "<")) {
		echo "Status: Error - This service requires PHP version 4.3.0+ and you you are using PHP version " . phpversion();
		exit(0);
	}

	echo "Status: Ready";
}
?> 
