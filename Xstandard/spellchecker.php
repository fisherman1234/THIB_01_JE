<?php
/*************************************** IMPORTANT **************************************
** - If you are having problems getting this or other Web Services to work, the most likely
** - cause is security settings.  This Web Service runs in the security context of the Web
** - server - other words, it does not have many rights.  Make sure file permissions on this
** - PHP file, custom dictionary files and config file is set to Everyone.
** -
** - This Web Service can only spell check text using ISO-8859-1 character set.
*************************************** IMPORTANT **************************************/


/*************************** OPTIONAL - CHANGE THESE SETTINGS **************************/
if (array_key_exists("HTTP_X_SPELL_CHECKER_LANG", $_SERVER)) {
	if (strlen($_SERVER["HTTP_X_SPELL_CHECKER_LANG"]) > 0) {
		define("XS_CUSTOM_DICTIONARY", "custom." . str_replace("_", "-", $_SERVER["HTTP_X_SPELL_CHECKER_LANG"]) . ".txt"); // Path to custom dictionary file.
	} else {
		define("XS_CUSTOM_DICTIONARY", "custom.txt"); // Path to custom dictionary file.
	}
} else {
	define("XS_CUSTOM_DICTIONARY", "custom.txt"); // Path to custom dictionary file.
}
define("XS_CONFIG_FILE", "spellchecker.config"); // Path to config file.
define("XS_LOG_FILE", "%Y-%m-%d.log"); // Log file for errors or debug information. Can be a template like "%Y-%m-%d.log" where %Y is 4 digit year, %m is 2 digit month and %d is 2 digit day.  This will produce a log file like "2003-12-29.log".
define("XS_SPELLCHECKER_NAME", "Default Spell Checker");
define("XS_ADD_TO_CUSTOM_DICTIONARY_ENABLED", true);
define("XS_AUTHORIZATION_CODE", ""); //An authorization code used to restrict access to this Web Service. You get this code from your account on the xstandard.com Web site.
/*************************** OPTIONAL - CHANGE THESE SETTINGS ***************************/














