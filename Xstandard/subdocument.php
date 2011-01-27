<?php
/*************************************** IMPORTANT **************************************
** - If you are having problems getting this or other Web Services to work, the most likely
** - cause is security settings.  This Web Service runs in the security context of the Web
** - server - other words, it does not have many rights.  Make sure file permissions on this
** - PHP file is set to Everyone.
**
** - To customize this service, search for "ADD CUSTOM CODE HERE" below.
*************************************** IMPORTANT **************************************/



/*************************** OPTIONAL - CHANGE THESE SETTINGS **************************/
define("XS_LOG_FILE", "%Y-%m-%d.log"); // Log file for errors or debug information. Can be a template like "%Y-%m-%d.log" where %Y is 4 digit year, %m is 2 digit month and %d is 2 digit day.  This will produce a log file like "2003-12-29.log".
define("XS_AUTHORIZATION_CODE", ""); //An authorization code used to restrict access to this Web Service. You get this code from your account on the xstandard.com Web site.
/*************************** OPTIONAL - CHANGE THESE SETTINGS ***************************/




/****************************************************************************************
** - Purpose:	Subdocument Service
**
** - Input:	SOAP
** - Output:	SOAP
**
** - Version:	2.0
** - Date:	2007-04-12
**
** - Note:	This script requires the following:
**			- PHP 4.3.0+
**
** - Copyright (c) 2006 Belus Technology Inc and its licensors. All rights reserved.
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
** Subdocument Web Service
****************************************************************************************/
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


function xs_add_subdocument_definition($elementName, $idAttribute, $option01Attribute, $option02Attribute, $option03Attribute, $option04Attribute, $option05Attribute, $option06Attribute, $option07Attribute, $option08Attribute, $option09Attribute, $option10Attribute) {
	$stream = array();

	$stream[] = "<subdocumentDefinition>";
		$stream[] = "<elementName>" . xs_xml_escape($elementName) . "</elementName>";
		$stream[] = "<idAttribute>" . xs_xml_escape($idAttribute) . "</idAttribute>";
		$stream[] = "<option01Attribute>" . xs_xml_escape($option01Attribute) . "</option01Attribute>";
		$stream[] = "<option02Attribute>" . xs_xml_escape($option02Attribute) . "</option02Attribute>";
		$stream[] = "<option03Attribute>" . xs_xml_escape($option03Attribute) . "</option03Attribute>";
		$stream[] = "<option04Attribute>" . xs_xml_escape($option04Attribute) . "</option04Attribute>";
		$stream[] = "<option05Attribute>" . xs_xml_escape($option05Attribute) . "</option05Attribute>";
		$stream[] = "<option06Attribute>" . xs_xml_escape($option06Attribute) . "</option06Attribute>";
		$stream[] = "<option07Attribute>" . xs_xml_escape($option07Attribute) . "</option07Attribute>";
		$stream[] = "<option08Attribute>" . xs_xml_escape($option08Attribute) . "</option08Attribute>";
		$stream[] = "<option09Attribute>" . xs_xml_escape($option09Attribute) . "</option09Attribute>";
		$stream[] = "<option10Attribute>" . xs_xml_escape($option10Attribute) . "</option10Attribute>";
	$stream[] = "</subdocumentDefinition>";

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


function read_from_file($path) {
	return @file_get_contents($path);
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
	if (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSubdocumentDescribe[1]", $request)) {
		// Description of the service

		$subdocument_definitions = array();


		// Get data from SOAP message
		$lang = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDescribe[1]/lang[1]"]; //interface language code


		/*************************** ADD CUSTOM CODE HERE **************************/
		/* Here is where you specify which custom elements XStandard will treat
		** as subdocuments. Here is the API of the function to call:
		**
		** xs_add_subdocument_definition($elementName, $idAttribute, $option01Attribute, $option02Attribute, $option03Attribute, $option04Attribute, $option05Attribute, $option06Attribute, $option07Attribute, $option08Attribute, $option09Attribute, $option10Attribute)
		**
		** $elementName is the name of the element.
		**
		** $idAttribute is the attribute that holds the ID of the subdocument.
		**
		** $option01Attribute to $option10Attribute are attributes that hold
		** customization settings you may need to use to determine the markup
		** you need to create for this subdocument.
		**
		*/

		$subdocument_definitions[] = xs_add_subdocument_definition("include", "doc", "", "", "", "", "", "", "", "", "", "");

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
    			echo "<doSubdocumentDescribeResponse xmlns=\"http://xstandard.com/2004/web-services\">";
      				echo "<doSubdocumentDescribeResult>";
      					echo "<subdocumentDefinitions>";
      						echo implode("", $subdocument_definitions);
						echo "</subdocumentDefinitions>";
      				echo "</doSubdocumentDescribeResult>";
				echo "</doSubdocumentDescribeResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]", $request)) {
		// Return subdocument
		
		$subdocument = "";
		$subdocumentFound = false;

		
		// Get data from SOAP message
		$lang = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/lang[1]"]; //interface language code
		$type = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/type[1]"]; //custom element name
		$id = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/id[1]"]; //subdocument id
		$option01 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option01[1]"]; //additional info
		$option02 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option02[1]"]; //additional info
		$option03 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option03[1]"]; //additional info
		$option04 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option04[1]"]; //additional info
		$option05 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option05[1]"]; //additional info
		$option06 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option06[1]"]; //additional info
		$option07 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option07[1]"]; //additional info
		$option08 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option08[1]"]; //additional info
		$option09 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option09[1]"]; //additional info
		$option10 = $request["/soap:Envelope[1]/soap:Body[1]/doSubdocumentDownload[1]/option10[1]"]; //additional info


		/*************************** ADD CUSTOM CODE HERE **************************/
		/* Here is where you return the markup for a given subdocument.
		** Set the XHTML markup for the subdocument into the variable $subdocument and
		** set $subdocumentFound to true.
		**
		*/
		
		if ($id == "A") {
			$subdocument = read_from_file("subdocument-example-A.txt");
			$subdocumentFound = true;
		} elseif ($id == "B") {
			$subdocument = read_from_file("subdocument-example-B.txt");
			$subdocumentFound = true;
		} elseif ($id == "C") {
			$subdocument = read_from_file("subdocument-example-C.txt");
			$subdocumentFound = true;
		} elseif ($id == "D") {
			$subdocument = read_from_file("subdocument-example-D.txt");
			$subdocumentFound = true;
		} elseif ($id == "E") {
			$subdocument = read_from_file("subdocument-example-E.txt");
			$subdocumentFound = true;
		}
		
		/*************************** ADD CUSTOM CODE HERE **************************/


		// Respond
		if ($subdocumentFound) {
			if ($soap12) {
				header("Content-Type: application/soap+xml");
			} else {
				header("Content-Type: text/xml");
			}
			echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			echo "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"" . $soap_namespace_uri . "\">";
				echo "<soap:Body>";
    				echo "<doSubdocumentDownloadResponse xmlns=\"http://xstandard.com/2004/web-services\">";
      					echo "<doSubdocumentDownloadResult>";
      						echo "<subdocument>";
								echo xs_xml_escape($subdocument);
							echo "</subdocument>";
      					echo "</doSubdocumentDownloadResult>";
					echo "</doSubdocumentDownloadResponse>";
  				echo "</soap:Body>";
			echo "</soap:Envelope>";
		} else {
			xs_respond_error("Subdocument not found. Please contact your System Administrator.", $soap12);
			exit(0);
		}
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