/****************************************************************************************
** - Purpose:	SpellChecker Service
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
** SpellChecker Web Service
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


class XS_XML_Parser {
	var $xpath = "";
	var $qpath = "";
	var $xpath_stack = array();
	var $qpath_stack = array();
	var $ordinal = array();
	var $chars = array();

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
class XS_XML_Filter {
	var $lang_filter = "";
	var $lang_stack = array();
	var $filter_stack = array();
	var $stream = array();


	function parse($xml, $lang = "") {
		$this->stream = array();
		$this->lang_filter = $lang;
		$sp = new SAXY_Lite_Parser();
		$sp->xml_set_element_handler(array(&$this, "startElement"), array(&$this, "endElement"));
		$sp->xml_set_character_data_handler(array(&$this, "charData"));
		$sp->parse($xml);
		return implode("", $this->stream);
	}

	function startElement($parser, $name, $attributes) {
		$lang = "";
		$filter = false;

		// Get the language of the element
		if (array_key_exists("xml:lang", $attributes)) {
			$lang = $attributes["xml:lang"];
		}
		if (strlen($lang) == 0) {
			if (count($this->lang_stack) > 0) {
				$lang = $this->lang_stack[count($this->lang_stack) - 1];
			}
		}
		array_push($this->lang_stack, $lang); 

		// Get filter status
		if (count($this->filter_stack) > 0) {
			$filter = $this->filter_stack[count($this->filter_stack) - 1];
		}
		
		if ($filter === true) {
		    array_push($this->filter_stack, true);
		} else {
			if (strlen($lang) == 0 or strlen($this->lang_filter) == 0) {
				array_push($this->filter_stack, false);
			} else {
				if ($this->lang_filter == $lang) {
					array_push($this->filter_stack, false);
				} else {
					array_push($this->filter_stack, true);
				}
			}
		}
		
		if ($this->filter_stack[count($this->filter_stack) - 1] === false) {
			$this->stream[] = "<" . $name;
			foreach ($attributes as $key => $item) {
				$this->stream[] = " " . $key . "=\"" . xs_xml_escape($item) . "\"";
			}
			$this->stream[] = ">";
		}
	}
		
	function endElement($parser, $name) {
		// Get lang
		$lang = array_pop($this->lang_stack);
		
		// Write closing tag
		if (array_pop($this->filter_stack) === false) {
			$this->stream[] = "</" . $name . ">";
		}		
	}
		
	function charData($parser, $text) {
		// Write closing tag
		if ($this->filter_stack[count($this->filter_stack) - 1] === false) {
			$this->stream[] = xs_xml_escape($text);
		}
	}
}


class XS_Spell_Config_File {
	var $xpath = "";
	var $xpath_stack = array();
	var $dictionaries = array();
	var $dictionary = array("name" => "", "code" => "", "jargon" => "", "size" => "", "stopCheckWordMin" => "15", "code" => "stopCheckPercentErrors", "available" => "no");

	function parse($xml) {
		$sp = new SAXY_Lite_Parser();
		$sp->xml_set_element_handler(array(&$this, "startElement"), array(&$this, "endElement"));
		$sp->xml_set_character_data_handler(array(&$this, "charData"));
		$sp->parse($xml);
		return $this->dictionaries;
	}

	function startElement($parser, $name, $attributes) {
		//Get XPath
		array_push($this->xpath_stack, $name);
		$this->xpath = "/" . implode("/", $this->xpath_stack);

		if ($this->xpath == "/spellChecker/dictionary") {
			$this->dictionary["name"] = "";
			$this->dictionary["code"] = "";
			$this->dictionary["jargon"] = "";
			$this->dictionary["size"] = "";
			$this->dictionary["stopCheckWordMin"] = "15";
			$this->dictionary["stopCheckPercentErrors"] = "50";
			$this->dictionary["available"] = "no";
		}
	}

	function endElement($parser, $name) {
		// Get the data
		if ($this->xpath == "/spellChecker/dictionary") {
			if ($this->dictionary["available"] == "yes") {
				array_push($this->dictionaries, $this->dictionary);
			}
		}

		//Get XPath
		array_pop($this->xpath_stack);
		$this->xpath = "/" . implode("/", $this->xpath_stack);
	}
		
	function charData($parser, $text) {
		$text = trim($text);
		
		switch ($this->xpath) {
		case "/spellChecker/dictionary/name":
			$this->dictionary["name"] = $text;
			break;
		case "/spellChecker/dictionary/code":
			$this->dictionary["code"] = $text;
			break;
		case "/spellChecker/dictionary/jargon":
			$this->dictionary["jargon"] = $text;
			break;
		case "/spellChecker/dictionary/size":
			$this->dictionary["size"] = $text;
			break;
		case "/spellChecker/dictionary/stopCheckWordMin":
			$this->dictionary["stopCheckWordMin"] = $text;
			break;
		case "/spellChecker/dictionary/stopCheckPercentErrors":
			$this->dictionary["stopCheckPercentErrors"] = $text;
			break;
		case "/spellChecker/dictionary/available":
			if ($text == "yes") {
				$this->dictionary["available"] = "yes";
			} else {
				$this->dictionary["available"] = "no";
			}
			break;
		}
	}
}


function xs_append_to_file($filename, $data) {
	if (($h = @fopen($filename, 'a')) === false) {
		return false;
	}
	if (($bytes = @fwrite($h, $data)) === false) {
		return false;
	}
	fclose($h);
	return $bytes;
}

function xs_xml_escape($text) {
	return str_replace(array("&", "<", ">", "\'", "\""), array("&amp;", "&lt;", "&gt;", "&apos;", "&quot;"), $text);
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


	$xml_parser = new XS_XML_Parser();
	$xml_filter = new XS_XML_Filter();
	$xml_config = new XS_Spell_Config_File();
	$config = array();
	$request = $xml_parser->parse($soap);
	$lang = "en";
	
	// Determine what to do
	if (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSpellCheckerDescribe[1]", $request)) {
		// Description of the service
		
		// Get lang
		if (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSpellCheckerDescribe[1]/lang[1]", $request)) {
			if (strlen($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerDescribe[1]/lang[1]"]) > 0) {
				$lang = $request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerDescribe[1]/lang[1]"];
			}
		}
		
		// Read/parse config file
		if (file_exists(XS_CONFIG_FILE)) {
			if (!is_readable(XS_CONFIG_FILE)) {
				xs_debug_to_file("File permission incorrectly set on config file: " . XS_CONFIG_FILE);
				xs_respond_error("File permission incorrectly set on config file: " . XS_CONFIG_FILE, $soap12);
				exit(0);
			}
			$config = $xml_config->parse($xml_filter->parse(@file_get_contents(XS_CONFIG_FILE), $lang));
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
    				echo "<doSpellCheckerDescribeResponse xmlns=\"http://xstandard.com/2004/web-services\">";
      					echo "<doSpellCheckerDescribeResult>";
						echo "<spellChecker>";
							echo "<name>" . xs_xml_escape(XS_SPELLCHECKER_NAME) . "</name>";
							echo "<description></description>";
						echo "</spellChecker>";
        					echo "<spellCheckerAddToCustomDictionary>";
							echo "<enabled>";
							if(XS_ADD_TO_CUSTOM_DICTIONARY_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</enabled>";
        					echo "</spellCheckerAddToCustomDictionary>";
						echo "<availableDictionaries>";
						foreach ($config as $key => $item) {
							echo "<availableDictionary>";
								echo "<code>" . $item["code"] . "</code>";
								echo "<name>" . xs_xml_escape($item["name"]) . "</name>";
								echo "<stopCheckWordMin>" . $item["stopCheckWordMin"] . "</stopCheckWordMin>";
								echo "<stopCheckPercentErrors>" . $item["stopCheckPercentErrors"] . "</stopCheckPercentErrors>";
							echo "</availableDictionary>";
						}
						echo "</availableDictionaries>";
      					echo "</doSpellCheckerDescribeResult>";
				echo "</doSpellCheckerDescribeResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSpellCheckerCheckSpelling[1]", $request)) {
		// Spell check

		// Get data to spell check
		$code = trim($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerCheckSpelling[1]/code[1]"]);
		$stop_check_word_min = intval(trim($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerCheckSpelling[1]/stopCheckWordMin[1]"]));
		$stop_check_percent_errors = intval(trim($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerCheckSpelling[1]/stopCheckPercentErrors[1]"]));
		$content = $request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerCheckSpelling[1]/text[1]"];
		$jargon = "";
		$found = false;
		
		// Load the custom dictionary
		$custom = "[XStandard][XHTML][CSS][Mozilla][Firefox]" . @file_get_contents(XS_CUSTOM_DICTIONARY);
		
		// Read/parse config file
		if (file_exists(XS_CONFIG_FILE)) {
			if (!is_readable(XS_CONFIG_FILE)) {
				xs_debug_to_file("File permission incorrectly set on config file: " . XS_CONFIG_FILE);
				xs_respond_error("File permission incorrectly set on config file: " . XS_CONFIG_FILE, $soap12);
				exit(0);
			}
			$config = $xml_config->parse(@file_get_contents(XS_CONFIG_FILE));
			foreach ($config as $key => $item) {
				if ($item["code"] == $code) {
					$jargon = $item["jargon"];
					$found = true;
					break;
				}
			}
		}
		
		if ($found === false) {
			xs_debug_to_file("The following dictionary is not correctly set in config file: " . $code);
			xs_respond_error("Dictionary is not available: " . $code, $soap12);
			exit(0);
		}


		// Split into words and get the offset of each word
		$words = preg_split("/[\x01-\x26|\x28-\x40|\x5b-\x60|\x7b-\xa9|\xab-\xb4|\xb6-\xb9|\xbb-\xbf|\xd7|\xf7]+?/", $content, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$misspelled = array();
		$suggestion = array();

		// Check each word
		$spelling = "";
		switch ($code) {
			case "en-us":
				$spelling = "american";
				break;
			case "en-gb":
				$spelling = "british";
				break;
			case "en-ca":
				$spelling = "canadian";
				break;
		}

		@pspell_config_create(str_replace("-", "_", $code), $spelling, $jargon);
		$spell = @pspell_new(str_replace("-", "_", $code), $spelling, $jargon, "", PSPELL_FAST);
		
		if ($spell === false) {
			xs_debug_to_file("The following dictionary is not installed: " . $code);
			xs_respond_error("Dictionary is not installed: " . $code, $soap12);
			exit(0);
		}
		
		foreach ($words as $item) {
			$word = $item[0];
			$offset = $item[1];
			while (substr($word, 0, 1) == "'") {
				$word = substr($word, 1);
				$offset = $offset + 1;
			}
			
			while (substr($word, -1, 1) == "'") {
				$word = substr($word, 0, strlen($word) - 1);
			}
			
			if (strlen($word) > 0) {
				if (strpos($custom, "[" . $word . "]") === false) {
					if (!@pspell_check($spell, $word)) {
						array_push($misspelled, array("word" => $word, "offset" => $offset));
					}
				}
			}
		}

		if ($stop_check_word_min > 0 and count($misspelled) >= $stop_check_word_min and intval(count($misspelled) / count($words) * 100) >= $stop_check_percent_errors) {
			// Determine if the right dictionary is used
			if ($soap12) {
				header("Content-Type: application/soap+xml");
			} else {
				header("Content-Type: text/xml");
			}
			echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			echo "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"" . $soap_namespace_uri . "\">";
				echo "<soap:Body>";
					echo "<doSpellCheckerCheckSpellingResponse xmlns=\"http://xstandard.com/2004/web-services\">";
						echo "<doSpellCheckerCheckSpellingResult>";
							echo "<stopCheckLimit>";
								echo "<limitReached>true</limitReached>";
								echo "<message>" . intval(count($misspelled) / count($words) * 100) . "% of the selection has spelling errors. Please select correct dictionary.</message>";
							echo "</stopCheckLimit>";
							echo "<misspellings>";
							echo "</misspellings>";
						echo "</doSpellCheckerCheckSpellingResult>";
					echo "</doSpellCheckerCheckSpellingResponse>";
  				echo "</soap:Body>";
			echo "</soap:Envelope>";
		} else {
			// Get suggestions
			foreach ($misspelled as $key => $value) {
				$suggestion[$value["word"]] = @pspell_suggest($spell, $value["word"]);
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
					echo "<doSpellCheckerCheckSpellingResponse xmlns=\"http://xstandard.com/2004/web-services\">";
						echo "<doSpellCheckerCheckSpellingResult>";
							echo "<stopCheckLimit>";
								echo "<limitReached>false</limitReached>";
								echo "<message></message>";
							echo "</stopCheckLimit>";
							echo "<misspellings>";
							foreach ($misspelled as $key => $value) {
									echo "<misspelling>";
										echo "<word>" . xs_xml_escape($value["word"]) . "</word>";
										echo "<offset>" . $value["offset"] . "</offset>";
										echo "<suggestion>" . xs_xml_escape(implode(",", $suggestion[$value["word"]])) . "</suggestion>";
									echo "</misspelling>";
							}
							echo "</misspellings>";
						echo "</doSpellCheckerCheckSpellingResult>";
					echo "</doSpellCheckerCheckSpellingResponse>";
  				echo "</soap:Body>";
			echo "</soap:Envelope>";
		}
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doSpellCheckerAddToCustomDictionary[1]", $request)) {
		// Add to custom dictionary
		
		if (XS_ADD_TO_CUSTOM_DICTIONARY_ENABLED === false) {
			xs_respond_error("Add to custom dictionary feature is not enabled.", $soap12);
			exit(0);
		}
		
		// Load the custom dictionary
		$custom = @file_get_contents(XS_CUSTOM_DICTIONARY);
		$status = false;
		
		// Get the words
		$code = trim($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerAddToCustomDictionary[1]/code[1]"]);
		$words =  split(",", trim($request["/soap:Envelope[1]/soap:Body[1]/doSpellCheckerAddToCustomDictionary[1]/word[1]"]));
	
		foreach ($words as $key => $item) {
			if (strpos($custom, "[" . $item . "]") === false) {
				if (xs_append_to_file(XS_CUSTOM_DICTIONARY, "[" . $item . "]") === false) {
					xs_debug_to_file("Failed to add word to custom dictionary. Check file permissions.");
					xs_respond_error("Failed to add word to custom dictionary. Check file permissions.", $soap12);
					exit(0);
				} else {
					$status = true;
				}
			}
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
				echo "<doSpellCheckerAddToCustomDictionaryResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doSpellCheckerAddToCustomDictionaryResult>";
					if ($status) {
						echo "true";
					} else {
						echo "false";
					}
					echo "</doSpellCheckerAddToCustomDictionaryResult>";
				echo "</doSpellCheckerAddToCustomDictionaryResponse>";
			echo "</soap:Body>";
		echo "</soap:Envelope>";
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
	
	// Check for pspell package
	if (!extension_loaded("pspell")) {
		echo "Status: Error - pspell extension is not loaded.";
		exit(0);
	}
	
	// Create spell checker for US English
	@pspell_config_create("en_us", "american", "w-accents");
	$spell = @pspell_new("en_us", "american", "w-accents", "", PSPELL_FAST);
	
	if ($spell === false) {
		echo "Status: Error - Cannot create spell checker for lang: en-us.";
		exit(0);
	}

	// Spell check some words
	$words = array("Kwick", "foxx", "jumped", "over", "the", "lasy", "dog");
	$misspelled = array();
	foreach($words as $word) {
		if (!@pspell_check($spell, $word)) {
			$misspelled[] = $word;
		}
	}
	if (count($misspelled) == 0) {
		echo "Status: Error - Cannot spell check words. Dictonary may not be installed correctly.";
		exit(0);
	}

	
	// Check config file
	if (file_exists(XS_CONFIG_FILE)) {
		if (!is_readable(XS_CONFIG_FILE)) {
			echo "Status: Error - Cannot read config file. Check file permissions.";
			exit(0);
		}
	} else {
		echo "Status: Error - Cannot find config file.";
		exit(0);
	}

	echo "Status: Ready";
}
?> 
