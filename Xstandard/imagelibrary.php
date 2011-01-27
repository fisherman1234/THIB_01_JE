<?php
/*************************************** IMPORTANT **************************************
** - If you are having problems getting this or other Web Services to work, the most likely
** - cause is security settings.  This Web Service runs in the security context of the Web
** - server - other words, it does not have many rights.  Make sure file permissions on this
** - PHP file, the destination folder and the temp folder is set to Everyone.
*************************************** IMPORTANT **************************************/




/*************************** OPTIONAL - CHANGE THESE SETTINGS **************************/
define("XS_LIBRARY_FOLDER", "."); // The folder where received files/folders should be saved.
define("XS_LOG_FILE", "%Y-%m-%d.log"); // Log file for errors or debug information. Can be a template like "%Y-%m-%d.log" where %Y is 4 digit year, %m is 2 digit month and %d is 2 digit day.  This will produce a log file like "2003-12-29.log".
define("XS_BASE_URL", "http://" . $_SERVER["SERVER_NAME"] . substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/") + 1)); // Base URL to create for files. Relative URLs are okay, for example: "images/".
define("XS_CONFIG_FILE", "imagelibrary.config"); // Path to config file.
define("XS_ACCEPTED_FILE_TYPES", "gif jpeg jpg png bmp"); // A list of accepted file extensions.
define("XS_UPLOAD_OBJECT_MAX_SIZE", 512000); //Maximum file upload size.
define("XS_UPLOAD_CONTAINER_MAX_SIZE", 5120000); //Maximum folder upload size.
define("XS_DOWNLOAD_OBJECT_MAX_SIZE", 5120000); //Maximum file download size.
define("XS_DOWNLOAD_CONTAINER_MAX_SIZE", 5120000); //Maximum folder download size.
define("XS_GET_DATE_LAST_MODIFIED", true); //Provide the last modified date for files.  For large libraries, turning this off can improve performance.
define("XS_GET_FILE_SIZE", true); //Provide file size. For large libraries, turning this off can improve performance.
define("XS_GET_IMAGE_DIMENSIONS", true); //Provide image dimensions. For large libraries, turning this off can improve performance.
define("XS_DEFAULT_IMAGE_IS_DECORATIVE", false); //Flag to indicate if images should be treated as decorative by default.
define("XS_BROWSE_ENABLED", true); //Enable the ablity to browse this library.
define("XS_SEARCH_ENABLED", false); //Enabled the ability to search this library. You'll need to customize the search feature to meet your CMS needs.
define("XS_UPLOAD_TO_ROOT_CONTAINER_ENABLED", true); //Enable the ability to upload files to root folder.
define("XS_UPLOAD_TO_SUB_CONTAINER_ENABLED", false); //Enable the ability to upload files to a sub folder.
define("XS_UPLOAD_REPLACE_ENABLED", true); //Enable the ability to replace file when uploading.
define("XS_DELETE_OBJECT_ENABLED", false); //Enable the ability to delete files
define("XS_DELETE_CONTAINER_ENABLED", false); //Enable the ability to delete folders
define("XS_DELETE_CONFIRMATION_ENABLED", true); //Require user confirmation before files or folders are deleted
define("XS_RENAME_OBJECT_ENABLED", false); //Enable the ability to rename files
define("XS_RENAME_CONTAINER_ENABLED", false); //Enable the ability to rename folders
define("XS_DOWNLOAD_OBJECT_ENABLED", true); //Enable the ability to download files from server to local computer
define("XS_DOWNLOAD_CONTAINER_ENABLED", true); //Enable the ability to download folders from server to local computer
define("XS_CREATE_CONTAINER_ENABLED", false); //Enable the ability to create folders
define("XS_HIDDEN_FOLDERS", "CVS,_vti_cnf"); //Comma delimited list of hidden folders
define("XS_HIDDEN_FILES", ""); //Comma delimited list of hidden files
define("XS_VALID_NAME_CHARS", ""); //Valid folder/file name characters. Example: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-"
define("XS_AUTHORIZATION_CODE", ""); //An authorization code used to restrict access to this Web Service. You get this code from your account on the xstandard.com Web site.

// Temp folder to stored received packets. Security must be set to Everyone on this folder.
if (file_exists("temp")) {
	define("XS_TEMP_FOLDER", "temp");
} elseif (file_exists("/tmp")) {
	define("XS_TEMP_FOLDER", "/tmp");
} elseif (file_exists("/usr/tmp")) {
	define("XS_TEMP_FOLDER", "/usr/tmp");
} else {
	define("XS_TEMP_FOLDER", getenv("TMP"));
}
/*************************** OPTIONAL - CHANGE THESE SETTINGS ***************************/

















/****************************************************************************************
** - Purpose:	Library Service
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




// --------------------------------------------------------------------------------
// PhpConcept Library - Zip Module 2.5
// --------------------------------------------------------------------------------
// License GNU/LGPL - Vincent Blavet - March 2006
// http://www.phpconcept.net
// --------------------------------------------------------------------------------
//
// Presentation :
//   PclZip is a PHP library that manage ZIP archives.
//   So far tests show that archives generated by PclZip are readable by
//   WinZip application and other tools.
//
// Description :
//   See readme.txt and http://www.phpconcept.net
//
// Warning :
//   This library and the associated files are non commercial, non professional
//   work.
//   It should not have unexpected results. However if any damage is caused by
//   this software the author can not be responsible.
//   The use of this software is at the risk of the user.
//
// --------------------------------------------------------------------------------
// $Id: pclzip.lib.php,v 1.44 2006/03/08 21:23:59 vblavet Exp $
// --------------------------------------------------------------------------------

  // ----- Constants
  define( 'PCLZIP_READ_BLOCK_SIZE', 2048 );
  
  // ----- File list separator
  // In version 1.x of PclZip, the separator for file list is a space
  // (which is not a very smart choice, specifically for windows paths !).
  // A better separator should be a comma (,). This constant gives you the
  // abilty to change that.
  // However notice that changing this value, may have impact on existing
  // scripts, using space separated filenames.
  // Recommanded values for compatibility with older versions :
  //define( 'PCLZIP_SEPARATOR', ' ' );
  // Recommanded values for smart separation of filenames.
  define( 'PCLZIP_SEPARATOR', ',' );

  // ----- Error configuration
  // 0 : PclZip Class integrated error handling
  // 1 : PclError external library error handling. By enabling this
  //     you must ensure that you have included PclError library.
  // [2,...] : reserved for futur use
  define( 'PCLZIP_ERROR_EXTERNAL', 0 );

  // ----- Optional static temporary directory
  //       By default temporary files are generated in the script current
  //       path.
  //       If defined :
  //       - MUST BE terminated by a '/'.
  //       - MUST be a valid, already created directory
  //       Samples :
  // define( 'PCLZIP_TEMPORARY_DIR', '/temp/' );
  // define( 'PCLZIP_TEMPORARY_DIR', 'C:/Temp/' );
  define( 'PCLZIP_TEMPORARY_DIR', '' );

// --------------------------------------------------------------------------------
// ***** UNDER THIS LINE NOTHING NEEDS TO BE MODIFIED *****
// --------------------------------------------------------------------------------

  // ----- Global variables
  $g_pclzip_version = "2.5";

  // ----- Error codes
  //   -1 : Unable to open file in binary write mode
  //   -2 : Unable to open file in binary read mode
  //   -3 : Invalid parameters
  //   -4 : File does not exist
  //   -5 : Filename is too long (max. 255)
  //   -6 : Not a valid zip file
  //   -7 : Invalid extracted file size
  //   -8 : Unable to create directory
  //   -9 : Invalid archive extension
  //  -10 : Invalid archive format
  //  -11 : Unable to delete file (unlink)
  //  -12 : Unable to rename file (rename)
  //  -13 : Invalid header checksum
  //  -14 : Invalid archive size
  define( 'PCLZIP_ERR_USER_ABORTED', 2 );
  define( 'PCLZIP_ERR_NO_ERROR', 0 );
  define( 'PCLZIP_ERR_WRITE_OPEN_FAIL', -1 );
  define( 'PCLZIP_ERR_READ_OPEN_FAIL', -2 );
  define( 'PCLZIP_ERR_INVALID_PARAMETER', -3 );
  define( 'PCLZIP_ERR_MISSING_FILE', -4 );
  define( 'PCLZIP_ERR_FILENAME_TOO_LONG', -5 );
  define( 'PCLZIP_ERR_INVALID_ZIP', -6 );
  define( 'PCLZIP_ERR_BAD_EXTRACTED_FILE', -7 );
  define( 'PCLZIP_ERR_DIR_CREATE_FAIL', -8 );
  define( 'PCLZIP_ERR_BAD_EXTENSION', -9 );
  define( 'PCLZIP_ERR_BAD_FORMAT', -10 );
  define( 'PCLZIP_ERR_DELETE_FILE_FAIL', -11 );
  define( 'PCLZIP_ERR_RENAME_FILE_FAIL', -12 );
  define( 'PCLZIP_ERR_BAD_CHECKSUM', -13 );
  define( 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP', -14 );
  define( 'PCLZIP_ERR_MISSING_OPTION_VALUE', -15 );
  define( 'PCLZIP_ERR_INVALID_OPTION_VALUE', -16 );
  define( 'PCLZIP_ERR_ALREADY_A_DIRECTORY', -17 );
  define( 'PCLZIP_ERR_UNSUPPORTED_COMPRESSION', -18 );
  define( 'PCLZIP_ERR_UNSUPPORTED_ENCRYPTION', -19 );
  define( 'PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE', -20 );
  define( 'PCLZIP_ERR_DIRECTORY_RESTRICTION', -21 );

  // ----- Options values
  define( 'PCLZIP_OPT_PATH', 77001 );
  define( 'PCLZIP_OPT_ADD_PATH', 77002 );
  define( 'PCLZIP_OPT_REMOVE_PATH', 77003 );
  define( 'PCLZIP_OPT_REMOVE_ALL_PATH', 77004 );
  define( 'PCLZIP_OPT_SET_CHMOD', 77005 );
  define( 'PCLZIP_OPT_EXTRACT_AS_STRING', 77006 );
  define( 'PCLZIP_OPT_NO_COMPRESSION', 77007 );
  define( 'PCLZIP_OPT_BY_NAME', 77008 );
  define( 'PCLZIP_OPT_BY_INDEX', 77009 );
  define( 'PCLZIP_OPT_BY_EREG', 77010 );
  define( 'PCLZIP_OPT_BY_PREG', 77011 );
  define( 'PCLZIP_OPT_COMMENT', 77012 );
  define( 'PCLZIP_OPT_ADD_COMMENT', 77013 );
  define( 'PCLZIP_OPT_PREPEND_COMMENT', 77014 );
  define( 'PCLZIP_OPT_EXTRACT_IN_OUTPUT', 77015 );
  define( 'PCLZIP_OPT_REPLACE_NEWER', 77016 );
  define( 'PCLZIP_OPT_STOP_ON_ERROR', 77017 );
  // Having big trouble with crypt. Need to multiply 2 long int
  // which is not correctly supported by PHP ...
  //define( 'PCLZIP_OPT_CRYPT', 77018 );
  define( 'PCLZIP_OPT_EXTRACT_DIR_RESTRICTION', 77019 );
  
  // ----- File description attributes
  define( 'PCLZIP_ATT_FILE_NAME', 79001 );
  define( 'PCLZIP_ATT_FILE_NEW_SHORT_NAME', 79002 );
  define( 'PCLZIP_ATT_FILE_NEW_FULL_NAME', 79003 );

  // ----- Call backs values
  define( 'PCLZIP_CB_PRE_EXTRACT', 78001 );
  define( 'PCLZIP_CB_POST_EXTRACT', 78002 );
  define( 'PCLZIP_CB_PRE_ADD', 78003 );
  define( 'PCLZIP_CB_POST_ADD', 78004 );
  /* For futur use
  define( 'PCLZIP_CB_PRE_LIST', 78005 );
  define( 'PCLZIP_CB_POST_LIST', 78006 );
  define( 'PCLZIP_CB_PRE_DELETE', 78007 );
  define( 'PCLZIP_CB_POST_DELETE', 78008 );
  */

  // --------------------------------------------------------------------------------
  // Class : PclZip
  // Description :
  //   PclZip is the class that represent a Zip archive.
  //   The public methods allow the manipulation of the archive.
  // Attributes :
  //   Attributes must not be accessed directly.
  // Methods :
  //   PclZip() : Object creator
  //   create() : Creates the Zip archive
  //   listContent() : List the content of the Zip archive
  //   extract() : Extract the content of the archive
  //   properties() : List the properties of the archive
  // --------------------------------------------------------------------------------
  class PclZip
  {
    // ----- Filename of the zip file
    var $zipname = '';

    // ----- File descriptor of the zip file
    var $zip_fd = 0;

    // ----- Internal error handling
    var $error_code = 1;
    var $error_string = '';
    
    // ----- Current status of the magic_quotes_runtime
    // This value store the php configuration for magic_quotes
    // The class can then disable the magic_quotes and reset it after
    var $magic_quotes_status;

  // --------------------------------------------------------------------------------
  // Function : PclZip()
  // Description :
  //   Creates a PclZip object and set the name of the associated Zip archive
  //   filename.
  //   Note that no real action is taken, if the archive does not exist it is not
  //   created. Use create() for that.
  // --------------------------------------------------------------------------------
  function PclZip($p_zipname)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::PclZip', "zipname=$p_zipname");

    // ----- Tests the zlib
    if (!function_exists('gzopen'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 1, "zlib extension seems to be missing");
      die('Abort '.basename(__FILE__).' : Missing zlib extensions');
    }

    // ----- Set the attributes
    $this->zipname = $p_zipname;
    $this->zip_fd = 0;
    $this->magic_quotes_status = -1;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 1);
    return;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function :
  //   create($p_filelist, $p_add_dir="", $p_remove_dir="")
  //   create($p_filelist, $p_option, $p_option_value, ...)
  // Description :
  //   This method supports two different synopsis. The first one is historical.
  //   This method creates a Zip Archive. The Zip file is created in the
  //   filesystem. The files and directories indicated in $p_filelist
  //   are added in the archive. See the parameters description for the
  //   supported format of $p_filelist.
  //   When a directory is in the list, the directory and its content is added
  //   in the archive.
  //   In this synopsis, the function takes an optional variable list of
  //   options. See bellow the supported options.
  // Parameters :
  //   $p_filelist : An array containing file or directory names, or
  //                 a string containing one filename or one directory name, or
  //                 a string containing a list of filenames and/or directory
  //                 names separated by spaces.
  //   $p_add_dir : A path to add before the real path of the archived file,
  //                in order to have it memorized in the archive.
  //   $p_remove_dir : A path to remove from the real path of the file to archive,
  //                   in order to have a shorter path memorized in the archive.
  //                   When $p_add_dir and $p_remove_dir are set, $p_remove_dir
  //                   is removed first, before $p_add_dir is added.
  // Options :
  //   PCLZIP_OPT_ADD_PATH :
  //   PCLZIP_OPT_REMOVE_PATH :
  //   PCLZIP_OPT_REMOVE_ALL_PATH :
  //   PCLZIP_OPT_COMMENT :
  //   PCLZIP_CB_PRE_ADD :
  //   PCLZIP_CB_POST_ADD :
  // Return Values :
  //   0 on failure,
  //   The list of the added files, with a status of the add action.
  //   (see PclZip::listContent() for list entry format)
  // --------------------------------------------------------------------------------
  function create($p_filelist)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::create', "filelist='$p_filelist', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Set default values
    $v_options = array();
    $v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove from the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options detected");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_ADD => 'optional',
                                                   PCLZIP_CB_POST_ADD => 'optional',
                                                   PCLZIP_OPT_NO_COMPRESSION => 'optional',
                                                   PCLZIP_OPT_COMMENT => 'optional'
                                                   //, PCLZIP_OPT_CRYPT => 'optional'
                                             ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                       "Invalid number / type of arguments");
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Init
    $v_string_list = array();
    $v_att_list = array();
    $v_filedescr_list = array();
    $p_result_list = array();
    
    // ----- Look if the $p_filelist is really an array
    if (is_array($p_filelist)) {
    
      // ----- Look if the first element is also an array
      //       This will mean that this is a file description entry
      if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
        $v_att_list = $p_filelist;
      }
      
      // ----- The list is a list of string names
      else {
        $v_string_list = $p_filelist;
      }
    }

    // ----- Look if the $p_filelist is a string
    else if (is_string($p_filelist)) {
      // ----- Create a list from the string
      $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
    }

    // ----- Invalid variable type for $p_filelist
    else {
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_filelist");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }
    
    // ----- Reformat the string list
    if (sizeof($v_string_list) != 0) {
      foreach ($v_string_list as $v_string) {
        if ($v_string != '') {
          $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Ignore an empty filename");
        }
      }
    }
    
    // ----- For each file in the list check the attributes
    $v_supported_attributes
    = array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
             ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
             ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
						);
    foreach ($v_att_list as $v_entry) {
      $v_result = $this->privFileDescrParseAtt($v_entry,
                                               $v_filedescr_list[],
                                               $v_options,
                                               $v_supported_attributes);
      if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }
    }

    // ----- Expand the filelist (expand directories)
    $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Call the create fct
    $v_result = $this->privCreate($v_filedescr_list, $p_result_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_result_list);
    return $p_result_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function :
  //   add($p_filelist, $p_add_dir="", $p_remove_dir="")
  //   add($p_filelist, $p_option, $p_option_value, ...)
  // Description :
  //   This method supports two synopsis. The first one is historical.
  //   This methods add the list of files in an existing archive.
  //   If a file with the same name already exists, it is added at the end of the
  //   archive, the first one is still present.
  //   If the archive does not exist, it is created.
  // Parameters :
  //   $p_filelist : An array containing file or directory names, or
  //                 a string containing one filename or one directory name, or
  //                 a string containing a list of filenames and/or directory
  //                 names separated by spaces.
  //   $p_add_dir : A path to add before the real path of the archived file,
  //                in order to have it memorized in the archive.
  //   $p_remove_dir : A path to remove from the real path of the file to archive,
  //                   in order to have a shorter path memorized in the archive.
  //                   When $p_add_dir and $p_remove_dir are set, $p_remove_dir
  //                   is removed first, before $p_add_dir is added.
  // Options :
  //   PCLZIP_OPT_ADD_PATH :
  //   PCLZIP_OPT_REMOVE_PATH :
  //   PCLZIP_OPT_REMOVE_ALL_PATH :
  //   PCLZIP_OPT_COMMENT :
  //   PCLZIP_OPT_ADD_COMMENT :
  //   PCLZIP_OPT_PREPEND_COMMENT :
  //   PCLZIP_CB_PRE_ADD :
  //   PCLZIP_CB_POST_ADD :
  // Return Values :
  //   0 on failure,
  //   The list of the added files, with a status of the add action.
  //   (see PclZip::listContent() for list entry format)
  // --------------------------------------------------------------------------------
  function add($p_filelist)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::add', "filelist='$p_filelist', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Set default values
    $v_options = array();
    $v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove form the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options detected");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_ADD => 'optional',
                                                   PCLZIP_CB_POST_ADD => 'optional',
                                                   PCLZIP_OPT_NO_COMPRESSION => 'optional',
                                                   PCLZIP_OPT_COMMENT => 'optional',
                                                   PCLZIP_OPT_ADD_COMMENT => 'optional',
                                                   PCLZIP_OPT_PREPEND_COMMENT => 'optional'
                                                   //, PCLZIP_OPT_CRYPT => 'optional'
												   ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Init
    $v_string_list = array();
    $v_att_list = array();
    $v_filedescr_list = array();
    $p_result_list = array();
    
    // ----- Look if the $p_filelist is really an array
    if (is_array($p_filelist)) {
    
      // ----- Look if the first element is also an array
      //       This will mean that this is a file description entry
      if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
        $v_att_list = $p_filelist;
      }
      
      // ----- The list is a list of string names
      else {
        $v_string_list = $p_filelist;
      }
    }

    // ----- Look if the $p_filelist is a string
    else if (is_string($p_filelist)) {
      // ----- Create a list from the string
      $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
    }

    // ----- Invalid variable type for $p_filelist
    else {
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }
    
    // ----- Reformat the string list
    if (sizeof($v_string_list) != 0) {
      foreach ($v_string_list as $v_string) {
        $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
      }
    }
    
    // ----- For each file in the list check the attributes
    $v_supported_attributes
    = array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
             ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
             ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
						);
    foreach ($v_att_list as $v_entry) {
      $v_result = $this->privFileDescrParseAtt($v_entry,
                                               $v_filedescr_list[],
                                               $v_options,
                                               $v_supported_attributes);
      if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }
    }

    // ----- Expand the filelist (expand directories)
    $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Call the create fct
    $v_result = $this->privAdd($v_filedescr_list, $p_result_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_result_list);
    return $p_result_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : listContent()
  // Description :
  //   This public method, gives the list of the files and directories, with their
  //   properties.
  //   The properties of each entries in the list are (used also in other functions) :
  //     filename : Name of the file. For a create or add action it is the filename
  //                given by the user. For an extract function it is the filename
  //                of the extracted file.
  //     stored_filename : Name of the file / directory stored in the archive.
  //     size : Size of the stored file.
  //     compressed_size : Size of the file's data compressed in the archive
  //                       (without the headers overhead)
  //     mtime : Last known modification date of the file (UNIX timestamp)
  //     comment : Comment associated with the file
  //     folder : true | false
  //     index : index of the file in the archive
  //     status : status of the action (depending of the action) :
  //              Values are :
  //                ok : OK !
  //                filtered : the file / dir is not extracted (filtered by user)
  //                already_a_directory : the file can not be extracted because a
  //                                      directory with the same name already exists
  //                write_protected : the file can not be extracted because a file
  //                                  with the same name already exists and is
  //                                  write protected
  //                newer_exist : the file was not extracted because a newer file exists
  //                path_creation_fail : the file is not extracted because the folder
  //                                     does not exists and can not be created
  //                write_error : the file was not extracted because there was a
  //                              error while writing the file
  //                read_error : the file was not extracted because there was a error
  //                             while reading the file
  //                invalid_header : the file was not extracted because of an archive
  //                                 format error (bad file header)
  //   Note that each time a method can continue operating when there
  //   is an action error on a file, the error is only logged in the file status.
  // Return Values :
  //   0 on an unrecoverable failure,
  //   The list of the files in the archive.
  // --------------------------------------------------------------------------------
  function listContent()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::listContent', "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Call the extracting fct
    $p_list = array();
    if (($v_result = $this->privList($p_list)) != 1)
    {
      unset($p_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function :
  //   extract($p_path="./", $p_remove_path="")
  //   extract([$p_option, $p_option_value, ...])
  // Description :
  //   This method supports two synopsis. The first one is historical.
  //   This method extract all the files / directories from the archive to the
  //   folder indicated in $p_path.
  //   If you want to ignore the 'root' part of path of the memorized files
  //   you can indicate this in the optional $p_remove_path parameter.
  //   By default, if a newer file with the same name already exists, the
  //   file is not extracted.
  //
  //   If both PCLZIP_OPT_PATH and PCLZIP_OPT_ADD_PATH aoptions
  //   are used, the path indicated in PCLZIP_OPT_ADD_PATH is append
  //   at the end of the path value of PCLZIP_OPT_PATH.
  // Parameters :
  //   $p_path : Path where the files and directories are to be extracted
  //   $p_remove_path : First part ('root' part) of the memorized path
  //                    (if any similar) to remove while extracting.
  // Options :
  //   PCLZIP_OPT_PATH :
  //   PCLZIP_OPT_ADD_PATH :
  //   PCLZIP_OPT_REMOVE_PATH :
  //   PCLZIP_OPT_REMOVE_ALL_PATH :
  //   PCLZIP_CB_PRE_EXTRACT :
  //   PCLZIP_CB_POST_EXTRACT :
  // Return Values :
  //   0 or a negative value on failure,
  //   The list of the extracted files, with a status of the action.
  //   (see PclZip::listContent() for list entry format)
  // --------------------------------------------------------------------------------
  function extract()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::extract", "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();
//    $v_path = "./";
    $v_path = '';
    $v_remove_path = "";
    $v_remove_all_path = false;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Default values for option
    $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;

    // ----- Look for arguments
    if ($v_size > 0) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional',
                                                   PCLZIP_OPT_BY_NAME => 'optional',
                                                   PCLZIP_OPT_BY_EREG => 'optional',
                                                   PCLZIP_OPT_BY_PREG => 'optional',
                                                   PCLZIP_OPT_BY_INDEX => 'optional',
                                                   PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
                                                   PCLZIP_OPT_EXTRACT_IN_OUTPUT => 'optional',
                                                   PCLZIP_OPT_REPLACE_NEWER => 'optional'
                                                   ,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
                                                   ,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional'
												    ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }

        // ----- Set the arguments
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          // ----- Check for '/' in last path char
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "path='$v_path', remove_path='$v_remove_path', remove_all_path='".($v_remove_path?'true':'false')."'");

    // ----- Call the extracting fct
    $p_list = array();
    $v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path,
	                                     $v_remove_all_path, $v_options);
    if ($v_result < 1) {
      unset($p_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function :
  //   extractByIndex($p_index, $p_path="./", $p_remove_path="")
  //   extractByIndex($p_index, [$p_option, $p_option_value, ...])
  // Description :
  //   This method supports two synopsis. The first one is historical.
  //   This method is doing a partial extract of the archive.
  //   The extracted files or folders are identified by their index in the
  //   archive (from 0 to n).
  //   Note that if the index identify a folder, only the folder entry is
  //   extracted, not all the files included in the archive.
  // Parameters :
  //   $p_index : A single index (integer) or a string of indexes of files to
  //              extract. The form of the string is "0,4-6,8-12" with only numbers
  //              and '-' for range or ',' to separate ranges. No spaces or ';'
  //              are allowed.
  //   $p_path : Path where the files and directories are to be extracted
  //   $p_remove_path : First part ('root' part) of the memorized path
  //                    (if any similar) to remove while extracting.
  // Options :
  //   PCLZIP_OPT_PATH :
  //   PCLZIP_OPT_ADD_PATH :
  //   PCLZIP_OPT_REMOVE_PATH :
  //   PCLZIP_OPT_REMOVE_ALL_PATH :
  //   PCLZIP_OPT_EXTRACT_AS_STRING : The files are extracted as strings and
  //     not as files.
  //     The resulting content is in a new field 'content' in the file
  //     structure.
  //     This option must be used alone (any other options are ignored).
  //   PCLZIP_CB_PRE_EXTRACT :
  //   PCLZIP_CB_POST_EXTRACT :
  // Return Values :
  //   0 on failure,
  //   The list of the extracted files, with a status of the action.
  //   (see PclZip::listContent() for list entry format)
  // --------------------------------------------------------------------------------
  //function extractByIndex($p_index, options...)
  function extractByIndex($p_index)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::extractByIndex", "index='$p_index', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();
//    $v_path = "./";
    $v_path = '';
    $v_remove_path = "";
    $v_remove_all_path = false;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Default values for option
    $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove form the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional',
                                                   PCLZIP_OPT_REPLACE_NEWER => 'optional'
                                                   ,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
                                                   ,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional'
												   ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }

        // ----- Set the arguments
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          // ----- Check for '/' in last path char
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
        if (!isset($v_options[PCLZIP_OPT_EXTRACT_AS_STRING])) {
          $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Option PCLZIP_OPT_EXTRACT_AS_STRING not set.");
        }
        else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Option PCLZIP_OPT_EXTRACT_AS_STRING set.");
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "index='$p_index', path='$v_path', remove_path='$v_remove_path', remove_all_path='".($v_remove_path?'true':'false')."'");

    // ----- Trick
    // Here I want to reuse extractByRule(), so I need to parse the $p_index
    // with privParseOptions()
    $v_arg_trick = array (PCLZIP_OPT_BY_INDEX, $p_index);
    $v_options_trick = array();
    $v_result = $this->privParseOptions($v_arg_trick, sizeof($v_arg_trick), $v_options_trick,
                                        array (PCLZIP_OPT_BY_INDEX => 'optional' ));
    if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
    }
    $v_options[PCLZIP_OPT_BY_INDEX] = $v_options_trick[PCLZIP_OPT_BY_INDEX];

    // ----- Call the extracting fct
    if (($v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path, $v_remove_all_path, $v_options)) < 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
        return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function :
  //   delete([$p_option, $p_option_value, ...])
  // Description :
  //   This method removes files from the archive.
  //   If no parameters are given, then all the archive is emptied.
  // Parameters :
  //   None or optional arguments.
  // Options :
  //   PCLZIP_OPT_BY_INDEX :
  //   PCLZIP_OPT_BY_NAME :
  //   PCLZIP_OPT_BY_EREG : 
  //   PCLZIP_OPT_BY_PREG :
  // Return Values :
  //   0 on failure,
  //   The list of the files which are still present in the archive.
  //   (see PclZip::listContent() for list entry format)
  // --------------------------------------------------------------------------------
  function delete()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::delete", "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 0) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Parse the options
      $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                        array (PCLZIP_OPT_BY_NAME => 'optional',
                                               PCLZIP_OPT_BY_EREG => 'optional',
                                               PCLZIP_OPT_BY_PREG => 'optional',
                                               PCLZIP_OPT_BY_INDEX => 'optional' ));
      if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
      }
    }

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Call the delete fct
    $v_list = array();
    if (($v_result = $this->privDeleteByRule($v_list, $v_options)) != 1) {
      $this->privSwapBackMagicQuotes();
      unset($v_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_list);
    return $v_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : deleteByIndex()
  // Description :
  //   ***** Deprecated *****
  //   delete(PCLZIP_OPT_BY_INDEX, $p_index) should be prefered.
  // --------------------------------------------------------------------------------
  function deleteByIndex($p_index)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::deleteByIndex", "index='$p_index'");
    
    $p_list = $this->delete(PCLZIP_OPT_BY_INDEX, $p_index);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : properties()
  // Description :
  //   This method gives the properties of the archive.
  //   The properties are :
  //     nb : Number of files in the archive
  //     comment : Comment associated with the archive file
  //     status : not_exist, ok
  // Parameters :
  //   None
  // Return Values :
  //   0 on failure,
  //   An array with the archive properties.
  // --------------------------------------------------------------------------------
  function properties()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::properties", "");

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Default properties
    $v_prop = array();
    $v_prop['comment'] = '';
    $v_prop['nb'] = 0;
    $v_prop['status'] = 'not_exist';

    // ----- Look if file exists
    if (@is_file($this->zipname))
    {
      // ----- Open the zip file
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
      if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
      {
        $this->privSwapBackMagicQuotes();
        
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), 0);
        return 0;
      }

      // ----- Read the central directory informations
      $v_central_dir = array();
      if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
      {
        $this->privSwapBackMagicQuotes();
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }

      // ----- Close the zip file
      $this->privCloseFd();

      // ----- Set the user attributes
      $v_prop['comment'] = $v_central_dir['comment'];
      $v_prop['nb'] = $v_central_dir['entries'];
      $v_prop['status'] = 'ok';
    }

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_prop);
    return $v_prop;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : duplicate()
  // Description :
  //   This method creates an archive by copying the content of an other one. If
  //   the archive already exist, it is replaced by the new one without any warning.
  // Parameters :
  //   $p_archive : The filename of a valid archive, or
  //                a valid PclZip object.
  // Return Values :
  //   1 on success.
  //   0 or a negative value on error (error code).
  // --------------------------------------------------------------------------------
  function duplicate($p_archive)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::duplicate", "");
    $v_result = 1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Look if the $p_archive is a PclZip object
    if ((is_object($p_archive)) && (get_class($p_archive) == 'pclzip'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The parameter is valid PclZip object '".$p_archive->zipname."'");

      // ----- Duplicate the archive
      $v_result = $this->privDuplicate($p_archive->zipname);
    }

    // ----- Look if the $p_archive is a string (so a filename)
    else if (is_string($p_archive))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The parameter is a filename '$p_archive'");

      // ----- Check that $p_archive is a valid zip file
      // TBC : Should also check the archive format
      if (!is_file($p_archive)) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "No file with filename '".$p_archive."'");
        $v_result = PCLZIP_ERR_MISSING_FILE;
      }
      else {
        // ----- Duplicate the archive
        $v_result = $this->privDuplicate($p_archive);
      }
    }

    // ----- Invalid variable
    else
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_archive_to_add");
      $v_result = PCLZIP_ERR_INVALID_PARAMETER;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : merge()
  // Description :
  //   This method merge the $p_archive_to_add archive at the end of the current
  //   one ($this).
  //   If the archive ($this) does not exist, the merge becomes a duplicate.
  //   If the $p_archive_to_add archive does not exist, the merge is a success.
  // Parameters :
  //   $p_archive_to_add : It can be directly the filename of a valid zip archive,
  //                       or a PclZip object archive.
  // Return Values :
  //   1 on success,
  //   0 or negative values on error (see below).
  // --------------------------------------------------------------------------------
  function merge($p_archive_to_add)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::merge", "");
    $v_result = 1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Look if the $p_archive_to_add is a PclZip object
    if ((is_object($p_archive_to_add)) && (get_class($p_archive_to_add) == 'pclzip'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The parameter is valid PclZip object");

      // ----- Merge the archive
      $v_result = $this->privMerge($p_archive_to_add);
    }

    // ----- Look if the $p_archive_to_add is a string (so a filename)
    else if (is_string($p_archive_to_add))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The parameter is a filename");

      // ----- Create a temporary archive
      $v_object_archive = new PclZip($p_archive_to_add);

      // ----- Merge the archive
      $v_result = $this->privMerge($v_object_archive);
    }

    // ----- Invalid variable
    else
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_archive_to_add");
      $v_result = PCLZIP_ERR_INVALID_PARAMETER;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------



  // --------------------------------------------------------------------------------
  // Function : errorCode()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function errorCode()
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      return(PclErrorCode());
    }
    else {
      return($this->error_code);
    }
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : errorName()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function errorName($p_with_code=false)
  {
    $v_name = array ( PCLZIP_ERR_NO_ERROR => 'PCLZIP_ERR_NO_ERROR',
                      PCLZIP_ERR_WRITE_OPEN_FAIL => 'PCLZIP_ERR_WRITE_OPEN_FAIL',
                      PCLZIP_ERR_READ_OPEN_FAIL => 'PCLZIP_ERR_READ_OPEN_FAIL',
                      PCLZIP_ERR_INVALID_PARAMETER => 'PCLZIP_ERR_INVALID_PARAMETER',
                      PCLZIP_ERR_MISSING_FILE => 'PCLZIP_ERR_MISSING_FILE',
                      PCLZIP_ERR_FILENAME_TOO_LONG => 'PCLZIP_ERR_FILENAME_TOO_LONG',
                      PCLZIP_ERR_INVALID_ZIP => 'PCLZIP_ERR_INVALID_ZIP',
                      PCLZIP_ERR_BAD_EXTRACTED_FILE => 'PCLZIP_ERR_BAD_EXTRACTED_FILE',
                      PCLZIP_ERR_DIR_CREATE_FAIL => 'PCLZIP_ERR_DIR_CREATE_FAIL',
                      PCLZIP_ERR_BAD_EXTENSION => 'PCLZIP_ERR_BAD_EXTENSION',
                      PCLZIP_ERR_BAD_FORMAT => 'PCLZIP_ERR_BAD_FORMAT',
                      PCLZIP_ERR_DELETE_FILE_FAIL => 'PCLZIP_ERR_DELETE_FILE_FAIL',
                      PCLZIP_ERR_RENAME_FILE_FAIL => 'PCLZIP_ERR_RENAME_FILE_FAIL',
                      PCLZIP_ERR_BAD_CHECKSUM => 'PCLZIP_ERR_BAD_CHECKSUM',
                      PCLZIP_ERR_INVALID_ARCHIVE_ZIP => 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP',
                      PCLZIP_ERR_MISSING_OPTION_VALUE => 'PCLZIP_ERR_MISSING_OPTION_VALUE',
                      PCLZIP_ERR_INVALID_OPTION_VALUE => 'PCLZIP_ERR_INVALID_OPTION_VALUE',
                      PCLZIP_ERR_UNSUPPORTED_COMPRESSION => 'PCLZIP_ERR_UNSUPPORTED_COMPRESSION',
                      PCLZIP_ERR_UNSUPPORTED_ENCRYPTION => 'PCLZIP_ERR_UNSUPPORTED_ENCRYPTION'
                      ,PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE => 'PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE'
                      ,PCLZIP_ERR_DIRECTORY_RESTRICTION => 'PCLZIP_ERR_DIRECTORY_RESTRICTION'
                    );

    if (isset($v_name[$this->error_code])) {
      $v_value = $v_name[$this->error_code];
    }
    else {
      $v_value = 'NoName';
    }

    if ($p_with_code) {
      return($v_value.' ('.$this->error_code.')');
    }
    else {
      return($v_value);
    }
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : errorInfo()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function errorInfo($p_full=false)
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      return(PclErrorString());
    }
    else {
      if ($p_full) {
        return($this->errorName(true)." : ".$this->error_string);
      }
      else {
        return($this->error_string." [code ".$this->error_code."]");
      }
    }
  }
  // --------------------------------------------------------------------------------


// --------------------------------------------------------------------------------
// ***** UNDER THIS LINE ARE DEFINED PRIVATE INTERNAL FUNCTIONS *****
// *****                                                        *****
// *****       THESES FUNCTIONS MUST NOT BE USED DIRECTLY       *****
// --------------------------------------------------------------------------------



  // --------------------------------------------------------------------------------
  // Function : privCheckFormat()
  // Description :
  //   This method check that the archive exists and is a valid zip archive.
  //   Several level of check exists. (futur)
  // Parameters :
  //   $p_level : Level of check. Default 0.
  //              0 : Check the first bytes (magic codes) (default value))
  //              1 : 0 + Check the central directory (futur)
  //              2 : 1 + Check each file header (futur)
  // Return Values :
  //   true on success,
  //   false on error, the error code is set.
  // --------------------------------------------------------------------------------
  function privCheckFormat($p_level=0)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCheckFormat", "");
    $v_result = true;

	// ----- Reset the file system cache
    clearstatcache();

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Look if the file exits
    if (!is_file($this->zipname)) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "Missing archive file '".$this->zipname."'");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, false, PclZip::errorInfo());
      return(false);
    }

    // ----- Check that the file is readeable
    if (!is_readable($this->zipname)) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "Unable to read archive '".$this->zipname."'");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, false, PclZip::errorInfo());
      return(false);
    }

    // ----- Check the magic code
    // TBC

    // ----- Check the central header
    // TBC

    // ----- Check each file header
    // TBC

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privParseOptions()
  // Description :
  //   This internal methods reads the variable list of arguments ($p_options_list,
  //   $p_size) and generate an array with the options and values ($v_result_list).
  //   $v_requested_options contains the options that can be present and those that
  //   must be present.
  //   $v_requested_options is an array, with the option value as key, and 'optional',
  //   or 'mandatory' as value.
  // Parameters :
  //   See above.
  // Return Values :
  //   1 on success.
  //   0 on failure.
  // --------------------------------------------------------------------------------
  function privParseOptions(&$p_options_list, $p_size, &$v_result_list, $v_requested_options=false)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privParseOptions", "");
    $v_result=1;
    
    // ----- Read the options
    $i=0;
    while ($i<$p_size) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Looking for table index $i, option = '".PclZipUtilOptionText($p_options_list[$i])."(".$p_options_list[$i].")'");

      // ----- Check if the option is supported
      if (!isset($v_requested_options[$p_options_list[$i]])) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid optional parameter '".$p_options_list[$i]."' for this method");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look for next option
      switch ($p_options_list[$i]) {
        // ----- Look for options that request a path value
        case PCLZIP_OPT_PATH :
        case PCLZIP_OPT_REMOVE_PATH :
        case PCLZIP_OPT_ADD_PATH :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i+1], false);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        case PCLZIP_OPT_EXTRACT_DIR_RESTRICTION :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (   is_string($p_options_list[$i+1])
              && ($p_options_list[$i+1] != '')) {
            $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i+1], false);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
            $i++;
          }
          else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." set with an empty value is ignored.");
          }
        break;

        // ----- Look for options that request an array of string for value
        case PCLZIP_OPT_BY_NAME :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]][0] = $p_options_list[$i+1];
          }
          else if (is_array($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Wrong parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request an EREG or PREG expression
        case PCLZIP_OPT_BY_EREG :
        case PCLZIP_OPT_BY_PREG :
        //case PCLZIP_OPT_CRYPT :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Wrong parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that takes a string
        case PCLZIP_OPT_COMMENT :
        case PCLZIP_OPT_ADD_COMMENT :
        case PCLZIP_OPT_PREPEND_COMMENT :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE,
			                     "Missing parameter value for option '"
								 .PclZipUtilOptionText($p_options_list[$i])
								 ."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE,
			                     "Wrong parameter value for option '"
								 .PclZipUtilOptionText($p_options_list[$i])
								 ."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request an array of index
        case PCLZIP_OPT_BY_INDEX :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_work_list = array();
          if (is_string($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is a string '".$p_options_list[$i+1]."'");

              // ----- Remove spaces
              $p_options_list[$i+1] = strtr($p_options_list[$i+1], ' ', '');

              // ----- Parse items
              $v_work_list = explode(",", $p_options_list[$i+1]);
          }
          else if (is_integer($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is an integer '".$p_options_list[$i+1]."'");
              $v_work_list[0] = $p_options_list[$i+1].'-'.$p_options_list[$i+1];
          }
          else if (is_array($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is an array");
              $v_work_list = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Value must be integer, string or array for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          
          // ----- Reduce the index list
          // each index item in the list must be a couple with a start and
          // an end value : [0,3], [5-5], [8-10], ...
          // ----- Check the format of each item
          $v_sort_flag=false;
          $v_sort_value=0;
          for ($j=0; $j<sizeof($v_work_list); $j++) {
              // ----- Explode the item
              $v_item_list = explode("-", $v_work_list[$j]);
              $v_size_item_list = sizeof($v_item_list);
              
              // ----- TBC : Here we might check that each item is a
              // real integer ...
              
              // ----- Look for single value
              if ($v_size_item_list == 1) {
                  // ----- Set the option value
                  $v_result_list[$p_options_list[$i]][$j]['start'] = $v_item_list[0];
                  $v_result_list[$p_options_list[$i]][$j]['end'] = $v_item_list[0];
              }
              elseif ($v_size_item_list == 2) {
                  // ----- Set the option value
                  $v_result_list[$p_options_list[$i]][$j]['start'] = $v_item_list[0];
                  $v_result_list[$p_options_list[$i]][$j]['end'] = $v_item_list[1];
              }
              else {
                  // ----- Error log
                  PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Too many values in index range for option '".PclZipUtilOptionText($p_options_list[$i])."'");

                  // ----- Return
                  //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                  return PclZip::errorCode();
              }

              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extracted index item = [".$v_result_list[$p_options_list[$i]][$j]['start'].",".$v_result_list[$p_options_list[$i]][$j]['end']."]");

              // ----- Look for list sort
              if ($v_result_list[$p_options_list[$i]][$j]['start'] < $v_sort_value) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The list should be sorted ...");
                  $v_sort_flag=true;

                  // ----- TBC : An automatic sort should be writen ...
                  // ----- Error log
                  PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Invalid order of index range for option '".PclZipUtilOptionText($p_options_list[$i])."'");

                  // ----- Return
                  //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                  return PclZip::errorCode();
              }
              $v_sort_value = $v_result_list[$p_options_list[$i]][$j]['start'];
          }
          
          // ----- Sort the items
          if ($v_sort_flag) {
              // TBC : To Be Completed
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "List sorting is not yet write ...");
          }

          // ----- Next option
          $i++;
        break;

        // ----- Look for options that request no value
        case PCLZIP_OPT_REMOVE_ALL_PATH :
        case PCLZIP_OPT_EXTRACT_AS_STRING :
        case PCLZIP_OPT_NO_COMPRESSION :
        case PCLZIP_OPT_EXTRACT_IN_OUTPUT :
        case PCLZIP_OPT_REPLACE_NEWER :
        case PCLZIP_OPT_STOP_ON_ERROR :
          $v_result_list[$p_options_list[$i]] = true;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
        break;

        // ----- Look for options that request an octal value
        case PCLZIP_OPT_SET_CHMOD :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request a call-back
        case PCLZIP_CB_PRE_EXTRACT :
        case PCLZIP_CB_POST_EXTRACT :
        case PCLZIP_CB_PRE_ADD :
        case PCLZIP_CB_POST_ADD :
        /* for futur use
        case PCLZIP_CB_PRE_DELETE :
        case PCLZIP_CB_POST_DELETE :
        case PCLZIP_CB_PRE_LIST :
        case PCLZIP_CB_POST_LIST :
        */
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_function_name = $p_options_list[$i+1];
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "call-back ".PclZipUtilOptionText($p_options_list[$i])." = '".$v_function_name."'");

          // ----- Check that the value is a valid existing function
          if (!function_exists($v_function_name)) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Function '".$v_function_name."()' is not an existing function for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Set the attribute
          $v_result_list[$p_options_list[$i]] = $v_function_name;
          $i++;
        break;

        default :
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                       "Unknown parameter '"
							   .$p_options_list[$i]."'");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
      }

      // ----- Next options
      $i++;
    }

    // ----- Look for mandatory options
    if ($v_requested_options !== false) {
      for ($key=reset($v_requested_options); $key=key($v_requested_options); $key=next($v_requested_options)) {
        // ----- Look for mandatory option
        if ($v_requested_options[$key] == 'mandatory') {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Detect a mandatory option : ".PclZipUtilOptionText($key)."(".$key.")");
          // ----- Look if present
          if (!isset($v_result_list[$key])) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Missing mandatory parameter ".PclZipUtilOptionText($key)."(".$key.")");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        }
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privFileDescrParseAtt()
  // Description :
  // Parameters :
  // Return Values :
  //   1 on success.
  //   0 on failure.
  // --------------------------------------------------------------------------------
  function privFileDescrParseAtt(&$p_file_list, &$p_filedescr, $v_options, $v_requested_options=false)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privFileDescrParseAtt", "");
    $v_result=1;
    
    // ----- For each file in the list check the attributes
    foreach ($p_file_list as $v_key => $v_value) {
    
      // ----- Check if the option is supported
      if (!isset($v_requested_options[$v_key])) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid file attribute '".$v_key."' for this file");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look for attribute
      switch ($v_key) {
        case PCLZIP_ATT_FILE_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['filename'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");
          
          if ($p_filedescr['filename'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

        break;

        case PCLZIP_ATT_FILE_NEW_SHORT_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['new_short_name'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");

          if ($p_filedescr['new_short_name'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty short filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        break;

        case PCLZIP_ATT_FILE_NEW_FULL_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['new_full_name'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");

          if ($p_filedescr['new_full_name'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty full filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        break;

        default :
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                           "Unknown parameter '".$v_key."'");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
      }

      // ----- Look for mandatory options
      if ($v_requested_options !== false) {
        for ($key=reset($v_requested_options); $key=key($v_requested_options); $key=next($v_requested_options)) {
          // ----- Look for mandatory option
          if ($v_requested_options[$key] == 'mandatory') {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Detect a mandatory option : ".PclZipUtilOptionText($key)."(".$key.")");
            // ----- Look if present
            if (!isset($p_file_list[$key])) {
              PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Missing mandatory parameter ".PclZipUtilOptionText($key)."(".$key.")");
              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
            }
          }
        }
      }
    
    // end foreach
    }
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privFileDescrExpand()
  // Description :
  // Parameters :
  // Return Values :
  //   1 on success.
  //   0 on failure.
  // --------------------------------------------------------------------------------
  function privFileDescrExpand(&$p_filedescr_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privFileDescrExpand", "");
    $v_result=1;
    
    // ----- Create a result list
    $v_result_list = array();
    
    // ----- Look each entry
    for ($i=0; $i<sizeof($p_filedescr_list); $i++) {
      // ----- Get filedescr
      $v_descr = $p_filedescr_list[$i];
      
      // ----- Reduce the filename
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filedescr before reduction :'".$v_descr['filename']."'");
      $v_descr['filename'] = PclZipUtilTranslateWinPath($v_descr['filename']);
      $v_descr['filename'] = PclZipUtilPathReduction($v_descr['filename']);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filedescr after reduction :'".$v_descr['filename']."'");
      
      // ----- Get type of descr
      if (!file_exists($v_descr['filename'])) {
        // ----- Error log
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_descr['filename']."' does not exists");
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$v_descr['filename']."' does not exists");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      if (@is_file($v_descr['filename'])) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "This is a file");
        $v_descr['type'] = 'file';
      }
      else if (@is_dir($v_descr['filename'])) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "This is a folder");
        $v_descr['type'] = 'folder';
      }
      else if (@is_link($v_descr['filename'])) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Unsupported file type : link");
        // skip
        continue;
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Unsupported file type : unknown type");
        // skip
        continue;
      }
      
      // ----- Calculate the stored filename
      $this->privCalculateStoredFilename($v_descr, $p_options);
      
      // ----- Add the descriptor in result list
      $v_result_list[sizeof($v_result_list)] = $v_descr;
      
      // ----- Look for folder
      if ($v_descr['type'] == 'folder') {
        // ----- List of items in folder
        $v_dirlist_descr = array();
        $v_dirlist_nb = 0;
        if ($v_folder_handler = @opendir($v_descr['filename'])) {
          while (($v_item_handler = @readdir($v_folder_handler)) !== false) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Looking for '".$v_item_handler."' in the directory");

            // ----- Skip '.' and '..'
            if (($v_item_handler == '.') || ($v_item_handler == '..')) {
                continue;
            }
            
            // ----- Compose the full filename
            $v_dirlist_descr[$v_dirlist_nb]['filename'] = $v_descr['filename'].'/'.$v_item_handler;
            
            // ----- Look for different stored filename
            // Because the name of the folder was changed, the name of the
            // files/sub-folders also change
            if ($v_descr['stored_filename'] != $v_descr['filename']) {
              $v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_descr['stored_filename'].'/'.$v_item_handler;
            }
      
            $v_dirlist_nb++;
          }
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to open dir '".$v_descr['filename']."' in read mode. Skipped.");
          // TBC : unable to open folder in read mode
        }
        
        // ----- Expand each element of the list
        if ($v_dirlist_nb != 0) {
          // ----- Expand
          if (($v_result = $this->privFileDescrExpand($v_dirlist_descr, $p_options)) != 1) {
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }
          
          // ----- Concat the resulting list
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Merging result list (size '".sizeof($v_result_list)."') with dirlist (size '".sizeof($v_dirlist_descr)."')");
          $v_result_list = array_merge($v_result_list, $v_dirlist_descr);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "merged result list is size '".sizeof($v_result_list)."'");
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Nothing in this folder to expand.");
        }
          
        // ----- Free local array
        unset($v_dirlist_descr);
      }
    }
    
    // ----- Get the result list
    $p_filedescr_list = $v_result_list;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privCreate()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privCreate($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCreate", "list");
    $v_result=1;
    $v_list_detail = array();
    
    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the file in write mode
    if (($v_result = $this->privOpenFd('wb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Add the list of files
    $v_result = $this->privAddList($p_filedescr_list, $p_result_list, $p_options);

    // ----- Close
    $this->privCloseFd();

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privAdd()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privAdd($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAdd", "list");
    $v_result=1;
    $v_list_detail = array();

    // ----- Look if the archive exists or is empty
    if ((!is_file($this->zipname)) || (filesize($this->zipname) == 0))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive does not exist, or is empty, create it.");

      // ----- Do a create
      $v_result = $this->privCreate($p_filedescr_list, $p_result_list, $p_options);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }
    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Magic quotes trick
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Creates a temporay file
    $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = $v_central_dir['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Swap the file descriptor
    // Here is a trick : I swap the temporary fd with the zip fd, in order to use
    // the following methods on the temporary fil and not the real archive
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Add the files
    $v_header_list = array();
    if (($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
    {
      fclose($v_zip_temp_fd);
      $this->privCloseFd();
      @unlink($v_zip_temp_name);
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "New offset of central dir : $v_offset");

    // ----- Copy the block of file headers from the old archive
    $v_size = $v_central_dir['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
      @fwrite($this->zip_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Create the Central Dir files header
    for ($i=0, $v_count=0; $i<sizeof($v_header_list); $i++)
    {
      // ----- Create the file header
      if ($v_header_list[$i]['status'] == 'ok') {
        if (($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
          fclose($v_zip_temp_fd);
          $this->privCloseFd();
          @unlink($v_zip_temp_name);
          $this->privSwapBackMagicQuotes();

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
        $v_count++;
      }

      // ----- Transform the header to a 'usable' info
      $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
    }

    // ----- Zip file comment
    $v_comment = $v_central_dir['comment'];
    if (isset($p_options[PCLZIP_OPT_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
      $v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
    }

    // ----- Calculate the size of the central header
    $v_size = @ftell($this->zip_fd)-$v_offset;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
    {
      // ----- Reset the file list
      unset($v_header_list);
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Swap back the file descriptor
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Close
    $this->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Delete the zip file
    // TBC : I should test the result ...
    @unlink($this->zipname);

    // ----- Rename the temporary file
    // TBC : I should test the result ...
    //@rename($v_zip_temp_name, $this->zipname);
    PclZipUtilRename($v_zip_temp_name, $this->zipname);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privOpenFd()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function privOpenFd($p_mode)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privOpenFd", 'mode='.$p_mode);
    $v_result=1;

    // ----- Look if already open
    if ($this->zip_fd != 0)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Zip file \''.$this->zipname.'\' already open');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Open file in '.$p_mode.' mode');
    if (($this->zip_fd = @fopen($this->zipname, $p_mode)) == 0)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in '.$p_mode.' mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privCloseFd()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function privCloseFd()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCloseFd", "");
    $v_result=1;

    if ($this->zip_fd != 0)
      @fclose($this->zip_fd);
    $this->zip_fd = 0;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privAddList()
  // Description :
  //   $p_add_dir and $p_remove_dir will give the ability to memorize a path which is
  //   different from the real path of the file. This is usefull if you want to have PclTar
  //   running in any directory, and memorize relative path from an other directory.
  // Parameters :
  //   $p_list : An array containing the file or directory names to add in the tar
  //   $p_result_list : list of added files with their properties (specially the status field)
  //   $p_add_dir : Path to add in the filename path archived
  //   $p_remove_dir : Path to remove in the filename path archived
  // Return Values :
  // --------------------------------------------------------------------------------
//  function privAddList($p_list, &$p_result_list, $p_add_dir, $p_remove_dir, $p_remove_all_dir, &$p_options)
  function privAddList($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddList", "list");
    $v_result=1;

    // ----- Add the files
    $v_header_list = array();
    if (($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($this->zip_fd);

    // ----- Create the Central Dir files header
    for ($i=0,$v_count=0; $i<sizeof($v_header_list); $i++)
    {
      // ----- Create the file header
      if ($v_header_list[$i]['status'] == 'ok') {
        if (($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
        $v_count++;
      }

      // ----- Transform the header to a 'usable' info
      $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
    }

    // ----- Zip file comment
    $v_comment = '';
    if (isset($p_options[PCLZIP_OPT_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_COMMENT];
    }

    // ----- Calculate the size of the central header
    $v_size = @ftell($this->zip_fd)-$v_offset;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_count, $v_size, $v_offset, $v_comment)) != 1)
    {
      // ----- Reset the file list
      unset($v_header_list);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privAddFileList()
  // Description :
  // Parameters :
  //   $p_filedescr_list : An array containing the file description 
  //                      or directory names to add in the zip
  //   $p_result_list : list of added files with their properties (specially the status field)
  // Return Values :
  // --------------------------------------------------------------------------------
  function privAddFileList($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddFileList", "filedescr_list");
    $v_result=1;
    $v_header = array();

    // ----- Recuperate the current number of elt in list
    $v_nb = sizeof($p_result_list);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Before add, list have ".$v_nb." elements");

    // ----- Loop on the files
    for ($j=0; ($j<sizeof($p_filedescr_list)) && ($v_result==1); $j++) {
      // ----- Format the filename
      $p_filedescr_list[$j]['filename']
      = PclZipUtilTranslateWinPath($p_filedescr_list[$j]['filename'], false);
      
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Looking for file '".$p_filedescr_list[$j]['filename']."'");

      // ----- Skip empty file names
      // TBC : Can this be possible ? not checked in DescrParseAtt ?
      if ($p_filedescr_list[$j]['filename'] == "") {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Skip empty filename");
        continue;
      }

      // ----- Check the filename
      if (!file_exists($p_filedescr_list[$j]['filename'])) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$p_filedescr_list[$j]['filename']."' does not exists");
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$p_filedescr_list[$j]['filename']."' does not exists");
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look if it is a file or a dir with no all path remove option
      if (   (is_file($p_filedescr_list[$j]['filename']))
          || (   is_dir($p_filedescr_list[$j]['filename'])
              && (   !isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])
                  || !$p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))) {

        // ----- Add the file
        $v_result = $this->privAddFile($p_filedescr_list[$j], $v_header,
                                       $p_options);
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        // ----- Store the file infos
        $p_result_list[$v_nb++] = $v_header;
      }
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "After add, list have ".$v_nb." elements");

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privAddFile()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privAddFile($p_filedescr, &$p_header, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddFile", "filename='".$p_filedescr['filename']."'");
    $v_result=1;
    
    // ----- Working variable
    $p_filename = $p_filedescr['filename'];

    // TBC : Already done in the fileAtt check ... ?
    if ($p_filename == "") {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid file list parameter (invalid or empty list)");

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }
  
    // ----- Look for a stored different filename 
    if (isset($p_filedescr['stored_filename'])) {
      $v_stored_filename = $p_filedescr['stored_filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, 'Stored filename is NOT the same "'.$v_stored_filename.'"');
    }
    else {
      $v_stored_filename = $p_filedescr['stored_filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, 'Stored filename is the same');
    }

    // ----- Set the file properties
    clearstatcache();
    $p_header['version'] = 20;
    $p_header['version_extracted'] = 10;
    $p_header['flag'] = 0;
    $p_header['compression'] = 0;
    $p_header['mtime'] = filemtime($p_filename);
    $p_header['crc'] = 0;
    $p_header['compressed_size'] = 0;
    $p_header['size'] = filesize($p_filename);
    $p_header['filename_len'] = strlen($p_filename);
    $p_header['extra_len'] = 0;
    $p_header['comment_len'] = 0;
    $p_header['disk'] = 0;
    $p_header['internal'] = 0;
//    $p_header['external'] = (is_file($p_filename)?0xFE49FFE0:0x41FF0010);
    $p_header['external'] = (is_file($p_filename)?0x00000000:0x00000010);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header external extension '".sprintf("0x%X",$p_header['external'])."'");
    $p_header['offset'] = 0;
    $p_header['filename'] = $p_filename;
    $p_header['stored_filename'] = $v_stored_filename;
    $p_header['extra'] = '';
    $p_header['comment'] = '';
    $p_header['status'] = 'ok';
    $p_header['index'] = -1;

    // ----- Look for pre-add callback
    if (isset($p_options[PCLZIP_CB_PRE_ADD])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_ADD]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_header, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_ADD].'(PCLZIP_CB_PRE_ADD, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_header['status'] = "skipped";
        $v_result = 1;
      }

      // ----- Update the informations
      // Only some fields can be modified
      if ($p_header['stored_filename'] != $v_local_header['stored_filename']) {
        $p_header['stored_filename'] = PclZipUtilPathReduction($v_local_header['stored_filename']);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New stored filename is '".$p_header['stored_filename']."'");
      }
    }

    // ----- Look for empty stored filename
    if ($p_header['stored_filename'] == "") {
      $p_header['status'] = "filtered";
    }
    
    // ----- Check the path length
    if (strlen($p_header['stored_filename']) > 0xFF) {
      $p_header['status'] = 'filename_too_long';
    }

    // ----- Look if no error, or file not skipped
    if ($p_header['status'] == 'ok') {

      // ----- Look for a file
      if (is_file($p_filename))
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "'".$p_filename."' is a file");
        // ----- Open the source file
        if (($v_file = @fopen($p_filename, "rb")) == 0) {
          PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "Unable to open file '$p_filename' in binary read mode");
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
        }

        if ($p_options[PCLZIP_OPT_NO_COMPRESSION]) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will not be compressed");
          // ----- Read the file content
          $v_content_compressed = @fread($v_file, $p_header['size']);

          // ----- Calculate the CRC
          $p_header['crc'] = @crc32($v_content_compressed);

          // ----- Set header parameters
          $p_header['compressed_size'] = $p_header['size'];
          $p_header['compression'] = 0;
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will be compressed");
          // ----- Read the file content
          $v_content = @fread($v_file, $p_header['size']);

          // ----- Calculate the CRC
          $p_header['crc'] = @crc32($v_content);

          // ----- Compress the file
          $v_content_compressed = @gzdeflate($v_content);

          // ----- Set header parameters
          $p_header['compressed_size'] = strlen($v_content_compressed);
          $p_header['compression'] = 8;
        }
        
        // ----- Look for encryption
        /*
        if ((isset($p_options[PCLZIP_OPT_CRYPT]))
		    && ($p_options[PCLZIP_OPT_CRYPT] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File need to be crypted ....");
          
          // Should be a random header
          $v_header = 'xxxxxxxxxxxx';
	      $v_content_compressed = PclZipUtilZipEncrypt($v_content_compressed,
		                                           $p_header['compressed_size'],
	                                               $v_header,
												   $p_header['crc'],
												   "test");
												   
          $p_header['compressed_size'] += 12;
          $p_header['flag'] = 1;
          
          // ----- Add the header to the data
          $v_content_compressed = $v_header.$v_content_compressed;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Size after header : ".strlen($v_content_compressed)."");
        }
        */

        // ----- Call the header generation
        if (($v_result = $this->privWriteFileHeader($p_header)) != 1) {
          @fclose($v_file);
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        // ----- Write the compressed (or not) content
        @fwrite($this->zip_fd, 
		            $v_content_compressed, $p_header['compressed_size']);
        
        // ----- Close the file
        @fclose($v_file);
      }

      // ----- Look for a directory
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "'".$p_filename."' is a folder");
        // ----- Look for directory last '/'
        if (@substr($p_header['stored_filename'], -1) != '/') {
          $p_header['stored_filename'] .= '/';
        }

        // ----- Set the file properties
        $p_header['size'] = 0;
        //$p_header['external'] = 0x41FF0010;   // Value for a folder : to be checked
        $p_header['external'] = 0x00000010;   // Value for a folder : to be checked

        // ----- Call the header generation
        if (($v_result = $this->privWriteFileHeader($p_header)) != 1)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
      }
    }

    // ----- Look for post-add callback
    if (isset($p_options[PCLZIP_CB_POST_ADD])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_ADD]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_header, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_ADD].'(PCLZIP_CB_POST_ADD, $v_local_header);');
      if ($v_result == 0) {
        // ----- Ignored
        $v_result = 1;
      }

      // ----- Update the informations
      // Nothing can be modified
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privCalculateStoredFilename()
  // Description :
  //   Based on file descriptor properties and global options, this method
  //   calculate the filename that will be stored in the archive.
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privCalculateStoredFilename(&$p_filedescr, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCalculateStoredFilename", "filename='".$p_filedescr['filename']."'");
    $v_result=1;
    
    // ----- Working variables
    $p_filename = $p_filedescr['filename'];
    if (isset($p_options[PCLZIP_OPT_ADD_PATH])) {
      $p_add_dir = $p_options[PCLZIP_OPT_ADD_PATH];
    }
    else {
      $p_add_dir = '';
    }
    if (isset($p_options[PCLZIP_OPT_REMOVE_PATH])) {
      $p_remove_dir = $p_options[PCLZIP_OPT_REMOVE_PATH];
    }
    else {
      $p_remove_dir = '';
    }
    if (isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
      $p_remove_all_dir = $p_options[PCLZIP_OPT_REMOVE_ALL_PATH];
    }
    else {
      $p_remove_all_dir = 0;
    }

    // ----- Look for full name change
    if (isset($p_filedescr['new_full_name'])) {
      $v_stored_filename = $p_filedescr['new_full_name'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Changing full name of '".$p_filename."' for '".$v_stored_filename."'");
    }
    
    // ----- Look for path and/or short name change
    else {

      // ----- Look for short name change
      if (isset($p_filedescr['new_short_name'])) {
        $v_path_info = pathinfo($p_filename);
        $v_dir = '';
        if ($v_path_info['dirname'] != '') {
          $v_dir = $v_path_info['dirname'].'/';
        }
        $v_stored_filename = $v_dir.$p_filedescr['new_short_name'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Changing short name of '".$p_filename."' for '".$v_stored_filename."'");
      }
      else {
        // ----- Calculate the stored filename
        $v_stored_filename = $p_filename;
      }

      // ----- Look for all path to remove
      if ($p_remove_all_dir) {
        $v_stored_filename = basename($p_filename);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove all path selected change '".$p_filename."' for '".$v_stored_filename."'");
      }
      // ----- Look for partial path remove
      else if ($p_remove_dir != "") {
        if (substr($p_remove_dir, -1) != '/')
          $p_remove_dir .= "/";

        if (   (substr($p_filename, 0, 2) == "./")
            || (substr($p_remove_dir, 0, 2) == "./")) {
            
          if (   (substr($p_filename, 0, 2) == "./")
              && (substr($p_remove_dir, 0, 2) != "./")) {
            $p_remove_dir = "./".$p_remove_dir;
          }
          if (   (substr($p_filename, 0, 2) != "./")
              && (substr($p_remove_dir, 0, 2) == "./")) {
            $p_remove_dir = substr($p_remove_dir, 2);
          }
        }

        $v_compare = PclZipUtilPathInclusion($p_remove_dir,
                                             $v_stored_filename);
        if ($v_compare > 0) {
          if ($v_compare == 2) {
            $v_stored_filename = "";
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Path to remove is the current folder");
          }
          else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove path '$p_remove_dir' in file '$v_stored_filename'");
            $v_stored_filename = substr($v_stored_filename,
                                        strlen($p_remove_dir));
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Result is '$v_stored_filename'");
          }
        }
      }
      // ----- Look for path to add
      if ($p_add_dir != "") {
        if (substr($p_add_dir, -1) == "/")
          $v_stored_filename = $p_add_dir.$v_stored_filename;
        else
          $v_stored_filename = $p_add_dir."/".$v_stored_filename;
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Add path '$p_add_dir' in file '$p_filename' = '$v_stored_filename'");
      }
    }

    // ----- Filename (reduce the path of stored name)
    $v_stored_filename = PclZipUtilPathReduction($v_stored_filename);
    $p_filedescr['stored_filename'] = $v_stored_filename;
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Stored filename will be '".$p_filedescr['stored_filename']."', strlen ".strlen($p_filedescr['stored_filename']));
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privWriteFileHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privWriteFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteFileHeader", 'file="'.$p_header['filename'].'", stored as "'.$p_header['stored_filename'].'"');
    $v_result=1;

    // ----- Store the offset position of the file
    $p_header['offset'] = ftell($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, 'File offset of the header :'.$p_header['offset']);

    // ----- Transform UNIX mtime to DOS format mdate/mtime
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    $v_date = getdate($p_header['mtime']);
    $v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
    $v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

    // ----- Packed data
    $v_binary_data = pack("VvvvvvVVVvv", 0x04034b50,
	                      $p_header['version_extracted'], $p_header['flag'],
                          $p_header['compression'], $v_mtime, $v_mdate,
                          $p_header['crc'], $p_header['compressed_size'],
						  $p_header['size'],
                          strlen($p_header['stored_filename']),
						  $p_header['extra_len']);

    // ----- Write the first 148 bytes of the header in the archive
    fputs($this->zip_fd, $v_binary_data, 30);

    // ----- Write the variable fields
    if (strlen($p_header['stored_filename']) != 0)
    {
      fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
    }
    if ($p_header['extra_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privWriteCentralFileHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privWriteCentralFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteCentralFileHeader", 'file="'.$p_header['filename'].'", stored as "'.$p_header['stored_filename'].'"');
    $v_result=1;

    // TBC
    //for(reset($p_header); $key = key($p_header); next($p_header)) {
    //  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "header[$key] = ".$p_header[$key]);
    //}

    // ----- Transform UNIX mtime to DOS format mdate/mtime
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    $v_date = getdate($p_header['mtime']);
    $v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
    $v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

    // ----- Packed data
    $v_binary_data = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
	                      $p_header['version'], $p_header['version_extracted'],
                          $p_header['flag'], $p_header['compression'],
						  $v_mtime, $v_mdate, $p_header['crc'],
                          $p_header['compressed_size'], $p_header['size'],
                          strlen($p_header['stored_filename']),
						  $p_header['extra_len'], $p_header['comment_len'],
                          $p_header['disk'], $p_header['internal'],
						  $p_header['external'], $p_header['offset']);

    // ----- Write the 42 bytes of the header in the zip file
    fputs($this->zip_fd, $v_binary_data, 46);

    // ----- Write the variable fields
    if (strlen($p_header['stored_filename']) != 0)
    {
      fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
    }
    if ($p_header['extra_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
    }
    if ($p_header['comment_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['comment'], $p_header['comment_len']);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privWriteCentralHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privWriteCentralHeader($p_nb_entries, $p_size, $p_offset, $p_comment)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteCentralHeader", 'nb_entries='.$p_nb_entries.', size='.$p_size.', offset='.$p_offset.', comment="'.$p_comment.'"');
    $v_result=1;

    // ----- Packed data
    $v_binary_data = pack("VvvvvVVv", 0x06054b50, 0, 0, $p_nb_entries,
	                      $p_nb_entries, $p_size,
						  $p_offset, strlen($p_comment));

    // ----- Write the 22 bytes of the header in the zip file
    fputs($this->zip_fd, $v_binary_data, 22);

    // ----- Write the variable fields
    if (strlen($p_comment) != 0)
    {
      fputs($this->zip_fd, $p_comment, strlen($p_comment));
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privList()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privList(&$p_list)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privList", "list");
    $v_result=1;

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
    {
      // ----- Magic quotes trick
      $this->privSwapBackMagicQuotes();
      
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of Central Dir
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Offset : ".$v_central_dir['offset']."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");
    if (@fseek($this->zip_fd, $v_central_dir['offset']))
    {
      $this->privSwapBackMagicQuotes();

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Read each entry
    for ($i=0; $i<$v_central_dir['entries']; $i++)
    {
      // ----- Read the file header
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      {
        $this->privSwapBackMagicQuotes();
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }
      $v_header['index'] = $i;

      // ----- Get the only interesting attributes
      $this->privConvertHeader2FileInfo($v_header, $p_list[$i]);
      unset($v_header);
    }

    // ----- Close the zip file
    $this->privCloseFd();

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privConvertHeader2FileInfo()
  // Description :
  //   This function takes the file informations from the central directory
  //   entries and extract the interesting parameters that will be given back.
  //   The resulting file infos are set in the array $p_info
  //     $p_info['filename'] : Filename with full path. Given by user (add),
  //                           extracted in the filesystem (extract).
  //     $p_info['stored_filename'] : Stored filename in the archive.
  //     $p_info['size'] = Size of the file.
  //     $p_info['compressed_size'] = Compressed size of the file.
  //     $p_info['mtime'] = Last modification date of the file.
  //     $p_info['comment'] = Comment associated with the file.
  //     $p_info['folder'] = true/false : indicates if the entry is a folder or not.
  //     $p_info['status'] = status of the action on the file.
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privConvertHeader2FileInfo($p_header, &$p_info)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privConvertHeader2FileInfo", "Filename='".$p_header['filename']."'");
    $v_result=1;

    // ----- Get the interesting attributes
    $p_info['filename'] = $p_header['filename'];
    $p_info['stored_filename'] = $p_header['stored_filename'];
    $p_info['size'] = $p_header['size'];
    $p_info['compressed_size'] = $p_header['compressed_size'];
    $p_info['mtime'] = $p_header['mtime'];
    $p_info['comment'] = $p_header['comment'];
    $p_info['folder'] = (($p_header['external']&0x00000010)==0x00000010);
    $p_info['index'] = $p_header['index'];
    $p_info['status'] = $p_header['status'];

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privExtractByRule()
  // Description :
  //   Extract a file or directory depending of rules (by index, by name, ...)
  // Parameters :
  //   $p_file_list : An array where will be placed the properties of each
  //                  extracted file
  //   $p_path : Path to add while writing the extracted files
  //   $p_remove_path : Path to remove (from the file memorized path) while writing the
  //                    extracted files. If the path does not match the file path,
  //                    the file is extracted with its memorized path.
  //                    $p_remove_path does not apply to 'list' mode.
  //                    $p_path and $p_remove_path are commulative.
  // Return Values :
  //   1 on success,0 or less on error (see error code list)
  // --------------------------------------------------------------------------------
  function privExtractByRule(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privExtractByRule", "path='$p_path', remove_path='$p_remove_path', remove_all_path='".($p_remove_all_path?'true':'false')."'");
    $v_result=1;

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Check the path
    if (   ($p_path == "")
	    || (   (substr($p_path, 0, 1) != "/")
		    && (substr($p_path, 0, 3) != "../")
			&& (substr($p_path,1,2)!=":/")))
      $p_path = "./".$p_path;

    // ----- Reduce the path last (and duplicated) '/'
    if (($p_path != "./") && ($p_path != "/"))
    {
      // ----- Look for the path end '/'
      while (substr($p_path, -1) == "/")
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Destination path [$p_path] ends by '/'");
        $p_path = substr($p_path, 0, strlen($p_path)-1);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Modified to [$p_path]");
      }
    }

    // ----- Look for path to remove format (should end by /)
    if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/'))
    {
      $p_remove_path .= '/';
    }
    $p_remove_path_size = strlen($p_remove_path);

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result = $this->privOpenFd('rb')) != 1)
    {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      // ----- Close the zip file
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Start at beginning of Central Dir
    $v_pos_entry = $v_central_dir['offset'];

    // ----- Read each entry
    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Read next file header entry : '$i'");

      // ----- Read next Central dir entry
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Position before rewind : ".ftell($this->zip_fd)."'");
      @rewind($this->zip_fd);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Position after rewind : ".ftell($this->zip_fd)."'");
      if (@fseek($this->zip_fd, $v_pos_entry))
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position after fseek : ".ftell($this->zip_fd)."'");

      // ----- Read the file header
      $v_header = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      // ----- Store the index
      $v_header['index'] = $i;

      // ----- Store the file position
      $v_pos_entry = ftell($this->zip_fd);

      // ----- Look for the specific extract rules
      $v_extract = false;

      // ----- Look for extract by name rule
      if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
          && ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByName'");

          // ----- Look if the filename is in the list
          for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_extract); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Compare with file '".$p_options[PCLZIP_OPT_BY_NAME][$j]."'");

              // ----- Look for a directory
              if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The searched item is a directory");

                  // ----- Look if the directory is in the filename path
                  if (   (strlen($v_header['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
                      && (substr($v_header['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The directory is in the file path");
                      $v_extract = true;
                  }
              }
              // ----- Look for a filename
              elseif ($v_header['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The file is the right one.");
                  $v_extract = true;
              }
          }
      }

      // ----- Look for extract by ereg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
               && ($p_options[PCLZIP_OPT_BY_EREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract by ereg '".$p_options[PCLZIP_OPT_BY_EREG]."'");

          if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_extract = true;
          }
      }

      // ----- Look for extract by preg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
               && ($p_options[PCLZIP_OPT_BY_PREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByEreg'");

          if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_extract = true;
          }
      }

      // ----- Look for extract by index rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
               && ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByIndex'");
          
          // ----- Look if the index is in the list
          for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_extract); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look if index '$i' is in [".$p_options[PCLZIP_OPT_BY_INDEX][$j]['start'].",".$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']."]");

              if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found as part of an index range");
                  $v_extract = true;
              }
              if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Do not look this index range for next loop");
                  $j_start = $j+1;
              }

              if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Index range is greater than index, stop loop");
                  break;
              }
          }
      }

      // ----- Look for no rule, which means extract all the archive
      else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with no rule (extract all)");
          $v_extract = true;
      }

	  // ----- Check compression method
	  if (   ($v_extract)
	      && (   ($v_header['compression'] != 8)
		      && ($v_header['compression'] != 0))) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unsupported compression method (".$v_header['compression'].")");
          $v_header['status'] = 'unsupported_compression';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

              $this->privSwapBackMagicQuotes();
              
              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_COMPRESSION,
			                       "Filename '".$v_header['stored_filename']."' is "
				  	    	  	   ."compressed by an unsupported compression "
				  	    	  	   ."method (".$v_header['compression'].") ");

              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
		  }
	  }
	  
	  // ----- Check encrypted files
	  if (($v_extract) && (($v_header['flag'] & 1) == 1)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unsupported file encryption");
          $v_header['status'] = 'unsupported_encryption';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

              $this->privSwapBackMagicQuotes();

              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION,
			                       "Unsupported encryption for "
				  	    	  	   ." filename '".$v_header['stored_filename']
								   ."'");

              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
		  }
    }

      // ----- Look for real extraction
      if (($v_extract) && ($v_header['status'] != 'ok')) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "No need for extract");
          $v_result = $this->privConvertHeader2FileInfo($v_header,
		                                        $p_file_list[$v_nb_extracted++]);
          if ($v_result != 1) {
              $this->privCloseFd();
              $this->privSwapBackMagicQuotes();
              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
              return $v_result;
          }

          $v_extract = false;
      }
      
      // ----- Look for real extraction
      if ($v_extract)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file '".$v_header['filename']."', index '$i'");

        // ----- Go to the file position
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
        @rewind($this->zip_fd);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
        if (@fseek($this->zip_fd, $v_header['offset']))
        {
          // ----- Close the zip file
          $this->privCloseFd();

          $this->privSwapBackMagicQuotes();

          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
        }
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

        // ----- Look for extraction as string
        if ($p_options[PCLZIP_OPT_EXTRACT_AS_STRING]) {

          // ----- Extracting the file
          $v_result1 = $this->privExtractFileAsString($v_header, $v_string);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Set the file content
          $p_file_list[$v_nb_extracted]['content'] = $v_string;

          // ----- Next extracted file
          $v_nb_extracted++;
          
          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for extraction in standard output
        elseif (   (isset($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]))
		        && ($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT])) {
          // ----- Extracting the file in standard output
          $v_result1 = $this->privExtractFileInOutput($v_header, $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for normal extraction
        else {
          // ----- Extracting the file
          $v_result1 = $this->privExtractFile($v_header,
		                                      $p_path, $p_remove_path,
											  $p_remove_all_path,
											  $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
      }
    }

    // ----- Close the zip file
    $this->privCloseFd();
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privExtractFile()
  // Description :
  // Parameters :
  // Return Values :
  //
  // 1 : ... ?
  // PCLZIP_ERR_USER_ABORTED(2) : User ask for extraction stop in callback
  // --------------------------------------------------------------------------------
  function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFile', "path='$p_path', remove_path='$p_remove_path', remove_all_path='".($p_remove_all_path?'true':'false')."'");
    $v_result=1;

    // ----- Read the file header
    if (($v_result = $this->privReadFileHeader($v_header)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    // ----- Look for all path to remove
    if ($p_remove_all_path == true) {
        // ----- Look for folder entry that not need to be extracted
        if (($p_entry['external']&0x00000010)==0x00000010) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The entry is a folder : need to be filtered");

            $p_entry['status'] = "filtered";

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "All path is removed");
        // ----- Get the basename of the path
        $p_entry['filename'] = basename($p_entry['filename']);
    }

    // ----- Look for path to remove
    else if ($p_remove_path != "")
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look for some path to remove");
      if (PclZipUtilPathInclusion($p_remove_path, $p_entry['filename']) == 2)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The folder is the same as the removed path '".$p_entry['filename']."'");

        // ----- Change the file status
        $p_entry['status'] = "filtered";

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      $p_remove_path_size = strlen($p_remove_path);
      if (substr($p_entry['filename'], 0, $p_remove_path_size) == $p_remove_path)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found path '$p_remove_path' to remove in file '".$p_entry['filename']."'");

        // ----- Remove the path
        $p_entry['filename'] = substr($p_entry['filename'], $p_remove_path_size);

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Resulting file is '".$p_entry['filename']."'");
      }
    }

    // ----- Add the path
    if ($p_path != '') {
      $p_entry['filename'] = $p_path."/".$p_entry['filename'];
    }
    
    // ----- Check a base_dir_restriction
    if (isset($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Check the extract directory restriction");
      $v_inclusion
      = PclZipUtilPathInclusion($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION],
                                $p_entry['filename']); 
      if ($v_inclusion == 0) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_EXTRACT_DIR_RESTRICTION is selected, file is outside restriction");

        PclZip::privErrorLog(PCLZIP_ERR_DIRECTORY_RESTRICTION,
			                     "Filename '".$p_entry['filename']."' is "
								 ."outside PCLZIP_OPT_EXTRACT_DIR_RESTRICTION");

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
    }

    // ----- Look for pre-extract callback
    if (isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_EXTRACT].'(PCLZIP_CB_PRE_EXTRACT, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_entry['status'] = "skipped";
        $v_result = 1;
      }
      
      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
        // ----- This status is internal and will be changed in 'skipped'
        $p_entry['status'] = "aborted";
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }

      // ----- Update the informations
      // Only some fields can be modified
      $p_entry['filename'] = $v_local_header['filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New filename is '".$p_entry['filename']."'");
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

    // ----- Look for specific actions while the file exist
    if (file_exists($p_entry['filename']))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$p_entry['filename']."' already exists");

      // ----- Look if file is a directory
      if (is_dir($p_entry['filename']))
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is a directory");

        // ----- Change the file status
        $p_entry['status'] = "already_a_directory";
        
        // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
        // For historical reason first PclZip implementation does not stop
        // when this kind of error occurs.
        if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		    && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

            PclZip::privErrorLog(PCLZIP_ERR_ALREADY_A_DIRECTORY,
			                     "Filename '".$p_entry['filename']."' is "
								 ."already used by an existing directory");

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
		}
      }
      // ----- Look if file is write protected
      else if (!is_writeable($p_entry['filename']))
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is write protected");

        // ----- Change the file status
        $p_entry['status'] = "write_protected";

        // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
        // For historical reason first PclZip implementation does not stop
        // when this kind of error occurs.
        if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		    && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

            PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL,
			                     "Filename '".$p_entry['filename']."' exists "
								 ."and is write protected");

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
		}
      }

      // ----- Look if the extracted file is older
      else if (filemtime($p_entry['filename']) > $p_entry['mtime'])
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is newer (".date("l dS of F Y h:i:s A", filemtime($p_entry['filename'])).") than the extracted file (".date("l dS of F Y h:i:s A", $p_entry['mtime']).")");
        // ----- Change the file status
        if (   (isset($p_options[PCLZIP_OPT_REPLACE_NEWER]))
		    && ($p_options[PCLZIP_OPT_REPLACE_NEWER]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_REPLACE_NEWER is selected, file will be replaced");
		}
		else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will not be replaced");
            $p_entry['status'] = "newer_exist";

            // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
            // For historical reason first PclZip implementation does not stop
            // when this kind of error occurs.
            if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		        && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
                //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

                PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL,
			             "Newer version of '".$p_entry['filename']."' exists "
					    ."and option PCLZIP_OPT_REPLACE_NEWER is not selected");

                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                return PclZip::errorCode();
		    }
		}
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is older than the extrated one - will be replaced by the extracted one (".date("l dS of F Y h:i:s A", filemtime($p_entry['filename'])).") than the extracted file (".date("l dS of F Y h:i:s A", $p_entry['mtime']).")");
      }
    }

    // ----- Check the directory availability and create it if necessary
    else {
      if ((($p_entry['external']&0x00000010)==0x00000010) || (substr($p_entry['filename'], -1) == '/'))
        $v_dir_to_check = $p_entry['filename'];
      else if (!strstr($p_entry['filename'], "/"))
        $v_dir_to_check = "";
      else
        $v_dir_to_check = dirname($p_entry['filename']);

      if (($v_result = $this->privDirCheck($v_dir_to_check, (($p_entry['external']&0x00000010)==0x00000010))) != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to create path for '".$p_entry['filename']."'");

        // ----- Change the file status
        $p_entry['status'] = "path_creation_fail";

        // ----- Return
        ////--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        //return $v_result;
        $v_result = 1;
      }
    }
    }

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

      // ----- Do the extraction (if not a folder)
      if (!(($p_entry['external']&0x00000010)==0x00000010))
      {
        // ----- Look for not compressed file
        if ($p_entry['compression'] == 0) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");

		  // ----- Opening destination file
          if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0)
          {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Error while opening '".$p_entry['filename']."' in write binary mode");

            // ----- Change the file status
            $p_entry['status'] = "write_error";

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Read '".$p_entry['size']."' bytes");

          // ----- Read the file by PCLZIP_READ_BLOCK_SIZE octets blocks
          $v_size = $p_entry['compressed_size'];
          while ($v_size != 0)
          {
            $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Read $v_read_size bytes");
            $v_buffer = @fread($this->zip_fd, $v_read_size);
            /* Try to speed up the code
            $v_binary_data = pack('a'.$v_read_size, $v_buffer);
            @fwrite($v_dest_file, $v_binary_data, $v_read_size);
            */
            @fwrite($v_dest_file, $v_buffer, $v_read_size);            
            $v_size -= $v_read_size;
          }

          // ----- Closing the destination file
          fclose($v_dest_file);

          // ----- Change the file mtime
          touch($p_entry['filename'], $p_entry['mtime']);
          

        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file (Compression method ".$p_entry['compression'].")");
          // ----- TBC
          // Need to be finished
          if (($p_entry['flag'] & 1) == 1) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File is encrypted");
            /*
              // ----- Read the encryption header
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read 12 encryption header bytes");
              $v_encryption_header = @fread($this->zip_fd, 12);
              
              // ----- Read the encrypted & compressed file in a buffer
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read '".($p_entry['compressed_size']-12)."' compressed & encrypted bytes");
              $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']-12);
              
              // ----- Decrypt the buffer
              $this->privDecrypt($v_encryption_header, $v_buffer,
			                     $p_entry['compressed_size']-12, $p_entry['crc']);
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Buffer is '".$v_buffer."'");
              */
          }
          else {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read '".$p_entry['compressed_size']."' compressed bytes");
              // ----- Read the compressed file in a buffer (one shot)
              $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);
          }
          
          // ----- Decompress the file
          $v_file_content = @gzinflate($v_buffer);
          unset($v_buffer);
          if ($v_file_content === FALSE) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to inflate compressed file");

            // ----- Change the file status
            // TBC
            $p_entry['status'] = "error";
            
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }
          
          // ----- Opening destination file
          if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Error while opening '".$p_entry['filename']."' in write binary mode");

            // ----- Change the file status
            $p_entry['status'] = "write_error";

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Write the uncompressed data
          @fwrite($v_dest_file, $v_file_content, $p_entry['size']);
          unset($v_file_content);

          // ----- Closing the destination file
          @fclose($v_dest_file);

          // ----- Change the file mtime
          @touch($p_entry['filename'], $p_entry['mtime']);
        }

        // ----- Look for chmod option
        if (isset($p_options[PCLZIP_OPT_SET_CHMOD])) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "chmod option activated '".$p_options[PCLZIP_OPT_SET_CHMOD]."'");

          // ----- Change the mode of the file
          @chmod($p_entry['filename'], $p_options[PCLZIP_OPT_SET_CHMOD]);
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
      }
    }

	// ----- Change abort status
	if ($p_entry['status'] == "aborted") {
      $p_entry['status'] = "skipped";
	}
	
    // ----- Look for post-extract callback
    elseif (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_EXTRACT].'(PCLZIP_CB_POST_EXTRACT, $v_local_header);');

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privExtractFileInOutput()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privExtractFileInOutput(&$p_entry, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFileInOutput', "");
    $v_result=1;

    // ----- Read the file header
    if (($v_result = $this->privReadFileHeader($v_header)) != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    // ----- Look for pre-extract callback
    if (isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_EXTRACT].'(PCLZIP_CB_PRE_EXTRACT, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_entry['status'] = "skipped";
        $v_result = 1;
      }

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
        // ----- This status is internal and will be changed in 'skipped'
        $p_entry['status'] = "aborted";
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }

      // ----- Update the informations
      // Only some fields can be modified
      $p_entry['filename'] = $v_local_header['filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New filename is '".$p_entry['filename']."'");
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

      // ----- Do the extraction (if not a folder)
      if (!(($p_entry['external']&0x00000010)==0x00000010)) {
        // ----- Look for not compressed file
        if ($p_entry['compressed_size'] == $p_entry['size']) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Reading '".$p_entry['size']."' bytes");

          // ----- Read the file in a buffer (one shot)
          $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);

          // ----- Send the file to the output
          echo $v_buffer;
          unset($v_buffer);
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file");
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Reading '".$p_entry['size']."' bytes");

          // ----- Read the compressed file in a buffer (one shot)
          $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);
          
          // ----- Decompress the file
          $v_file_content = gzinflate($v_buffer);
          unset($v_buffer);

          // ----- Send the file to the output
          echo $v_file_content;
          unset($v_file_content);
        }
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
      }
    }

	// ----- Change abort status
	if ($p_entry['status'] == "aborted") {
      $p_entry['status'] = "skipped";
	}

    // ----- Look for post-extract callback
    elseif (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_EXTRACT].'(PCLZIP_CB_POST_EXTRACT, $v_local_header);');

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }
    }

    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privExtractFileAsString()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privExtractFileAsString(&$p_entry, &$p_string)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFileAsString', "p_entry['filename']='".$p_entry['filename']."'");
    $v_result=1;

    // ----- Read the file header
    $v_header = array();
    if (($v_result = $this->privReadFileHeader($v_header)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file in string (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Do the extraction (if not a folder)
    if (!(($p_entry['external']&0x00000010)==0x00000010))
    {
      // ----- Look for not compressed file
//      if ($p_entry['compressed_size'] == $p_entry['size'])
      if ($p_entry['compression'] == 0) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Reading '".$p_entry['size']."' bytes");

        // ----- Reading the file
        $p_string = @fread($this->zip_fd, $p_entry['compressed_size']);
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file (compression method '".$p_entry['compression']."')");

        // ----- Reading the file
        $v_data = @fread($this->zip_fd, $p_entry['compressed_size']);
        
        // ----- Decompress the file
        if (($p_string = @gzinflate($v_data)) === FALSE) {
            // TBC
        }
      }

      // ----- Trace
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
    }
    else {
        // TBC : error : can not extract a folder in a string
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privReadFileHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privReadFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadFileHeader", "");
    $v_result=1;

    // ----- Read the 4 bytes signature
    $v_binary_data = @fread($this->zip_fd, 4);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
    $v_data = unpack('Vid', $v_binary_data);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

    // ----- Check signature
    if ($v_data['id'] != 0x04034b50)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid File header");

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the first 42 bytes of the header
    $v_binary_data = fread($this->zip_fd, 26);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 26)
    {
      $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid block size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Header : '".$v_binary_data."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Header (Hex) : '".bin2hex($v_binary_data)."'");
    $v_data = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $v_binary_data);

    // ----- Get filename
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "File name length : ".$v_data['filename_len']);
    $p_header['filename'] = fread($this->zip_fd, $v_data['filename_len']);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Filename : \''.$p_header['filename'].'\'');

    // ----- Get extra_fields
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extra field length : ".$v_data['extra_len']);
    if ($v_data['extra_len'] != 0) {
      $p_header['extra'] = fread($this->zip_fd, $v_data['extra_len']);
    }
    else {
      $p_header['extra'] = '';
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Extra field : \''.bin2hex($p_header['extra']).'\'');

    // ----- Extract properties
    $p_header['version_extracted'] = $v_data['version'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Version need to extract : ('.$p_header['version_extracted'].') \''.($p_header['version_extracted']/10).'.'.($p_header['version_extracted']%10).'\'');
    $p_header['compression'] = $v_data['compression'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compression method : \''.$p_header['compression'].'\'');
    $p_header['size'] = $v_data['size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size : \''.$p_header['size'].'\'');
    $p_header['compressed_size'] = $v_data['compressed_size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compressed Size : \''.$p_header['compressed_size'].'\'');
    $p_header['crc'] = $v_data['crc'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'CRC : \''.sprintf("0x%X", $p_header['crc']).'\'');
    $p_header['flag'] = $v_data['flag'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Flag : \''.$p_header['flag'].'\'');
    $p_header['filename_len'] = $v_data['filename_len'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Filename_len : \''.$p_header['filename_len'].'\'');

    // ----- Recuperate date in UNIX format
    $p_header['mdate'] = $v_data['mdate'];
    $p_header['mtime'] = $v_data['mtime'];
    if ($p_header['mdate'] && $p_header['mtime'])
    {
      // ----- Extract time
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;

      // ----- Extract date
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;

      // ----- Get UNIX date format
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }
    else
    {
      $p_header['mtime'] = time();
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date is actual : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }

    // TBC
    //for(reset($v_data); $key = key($v_data); next($v_data)) {
    //  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Attribut[$key] = ".$v_data[$key]);
    //}

    // ----- Set the stored filename
    $p_header['stored_filename'] = $p_header['filename'];

    // ----- Set the status field
    $p_header['status'] = "ok";

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privReadCentralFileHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privReadCentralFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadCentralFileHeader", "");
    $v_result=1;

    // ----- Read the 4 bytes signature
    $v_binary_data = @fread($this->zip_fd, 4);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
    $v_data = unpack('Vid', $v_binary_data);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

    // ----- Check signature
    if ($v_data['id'] != 0x02014b50)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid Central Dir File signature");

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the first 42 bytes of the header
    $v_binary_data = fread($this->zip_fd, 42);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 42)
    {
      $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid block size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header : '".$v_binary_data."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header (Hex) : '".bin2hex($v_binary_data)."'");
    $p_header = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $v_binary_data);

    // ----- Get filename
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "File name length : ".$p_header['filename_len']);
    if ($p_header['filename_len'] != 0)
      $p_header['filename'] = fread($this->zip_fd, $p_header['filename_len']);
    else
      $p_header['filename'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Filename : \''.$p_header['filename'].'\'');

    // ----- Get extra
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Extra length : ".$p_header['extra_len']);
    if ($p_header['extra_len'] != 0)
      $p_header['extra'] = fread($this->zip_fd, $p_header['extra_len']);
    else
      $p_header['extra'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Extra : \''.$p_header['extra'].'\'');

    // ----- Get comment
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Comment length : ".$p_header['comment_len']);
    if ($p_header['comment_len'] != 0)
      $p_header['comment'] = fread($this->zip_fd, $p_header['comment_len']);
    else
      $p_header['comment'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Comment : \''.$p_header['comment'].'\'');

    // ----- Extract properties
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Version : \''.($p_header['version']/10).'.'.($p_header['version']%10).'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Version need to extract : \''.($p_header['version_extracted']/10).'.'.($p_header['version_extracted']%10).'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Size : \''.$p_header['size'].'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Compressed Size : \''.$p_header['compressed_size'].'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'CRC : \''.sprintf("0x%X", $p_header['crc']).'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Flag : \''.$p_header['flag'].'\'');
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Offset : \''.$p_header['offset'].'\'');

    // ----- Recuperate date in UNIX format
    if ($p_header['mdate'] && $p_header['mtime'])
    {
      // ----- Extract time
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;

      // ----- Extract date
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;

      // ----- Get UNIX date format
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }
    else
    {
      $p_header['mtime'] = time();
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Date is actual : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }

    // ----- Set the stored filename
    $p_header['stored_filename'] = $p_header['filename'];

    // ----- Set default status to ok
    $p_header['status'] = 'ok';

    // ----- Look if it is a directory
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Internal (Hex) : '".sprintf("Ox%04X", $p_header['internal'])."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "External (Hex) : '".sprintf("Ox%04X", $p_header['external'])."' (".(($p_header['external']&0x00000010)==0x00000010?'is a folder':'is a file').')');
    if (substr($p_header['filename'], -1) == '/') {
      //$p_header['external'] = 0x41FF0010;
      $p_header['external'] = 0x00000010;
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Force folder external : \''.sprintf("Ox%04X", $p_header['external']).'\'');
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Header of filename : \''.$p_header['filename'].'\'');

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privCheckFileHeaders()
  // Description :
  // Parameters :
  // Return Values :
  //   1 on success,
  //   0 on error;
  // --------------------------------------------------------------------------------
  function privCheckFileHeaders(&$p_local_header, &$p_central_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCheckFileHeaders", "");
    $v_result=1;

	// ----- Check the static values
	// TBC
	if ($p_local_header['filename'] != $p_central_header['filename']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "filename" : TBC To Be Completed');
	}
	if ($p_local_header['version_extracted'] != $p_central_header['version_extracted']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "version_extracted" : TBC To Be Completed');
	}
	if ($p_local_header['flag'] != $p_central_header['flag']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "flag" : TBC To Be Completed');
	}
	if ($p_local_header['compression'] != $p_central_header['compression']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "compression" : TBC To Be Completed');
	}
	if ($p_local_header['mtime'] != $p_central_header['mtime']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "mtime" : TBC To Be Completed');
	}
	if ($p_local_header['filename_len'] != $p_central_header['filename_len']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "filename_len" : TBC To Be Completed');
	}

	// ----- Look for flag bit 3
	if (($p_local_header['flag'] & 8) == 8) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Purpose bit flag bit 3 set !');
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'File size, compression size and crc found in central header');
        $p_local_header['size'] = $p_central_header['size'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size : \''.$p_local_header['size'].'\'');
        $p_local_header['compressed_size'] = $p_central_header['compressed_size'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compressed Size : \''.$p_local_header['compressed_size'].'\'');
        $p_local_header['crc'] = $p_central_header['crc'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'CRC : \''.sprintf("0x%X", $p_local_header['crc']).'\'');
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privReadEndCentralDir()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privReadEndCentralDir(&$p_central_dir)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadEndCentralDir", "");
    $v_result=1;

    // ----- Go to the end of the zip file
    $v_size = filesize($this->zipname);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Size of the file :$v_size");
    @fseek($this->zip_fd, $v_size);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position at end of zip file : \''.ftell($this->zip_fd).'\'');
    if (@ftell($this->zip_fd) != $v_size)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to go to the end of the archive \''.$this->zipname.'\'');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- First try : look if this is an archive with no commentaries (most of the time)
    // in this case the end of central dir is at 22 bytes of the file end
    $v_found = 0;
    if ($v_size > 26) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Look for central dir with no comment');
      @fseek($this->zip_fd, $v_size-22);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position after min central position : \''.ftell($this->zip_fd).'\'');
      if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Read for bytes
      $v_binary_data = @fread($this->zip_fd, 4);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
      $v_data = @unpack('Vid', $v_binary_data);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

      // ----- Check signature
      if ($v_data['id'] == 0x06054b50) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found central dir at the default position.");
        $v_found = 1;
      }

      $v_pos = ftell($this->zip_fd);
    }

    // ----- Go back to the maximum possible size of the Central Dir End Record
    if (!$v_found) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Start extended search of end central dir');
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      @fseek($this->zip_fd, $v_size-$v_maximum_size);
      if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position after max central position : \''.ftell($this->zip_fd).'\'');

      // ----- Read byte per byte in order to find the signature
      $v_pos = ftell($this->zip_fd);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      {
        // ----- Read a byte
        $v_byte = @fread($this->zip_fd, 1);

        // -----  Add the byte
        $v_bytes = ($v_bytes << 8) | Ord($v_byte);

        // ----- Compare the bytes
        if ($v_bytes == 0x504b0506)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Found End Central Dir signature at position : \''.ftell($this->zip_fd).'\'');
          $v_pos++;
          break;
        }

        $v_pos++;
      }

      // ----- Look if not found end of central dir
      if ($v_pos == $v_size)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to find End of Central Dir Record signature");

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
    }

    // ----- Read the first 18 bytes of the header
    $v_binary_data = fread($this->zip_fd, 18);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 18)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Central Dir Record : '".$v_binary_data."'");
    ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Central Dir Record (Hex) : '".bin2hex($v_binary_data)."'");
    $v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);

    // ----- Check the global size
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Comment length : ".$v_data['comment_size']);
    if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The central dir is not at the end of the archive. Some trailing bytes exists after the archive.");

	  // ----- Removed in release 2.2 see readme file
	  // The check of the file size is a little too strict.
	  // Some bugs where found when a zip is encrypted/decrypted with 'crypt'.
	  // While decrypted, zip has training 0 bytes
	  if (0) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
	                       'The central dir is not at the end of the archive.'
						   .' Some trailing bytes exists after the archive.');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
	  }
    }

    // ----- Get comment
    if ($v_data['comment_size'] != 0)
      $p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
    else
      $p_central_dir['comment'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Comment : \''.$p_central_dir['comment'].'\'');

    $p_central_dir['entries'] = $v_data['entries'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Nb of entries : \''.$p_central_dir['entries'].'\'');
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Nb of entries for this disk : \''.$p_central_dir['disk_entries'].'\'');
    $p_central_dir['offset'] = $v_data['offset'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Offset of Central Dir : \''.$p_central_dir['offset'].'\'');
    $p_central_dir['size'] = $v_data['size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size of Central Dir : \''.$p_central_dir['size'].'\'');
    $p_central_dir['disk'] = $v_data['disk'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Disk number : \''.$p_central_dir['disk'].'\'');
    $p_central_dir['disk_start'] = $v_data['disk_start'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Start disk number : \''.$p_central_dir['disk_start'].'\'');

    // TBC
    //for(reset($p_central_dir); $key = key($p_central_dir); next($p_central_dir)) {
    //  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "central_dir[$key] = ".$p_central_dir[$key]);
    //}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privDeleteByRule()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privDeleteByRule(&$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDeleteByRule", "");
    $v_result=1;
    $v_list_detail = array();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Scan all the files
    // ----- Start at beginning of Central Dir
    $v_pos_entry = $v_central_dir['offset'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
    if (@fseek($this->zip_fd, $v_pos_entry))
    {
      // ----- Close the zip file
      $this->privCloseFd();

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

    // ----- Read each entry
    $v_header_list = array();
    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Read next file header entry (index '$i')");

      // ----- Read the file header
      $v_header_list[$v_nb_extracted] = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header_list[$v_nb_extracted])) != 1)
      {
        // ----- Close the zip file
        $this->privCloseFd();

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename (index '$i') : '".$v_header_list[$v_nb_extracted]['stored_filename']."'");

      // ----- Store the index
      $v_header_list[$v_nb_extracted]['index'] = $i;

      // ----- Look for the specific extract rules
      $v_found = false;

      // ----- Look for extract by name rule
      if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
          && ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByName'");

          // ----- Look if the filename is in the list
          for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_found); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Compare with file '".$p_options[PCLZIP_OPT_BY_NAME][$j]."'");

              // ----- Look for a directory
              if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The searched item is a directory");

                  // ----- Look if the directory is in the filename path
                  if (   (strlen($v_header_list[$v_nb_extracted]['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
                      && (substr($v_header_list[$v_nb_extracted]['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The directory is in the file path");
                      $v_found = true;
                  }
                  elseif (   (($v_header_list[$v_nb_extracted]['external']&0x00000010)==0x00000010) /* Indicates a folder */
                          && ($v_header_list[$v_nb_extracted]['stored_filename'].'/' == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The entry is the searched directory");
                      $v_found = true;
                  }
              }
              // ----- Look for a filename
              elseif ($v_header_list[$v_nb_extracted]['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The file is the right one.");
                  $v_found = true;
              }
          }
      }

      // ----- Look for extract by ereg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
               && ($p_options[PCLZIP_OPT_BY_EREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract by ereg '".$p_options[PCLZIP_OPT_BY_EREG]."'");

          if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header_list[$v_nb_extracted]['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_found = true;
          }
      }

      // ----- Look for extract by preg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
               && ($p_options[PCLZIP_OPT_BY_PREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByEreg'");

          if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header_list[$v_nb_extracted]['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_found = true;
          }
      }

      // ----- Look for extract by index rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
               && ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByIndex'");

          // ----- Look if the index is in the list
          for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_found); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look if index '$i' is in [".$p_options[PCLZIP_OPT_BY_INDEX][$j]['start'].",".$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']."]");

              if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found as part of an index range");
                  $v_found = true;
              }
              if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Do not look this index range for next loop");
                  $j_start = $j+1;
              }

              if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Index range is greater than index, stop loop");
                  break;
              }
          }
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "No argument mean remove all file");
      	$v_found = true;
      }

      // ----- Look for deletion
      if ($v_found)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_header_list[$v_nb_extracted]['stored_filename']."', index '$i' need to be deleted");
        unset($v_header_list[$v_nb_extracted]);
      }
      else
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_header_list[$v_nb_extracted]['stored_filename']."', index '$i' will not be deleted");
        $v_nb_extracted++;
      }
    }

    // ----- Look if something need to be deleted
    if ($v_nb_extracted > 0) {

        // ----- Creates a temporay file
        $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

        // ----- Creates a temporary zip archive
        $v_temp_zip = new PclZip($v_zip_temp_name);

        // ----- Open the temporary zip file in write mode
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary write mode");
        if (($v_result = $v_temp_zip->privOpenFd('wb')) != 1) {
            $this->privCloseFd();

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        // ----- Look which file need to be kept
        for ($i=0; $i<sizeof($v_header_list); $i++) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Keep entry index '$i' : '".$v_header_list[$i]['filename']."'");

            // ----- Calculate the position of the header
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset='". $v_header_list[$i]['offset']."'");
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
            @rewind($this->zip_fd);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
            if (@fseek($this->zip_fd,  $v_header_list[$i]['offset'])) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Error log
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                return PclZip::errorCode();
            }
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

            // ----- Read the file header
            $v_local_header = array();
            if (($v_result = $this->privReadFileHeader($v_local_header)) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
            
            // ----- Check that local file header is same as central file header
            if ($this->privCheckFileHeaders($v_local_header,
			                                $v_header_list[$i]) != 1) {
                // TBC
            }
            unset($v_local_header);

            // ----- Write the file header
            if (($v_result = $v_temp_zip->privWriteFileHeader($v_header_list[$i])) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset for this file is '".$v_header_list[$i]['offset']."'");

            // ----- Read/write the data block
            if (($v_result = PclZipUtilCopyBlock($this->zip_fd, $v_temp_zip->zip_fd, $v_header_list[$i]['compressed_size'])) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
        }

        // ----- Store the offset of the central dir
        $v_offset = @ftell($v_temp_zip->zip_fd);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "New offset of central dir : $v_offset");

        // ----- Re-Create the Central Dir files header
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Creates the new central directory");
        for ($i=0; $i<sizeof($v_header_list); $i++) {
            // ----- Create the file header
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset of file : ".$v_header_list[$i]['offset']);
            if (($v_result = $v_temp_zip->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
                $v_temp_zip->privCloseFd();
                $this->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }

            // ----- Transform the header to a 'usable' info
            $v_temp_zip->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Creates the central directory footer");

        // ----- Zip file comment
        $v_comment = '';
        if (isset($p_options[PCLZIP_OPT_COMMENT])) {
          $v_comment = $p_options[PCLZIP_OPT_COMMENT];
        }

        // ----- Calculate the size of the central header
        $v_size = @ftell($v_temp_zip->zip_fd)-$v_offset;

        // ----- Create the central dir footer
        if (($v_result = $v_temp_zip->privWriteCentralHeader(sizeof($v_header_list), $v_size, $v_offset, $v_comment)) != 1) {
            // ----- Reset the file list
            unset($v_header_list);
            $v_temp_zip->privCloseFd();
            $this->privCloseFd();
            @unlink($v_zip_temp_name);

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        // ----- Close
        $v_temp_zip->privCloseFd();
        $this->privCloseFd();

        // ----- Delete the zip file
        // TBC : I should test the result ...
        @unlink($this->zipname);

        // ----- Rename the temporary file
        // TBC : I should test the result ...
        //@rename($v_zip_temp_name, $this->zipname);
        PclZipUtilRename($v_zip_temp_name, $this->zipname);
    
        // ----- Destroy the temporary archive
        unset($v_temp_zip);
    }
    
    // ----- Remove every files : reset the file
    else if ($v_central_dir['entries'] != 0) {
        $this->privCloseFd();

        if (($v_result = $this->privOpenFd('wb')) != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        if (($v_result = $this->privWriteCentralHeader(0, 0, 0, '')) != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        $this->privCloseFd();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privDirCheck()
  // Description :
  //   Check if a directory exists, if not it creates it and all the parents directory
  //   which may be useful.
  // Parameters :
  //   $p_dir : Directory path to check.
  // Return Values :
  //    1 : OK
  //   -1 : Unable to create directory
  // --------------------------------------------------------------------------------
  function privDirCheck($p_dir, $p_is_dir=false)
  {
    $v_result = 1;

    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDirCheck", "entry='$p_dir', is_dir='".($p_is_dir?"true":"false")."'");

    // ----- Remove the final '/'
    if (($p_is_dir) && (substr($p_dir, -1)=='/'))
    {
      $p_dir = substr($p_dir, 0, strlen($p_dir)-1);
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Looking for entry '$p_dir'");

    // ----- Check the directory availability
    if ((is_dir($p_dir)) || ($p_dir == ""))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, "'$p_dir' is a directory");
      return 1;
    }

    // ----- Extract parent directory
    $p_parent_dir = dirname($p_dir);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Parent directory is '$p_parent_dir'");

    // ----- Just a check
    if ($p_parent_dir != $p_dir)
    {
      // ----- Look for parent directory
      if ($p_parent_dir != "")
      {
        if (($v_result = $this->privDirCheck($p_parent_dir)) != 1)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
      }
    }

    // ----- Create the directory
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Create directory '$p_dir'");
    if (!@mkdir($p_dir, 0777))
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_DIR_CREATE_FAIL, "Unable to create directory '$p_dir'");

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result, "Directory '$p_dir' created");
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privMerge()
  // Description :
  //   If $p_archive_to_add does not exist, the function exit with a success result.
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privMerge(&$p_archive_to_add)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privMerge", "archive='".$p_archive_to_add->zipname."'");
    $v_result=1;

    // ----- Look if the archive_to_add exists
    if (!is_file($p_archive_to_add->zipname))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive to add does not exist. End of merge.");

      // ----- Nothing to merge, so merge is a success
      $v_result = 1;

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Look if the archive exists
    if (!is_file($this->zipname))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive does not exist, duplicate the archive_to_add.");

      // ----- Do a duplicate
      $v_result = $this->privDuplicate($p_archive_to_add->zipname);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in zip : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in zip : ".ftell($this->zip_fd)."'");

    // ----- Open the archive_to_add file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open archive_to_add in binary read mode");
    if (($v_result=$p_archive_to_add->privOpenFd('rb')) != 1)
    {
      $this->privCloseFd();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir_to_add = array();
    if (($v_result = $p_archive_to_add->privReadEndCentralDir($v_central_dir_to_add)) != 1)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();

      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in archive_to_add : ".ftell($p_archive_to_add->zip_fd)."'");
    @rewind($p_archive_to_add->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in archive_to_add : ".ftell($p_archive_to_add->zip_fd)."'");

    // ----- Creates a temporay file
    $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = $v_central_dir['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Copy the files from the archive_to_add into the temporary file
    $v_size = $v_central_dir_to_add['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($p_archive_to_add->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($v_zip_temp_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "New offset of central dir : $v_offset");

    // ----- Copy the block of file headers from the old archive
    $v_size = $v_central_dir['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Copy the block of file headers from the archive_to_add
    $v_size = $v_central_dir_to_add['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($p_archive_to_add->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Merge the file comments
    $v_comment = $v_central_dir['comment'].' '.$v_central_dir_to_add['comment'];

    // ----- Calculate the size of the (new) central header
    $v_size = @ftell($v_zip_temp_fd)-$v_offset;

    // ----- Swap the file descriptor
    // Here is a trick : I swap the temporary fd with the zip fd, in order to use
    // the following methods on the temporary fil and not the real archive fd
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_central_dir['entries']+$v_central_dir_to_add['entries'], $v_size, $v_offset, $v_comment)) != 1)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();
      @fclose($v_zip_temp_fd);
      $this->zip_fd = null;

      // ----- Reset the file list
      unset($v_header_list);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Swap back the file descriptor
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Close
    $this->privCloseFd();
    $p_archive_to_add->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Delete the zip file
    // TBC : I should test the result ...
    @unlink($this->zipname);

    // ----- Rename the temporary file
    // TBC : I should test the result ...
    //@rename($v_zip_temp_name, $this->zipname);
    PclZipUtilRename($v_zip_temp_name, $this->zipname);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privDuplicate()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privDuplicate($p_archive_filename)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDuplicate", "archive_filename='$p_archive_filename'");
    $v_result=1;

    // ----- Look if the $p_archive_filename exists
    if (!is_file($p_archive_filename))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive to duplicate does not exist. End of duplicate.");

      // ----- Nothing to duplicate, so duplicate is a success.
      $v_result = 1;

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('wb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($p_archive_filename, 'rb')) == 0)
    {
      $this->privCloseFd();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive file \''.$p_archive_filename.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = filesize($p_archive_filename);
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read $v_read_size bytes");
      $v_buffer = fread($v_zip_temp_fd, $v_read_size);
      @fwrite($this->zip_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Close
    $this->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privErrorLog()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function privErrorLog($p_error_code=0, $p_error_string='')
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclError($p_error_code, $p_error_string);
    }
    else {
      $this->error_code = $p_error_code;
      $this->error_string = $p_error_string;
    }
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privErrorReset()
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function privErrorReset()
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclErrorReset();
    }
    else {
      $this->error_code = 0;
      $this->error_string = '';
    }
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privDecrypt()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privDecrypt($p_encryption_header, &$p_buffer, $p_size, $p_crc)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privDecrypt', "size=".$p_size."");
    $v_result=1;
    
    // ----- To Be Modified ;-)
    $v_pwd = "test";
    
    $p_buffer = PclZipUtilZipDecrypt($p_buffer, $p_size, $p_encryption_header,
	                                 $p_crc, $v_pwd);
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privDisableMagicQuotes()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privDisableMagicQuotes()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privDisableMagicQuotes', "");
    $v_result=1;

    // ----- Look if function exists
    if (   (!function_exists("get_magic_quotes_runtime"))
	    || (!function_exists("set_magic_quotes_runtime"))) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Functions *et_magic_quotes_runtime are not supported");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

    // ----- Look if already done
    if ($this->magic_quotes_status != -1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "magic_quote already disabled");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

	// ----- Get and memorize the magic_quote value
	$this->magic_quotes_status = @get_magic_quotes_runtime();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Current magic_quotes_runtime status is '".($this->magic_quotes_status==0?'disable':'enable')."'");

	// ----- Disable magic_quotes
	if ($this->magic_quotes_status == 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Disable magic_quotes");
	  @set_magic_quotes_runtime(0);
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : privSwapBackMagicQuotes()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function privSwapBackMagicQuotes()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privSwapBackMagicQuotes', "");
    $v_result=1;

    // ----- Look if function exists
    if (   (!function_exists("get_magic_quotes_runtime"))
	    || (!function_exists("set_magic_quotes_runtime"))) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Functions *et_magic_quotes_runtime are not supported");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

    // ----- Look if something to do
    if ($this->magic_quotes_status != -1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "magic_quote not modified");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

	// ----- Swap back magic_quotes
	if ($this->magic_quotes_status == 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Enable back magic_quotes");
  	  @set_magic_quotes_runtime($this->magic_quotes_status);
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  }
  // End of class
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilPathReduction()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PclZipUtilPathReduction($p_dir)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathReduction", "dir='$p_dir'");
    $v_result = "";

    // ----- Look for not empty path
    if ($p_dir != "") {
      // ----- Explode path by directory names
      $v_list = explode("/", $p_dir);

      // ----- Study directories from last to first
      $v_skip = 0;
      for ($i=sizeof($v_list)-1; $i>=0; $i--) {
        // ----- Look for current path
        if ($v_list[$i] == ".") {
          // ----- Ignore this directory
          // Should be the first $i=0, but no check is done
        }
        else if ($v_list[$i] == "..") {
		  $v_skip++;
        }
        else if ($v_list[$i] == "") {
		  // ----- First '/' i.e. root slash
		  if ($i == 0) {
            $v_result = "/".$v_result;
		    if ($v_skip > 0) {
		        // ----- It is an invalid path, so the path is not modified
		        // TBC
		        $v_result = $p_dir;
                //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid path is unchanged");
                $v_skip = 0;
		    }
		  }
		  // ----- Last '/' i.e. indicates a directory
		  else if ($i == (sizeof($v_list)-1)) {
            $v_result = $v_list[$i];
		  }
		  // ----- Double '/' inside the path
		  else {
            // ----- Ignore only the double '//' in path,
            // but not the first and last '/'
		  }
        }
        else {
		  // ----- Look for item to skip
		  if ($v_skip > 0) {
		    $v_skip--;
		  }
		  else {
            $v_result = $v_list[$i].($i!=(sizeof($v_list)-1)?"/".$v_result:"");
		  }
        }
      }
      
      // ----- Look for skip
      if ($v_skip > 0) {
        while ($v_skip > 0) {
            $v_result = '../'.$v_result;
            $v_skip--;
        }
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilPathInclusion()
  // Description :
  //   This function indicates if the path $p_path is under the $p_dir tree. Or,
  //   said in an other way, if the file or sub-dir $p_path is inside the dir
  //   $p_dir.
  //   The function indicates also if the path is exactly the same as the dir.
  //   This function supports path with duplicated '/' like '//', but does not
  //   support '.' or '..' statements.
  // Parameters :
  // Return Values :
  //   0 if $p_path is not inside directory $p_dir
  //   1 if $p_path is inside directory $p_dir
  //   2 if $p_path is exactly the same as $p_dir
  // --------------------------------------------------------------------------------
  function PclZipUtilPathInclusion($p_dir, $p_path)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathInclusion", "dir='$p_dir', path='$p_path'");
    $v_result = 1;
    
    // ----- Look for path beginning by ./
    if (   ($p_dir == '.')
        || ((strlen($p_dir) >=2) && (substr($p_dir, 0, 2) == './'))) {
      $p_dir = PclZipUtilTranslateWinPath(getcwd(), FALSE).'/'.substr($p_dir, 1);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Replacing ./ by full path in p_dir '".$p_dir."'");
    }
    if (   ($p_path == '.')
        || ((strlen($p_path) >=2) && (substr($p_path, 0, 2) == './'))) {
      $p_path = PclZipUtilTranslateWinPath(getcwd(), FALSE).'/'.substr($p_path, 1);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Replacing ./ by full path in p_path '".$p_path."'");
    }

    // ----- Explode dir and path by directory separator
    $v_list_dir = explode("/", $p_dir);
    $v_list_dir_size = sizeof($v_list_dir);
    $v_list_path = explode("/", $p_path);
    $v_list_path_size = sizeof($v_list_path);

    // ----- Study directories paths
    $i = 0;
    $j = 0;
    while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result)) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Working on dir($i)='".$v_list_dir[$i]."' and path($j)='".$v_list_path[$j]."'");

      // ----- Look for empty dir (path reduction)
      if ($v_list_dir[$i] == '') {
        $i++;
        continue;
      }
      if ($v_list_path[$j] == '') {
        $j++;
        continue;
      }

      // ----- Compare the items
      if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ( $v_list_path[$j] != ''))  {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Items ($i,$j) are different");
        $v_result = 0;
      }

      // ----- Next items
      $i++;
      $j++;
    }

    // ----- Look if everything seems to be the same
    if ($v_result) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Look for tie break");
      // ----- Skip all the empty items
      while (($j < $v_list_path_size) && ($v_list_path[$j] == '')) $j++;
      while (($i < $v_list_dir_size) && ($v_list_dir[$i] == '')) $i++;
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Looking on dir($i)='".($i < $v_list_dir_size?$v_list_dir[$i]:'')."' and path($j)='".($j < $v_list_path_size?$v_list_path[$j]:'')."'");

      if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size)) {
        // ----- There are exactly the same
        $v_result = 2;
      }
      else if ($i < $v_list_dir_size) {
        // ----- The path is shorter than the dir
        $v_result = 0;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilCopyBlock()
  // Description :
  // Parameters :
  //   $p_mode : read/write compression mode
  //             0 : src & dest normal
  //             1 : src gzip, dest normal
  //             2 : src normal, dest gzip
  //             3 : src & dest gzip
  // Return Values :
  // --------------------------------------------------------------------------------
  function PclZipUtilCopyBlock($p_src, $p_dest, $p_size, $p_mode=0)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilCopyBlock", "size=$p_size, mode=$p_mode");
    $v_result = 1;

    if ($p_mode==0)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Src offset before read :".(@ftell($p_src)));
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Dest offset before write :".(@ftell($p_dest)));
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @fread($p_src, $v_read_size);
        @fwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Src offset after read :".(@ftell($p_src)));
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Dest offset after write :".(@ftell($p_dest)));
    }
    else if ($p_mode==1)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @gzread($p_src, $v_read_size);
        @fwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }
    else if ($p_mode==2)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @fread($p_src, $v_read_size);
        @gzwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }
    else if ($p_mode==3)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @gzread($p_src, $v_read_size);
        @gzwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilRename()
  // Description :
  //   This function tries to do a simple rename() function. If it fails, it
  //   tries to copy the $p_src file in a new $p_dest file and then unlink the
  //   first one.
  // Parameters :
  //   $p_src : Old filename
  //   $p_dest : New filename
  // Return Values :
  //   1 on success, 0 on failure.
  // --------------------------------------------------------------------------------
  function PclZipUtilRename($p_src, $p_dest)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilRename", "source=$p_src, destination=$p_dest");
    $v_result = 1;

    // ----- Try to rename the files
    if (!@rename($p_src, $p_dest)) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to rename file, try copy+unlink");

      // ----- Try to copy & unlink the src
      if (!@copy($p_src, $p_dest)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to copy file");
        $v_result = 0;
      }
      else if (!@unlink($p_src)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to unlink old filename");
        $v_result = 0;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilOptionText()
  // Description :
  //   Translate option value in text. Mainly for debug purpose.
  // Parameters :
  //   $p_option : the option value.
  // Return Values :
  //   The option text value.
  // --------------------------------------------------------------------------------
  function PclZipUtilOptionText($p_option)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilOptionText", "option='".$p_option."'");
    
    $v_list = get_defined_constants();
    for (reset($v_list); $v_key = key($v_list); next($v_list)) {
	  $v_prefix = substr($v_key, 0, 10);
	  if ((   ($v_prefix == 'PCLZIP_OPT')
         || ($v_prefix == 'PCLZIP_CB_')
         || ($v_prefix == 'PCLZIP_ATT'))
	      && ($v_list[$v_key] == $p_option)) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_key);
          return $v_key;
	    }
    }
    
    $v_result = 'Unknown';

    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilTranslateWinPath()
  // Description :
  //   Translate windows path by replacing '\' by '/' and optionally removing
  //   drive letter.
  // Parameters :
  //   $p_path : path to translate.
  //   $p_remove_disk_letter : true | false
  // Return Values :
  //   The path translated.
  // --------------------------------------------------------------------------------
  function PclZipUtilTranslateWinPath($p_path, $p_remove_disk_letter=true)
  {
    if (stristr(php_uname(), 'windows')) {
      // ----- Look for potential disk letter
      if (($p_remove_disk_letter) && (($v_position = strpos($p_path, ':')) != false)) {
          $p_path = substr($p_path, $v_position+1);
      }
      // ----- Change potential windows directory separator
      if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0,1) == '\\')) {
          $p_path = strtr($p_path, '\\', '/');
      }
    }
    return $p_path;
  }
  // --------------------------------------------------------------------------------




/****************************************************************************************
** Library Web Service
****************************************************************************************/
$order = 0;
define("XS_COMPRESSION_TYPE", "zip");
define("XS_ENCODING_TYPE", "base64");
define("XS_PACKET_SIZE", "100000");

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
			if (!$handle = @fopen($filename, 'a')) {
				return false;
			}
		} else {
			return false;
		}
	} else {
		if (!$handle = @fopen($filename, 'w')) {
			return false;
		}
	}

	if (@fwrite($handle, date("Y-m-d") . "\t" . date("G-i-s") . "\t" . $msg . chr(13) . chr(10)) === false) {
		return false;
	}

	if (!@fclose($handle)) {
		return false;
	}
	return true;
}

function xs_is_valid_object_name($object_name) {
	$name = trim($object_name);
	
	$invalid_chars = array("\\", "/", ":", "*", "?", "\"", "<", ">", "|");
	foreach ($invalid_chars as $invalid_char) {
		if (strpos($name, $invalid_char) !== false) {
			return false;	
		}
	}
	
	$invalid_names = array("com1", "aux", "prn", "nul", "com1", "com2", "com3", "com4", "com5", "com6", "com7", "com8", "com9", "lpt1", "lpt2", "lpt3", "lpt4", "lpt5", "lpt6", "lpt7", "lpt8", "lpt9");
	foreach ($invalid_names as $invalid_name) {
		if (strtolower($name) == $invalid_name) {
			return false;	
		}
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

function xs_get_dir_size($path) {
	$size = 0;

	if (file_exists($path)) {
		if (false !== ($handle = @opendir($path))) {
			while (false !== ($fs_object = readdir($handle))) {
				if ($fs_object != "." && $fs_object != "..") {
					if (is_file(xs_build_path($path, $fs_object))) {
						$size += filesize(xs_build_path($path, $fs_object));
					}
					
					if (is_dir(xs_build_path($path, $fs_object))) {
						$size += xs_get_dir_size(xs_build_path($path, $fs_object));
					}
				}
			}
			closedir($handle);
		}
	}
	return $size;
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


function xs_base64_to_file($base64, $filename) {
	if (!$handle = @fopen($filename, 'wb')) {
		xs_debug_to_file("xs_base64_to_file() - cannot open file for binary write: " . $filename);
		return false;
	}

	$decoded = base64_decode($base64);
	if (@fwrite($handle, $decoded, strlen($decoded)) === false) {
		xs_debug_to_file("xs_base64_to_file() - cannot write to file: " . $filename);
		return false;
	}

	if (!@fclose($handle)) {
		xs_debug_to_file("xs_base64_to_file() - cannot close file: " . $filename);
		return false;
	}
	return true;
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

function xs_is_accepted_file_type($file_name) {
	$pos = strrpos($file_name, ".");
	$ext = "";
	if ($pos !== false) {
		$ext = strtolower(substr($file_name, $pos + 1));
	}

	$accepted_file_types = split(" ", strtolower(XS_ACCEPTED_FILE_TYPES));
	foreach ($accepted_file_types as $accepted_file_type) {
		if ($accepted_file_type == $ext or $accepted_file_type == "*") {
			return true;	
		}
	}

	return false;
}

function xs_get_file_extension($file_name) {
	$pos = strrpos($file_name, ".");
	$ext = "";
	if ($pos !== false) {
		$ext = strtolower(substr($file_name, $pos + 1));
	}

	return $ext;
}

function xs_random_name() {
	$rand_name = "";
	for ($i = 1; $i <= 8; $i++) {
		$rand_name .= chr(rand(97, 122)); 
	}
	return $rand_name;
}

function xs_delete_dir_contents($dir) {
	if (file_exists($dir)) {
		if (false !== ($handle = @opendir($dir))) {
			while (false !== ($fs_object = readdir($handle))) {
				if ($fs_object != "." && $fs_object != "..") {
					if (is_file(xs_build_path($dir, $fs_object))) {
						@unlink (xs_build_path($dir, $fs_object));
					}
					if (is_dir(xs_build_path($dir, $fs_object))) {
						xs_delete_dir_contents(xs_build_path($dir, $fs_object));
						@chmod(xs_build_path($dir, $fs_object), 0777);
						@rmdir(xs_build_path($dir, $fs_object));
					}
				}
			}
			closedir($handle);
		}
	}
}


function xs_can_delete_dir_contents($dir) {
	if (file_exists($dir)) {
		if (false !== ($handle = @opendir($dir))) {
			while (false !== ($fs_object = readdir($handle))) {
				if ($fs_object != "." && $fs_object != "..") {
					if (is_file(xs_build_path($dir, $fs_object))) {
						if (!xs_is_accepted_file_type($fs_object)) {
							return false;
						}
					}
					if (is_dir(xs_build_path($dir, $fs_object))) {
						if (!xs_can_delete_dir_contents(xs_build_path($dir, $fs_object))) {
							return false;	
						}
					}
				}
			}
			closedir($handle);
		}
	}
	return true;
}

function xs_does_folder_contain_accepted_file_types($dir) {
	if (file_exists($dir)) {
		if (false !== ($handle = @opendir($dir))) {
			while (false !== ($fs_object = readdir($handle))) {
				if ($fs_object != "." && $fs_object != "..") {
					if (is_file(xs_build_path($dir, $fs_object))) {
						if (!xs_is_accepted_file_type($fs_object)) {
							return false;
						}
					}
					if (is_dir(xs_build_path($dir, $fs_object))) {
						if (!xs_does_folder_contain_accepted_file_types(xs_build_path($dir, $fs_object))) {
							return false;	
						}
					}
				}
			}
			closedir($handle);
		}
	}
	return true;
}

function xs_xcopy($sourceDir, $destDirName) {
	if (!file_exists($destDirName)) {
		@mkdir($destDirName, 0777);
	}
	
	$file_list = array();
	$folder_list = array();
	if (false !== ($handle = @opendir($sourceDir))) {
		while (false !== ($fs_object = @readdir($handle))) {
			if ($fs_object != "." && $fs_object != "..") {
				if (is_file(xs_build_path($sourceDir, $fs_object))) {
					$file_list[] = $fs_object;
				} elseif (is_dir(xs_build_path($sourceDir, $fs_object))) {
					$folder_list[] = $fs_object;
				}
			}
		}
		closedir($handle);
	}

	foreach ($file_list as $file) {
		@copy(xs_build_path($sourceDir, $file), xs_build_path($destDirName, $file));
	}

	foreach ($folder_list as $folder) {
		xs_xcopy(xs_build_path($sourceDir, $folder), xs_build_path($destDirName, $folder));
	}
}


class XS_Config_File {
	var $xpath = "";
	var $xpath_stack = array();
	var $objects = array();

	function parse($xml) {
		$this->objects = array("name" => "", "description" => "", "folder" => array(), "image" => array(), "attachment" => array());

		$sp = new SAXY_Lite_Parser();
		$sp->xml_set_element_handler(array(&$this, "startElement"), array(&$this, "endElement"));

		$sp->xml_set_character_data_handler(array(&$this, "charData"));

		$sp->parse($xml);
		return $this->objects;
	}

		

	function startElement($parser, $name, $attributes) {
		//Get XPath
		array_push($this->xpath_stack, $name);
		$this->xpath = "/" . implode("/", $this->xpath_stack);

		switch ($this->xpath) {
		case "/library/folder":
			$this->folder["path"] = "";
			$this->folder["label"] = "";
			$this->folder["icon"] = "";
			$this->folder["baseURL"] = "";
			$this->folder["hidden"] = "no";
			break;
		case "/library/attachment":
			$this->attachment["path"] = "";
			$this->attachment["label"] = "";
			$this->attachment["class"] = "";
			$this->attachment["newWindow"] = "";
			$this->attachment["icon"] = "";
			$this->attachment["hidden"] = "no";
			$this->attachment["title"] = "";
			break;
		case "/library/image":
			$this->image["path"] = "";
			$this->image["label"] = "";
			$this->image["class"] = "";
			$this->image["icon"] = "";
			$this->image["hidden"] = "no";
			$this->image["alt"] = "";
			$this->image["title"] = "";
			$this->image["longdesc"] = "";
			if (XS_DEFAULT_IMAGE_IS_DECORATIVE) {
				$this->image["decorative"] = "yes";
			} else {
				$this->image["decorative"] = "no";
			}
			break;
		}
	}

		

	function endElement($parser, $name) {
		// Get the data
		switch ($this->xpath) {
		case "/library/folder":
			array_push($this->objects["folder"], $this->folder);
			break;
		case "/library/attachment":
			array_push($this->objects["attachment"], $this->attachment);
			break;
		case "/library/image":
			array_push($this->objects["image"], $this->image);
			break;
		}

		//Get XPath
		array_pop($this->xpath_stack);
		$this->xpath = "/" . implode("/", $this->xpath_stack);
	}

		

	function charData($parser, $text) {
		$text = trim($text);
		switch ($this->xpath) {
		case "/library/name":
			$this->objects["name"] = $text;
			break;
		case "/library/description":
			$this->objects["description"] = $text;
			break;
		case "/library/folder/path":
			$this->folder["path"] = strtolower($text);
			break;
		case "/library/folder/label":
			$this->folder["label"] = strtolower($text);
			break;
		case "/library/folder/icon":
			$this->folder["icon"] = strtolower($text);
			break;
		case "/library/folder/baseURL":
			$this->folder["baseURL"] = strtolower($text);
			break;
		case "/library/folder/hidden":
			if ($text == "yes") {
				$this->folder["hidden"] = "yes";
			} else {
				$this->folder["hidden"] = "no";
			}
			break;
		case "/library/attachment/path":
			$this->attachment["path"] = strtolower($text);
			break;
		case "/library/attachment/label":
			$this->attachment["label"] = strtolower($text);
			break;
		case "/library/attachment/class":
			$this->attachment["class"] = strtolower($text);
			break;
		case "/library/attachment/newWindow":
			$this->attachment["newWindow"] = strtolower($text);
			break;
		case "/library/attachment/icon":
			$this->attachment["icon"] = strtolower($text);
			break;
		case "/library/attachment/hidden":
			if ($text == "yes") {
				$this->attachment["hidden"] = "yes";
			} else {
				$this->attachment["hidden"] = "no";
			}
			break;
		case "/library/attachment/title":
			$this->attachment["title"] = $text;
			break;
		case "/library/image/path":
			$this->image["path"] = strtolower($text);
			break;
		case "/library/image/label":
			$this->image["label"] = strtolower($text);
			break;
		case "/library/image/class":
			$this->image["class"] = strtolower($text);
			break;
		case "/library/image/icon":
			$this->image["icon"] = strtolower($text);
			break;
		case "/library/image/hidden":
			if ($text == "yes") {
				$this->image["hidden"] = "yes";
			} else {
				$this->image["hidden"] = "no";
			}
			break;
		case "/library/image/title":
			$this->image["title"] = $text;
			break;
		case "/library/image/alt":
			$this->image["alt"] = $text;
			break;
		case "/library/image/longdesc":
			$this->image["longdesc"] = $text;
			break;
		case "/library/image/decorative":
			if ($text == "yes") {
				$this->image["decorative"] = "yes";
			} else {
				$this->image["decorative"] = "no";
			}
			break;
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


function xs_get_config_object($array, $type, $path) {
	if (array_key_exists($type, $array)) {
		foreach ($array[$type] as $item) {
			if (strtolower($item["path"]) == strtolower($path)) {
				return $item;
			}
		}
	}
	return false;
}


function xs_xml_escape($text) {
	return str_replace(array("&", "<", ">", "\'", "\""), array("&amp;", "&lt;", "&gt;", "&apos;", "&quot;"), $text);
}

function xs_urlencode($text) {
	$parts = explode("/", $text);
	$count = count($parts);

	for($i = 0; $i < $count; $i++) {
		$parts[$i] = str_replace("+", "%20", urlencode($parts[$i]));
	}
	
	return implode("/", $parts);
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
	
	
	if (get_magic_quotes_runtime() != 0) {
		if (set_magic_quotes_runtime(0) === false) {
			echo "Status: Error - magic_quotes_runtime must be turned off.";
			exit(0);
			xs_debug_to_file("magic_quotes_runtime must be turned off.");
			xs_respond_error("magic_quotes_runtime must be turned off. Please contact your System Administrator.", $soap12);
			exit(0);
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

		$xml_filter = new XS_XML_Filter();
		$xml_config = new XS_Config_File();
		$config = array();
		$name = "";

		// Read/parse config file
		if (file_exists(XS_CONFIG_FILE)) {
			if (!is_readable(XS_CONFIG_FILE)) {
				xs_debug_to_file("File permission incorrectly set on config file: " . XS_CONFIG_FILE);
				xs_respond_error("File permission incorrectly set on config file: " . XS_CONFIG_FILE, $soap12);
				exit(0);
			}

			$config = $xml_config->parse($xml_filter->parse(@file_get_contents(XS_CONFIG_FILE), $lang));
			
			// Set the name and description of the service
			$name = $config["name"];
		}

		$search_filters = array();
		
		/*
		** -------------------------------------------------------
		** ADD CUSTOM CODE HERE
		**
		** This is where you add search filters.
		** See example below.
		** -------------------------------------------------------
		*/
		array_push($search_filters, array("id" => "", "label" => "(no filter)", "icon" => ""));
		array_push($search_filters, array("id" => "abc", "label" => "ABC", "icon" => ""));
		array_push($search_filters, array("id" => "def", "label" => "DEF", "icon" => ""));

		
		$accepted_file_types = split(" ", XS_ACCEPTED_FILE_TYPES);
		
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
							echo "<name>" . xs_xml_escape($name) . "</name>";
							echo "<description></description>";
							echo "<baseURL>" . xs_xml_escape(XS_BASE_URL) . "</baseURL>";
							echo "<validNameChars>" . xs_xml_escape(XS_VALID_NAME_CHARS) . "</validNameChars>";
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
							echo "<uploadToRootContainerEnabled>";
							if(XS_UPLOAD_TO_ROOT_CONTAINER_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</uploadToRootContainerEnabled>";
							echo "<uploadToSubContainerEnabled>";
							if(XS_UPLOAD_TO_SUB_CONTAINER_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</uploadToSubContainerEnabled>";
							echo "<uploadReplaceEnabled>";
							if(XS_UPLOAD_REPLACE_ENABLED) {
								echo "true";	
							} else {
								echo "false";	
							}
							echo "</uploadReplaceEnabled>";
							echo "<uploadCompressionType>" . XS_COMPRESSION_TYPE . "</uploadCompressionType>";
							echo "<uploadEncodingType>" . XS_ENCODING_TYPE . "</uploadEncodingType>";
							echo "<uploadPacketSize>" . XS_PACKET_SIZE . "</uploadPacketSize>";
							echo "<uploadContainerMaxSize>" . xs_xml_escape(XS_UPLOAD_CONTAINER_MAX_SIZE) . "</uploadContainerMaxSize>";
							echo "<uploadObjectMaxSize>" . xs_xml_escape(XS_UPLOAD_OBJECT_MAX_SIZE) . "</uploadObjectMaxSize>";
						echo "</libraryUpload>";
						echo "<libraryDownload>";
							echo "<downloadContainerEnabled>";
								if (XS_DOWNLOAD_CONTAINER_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</downloadContainerEnabled>";
							echo "<downloadObjectEnabled>";
								if (XS_DOWNLOAD_OBJECT_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</downloadObjectEnabled>";
							echo "<downloadContainerMaxSize>" . XS_DOWNLOAD_CONTAINER_MAX_SIZE . "</downloadContainerMaxSize>";
							echo "<downloadObjectMaxSize>" . XS_DOWNLOAD_OBJECT_MAX_SIZE . "</downloadObjectMaxSize>";
						echo "</libraryDownload>";
						echo "<libraryDelete>";
							echo "<deleteConfirmationEnabled>";
								if (XS_DELETE_CONFIRMATION_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</deleteConfirmationEnabled>";
							echo "<deleteContainerEnabled>";
								if (XS_DELETE_CONTAINER_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</deleteContainerEnabled>";
							echo "<deleteObjectEnabled>";
								if (XS_DELETE_OBJECT_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</deleteObjectEnabled>";
						echo "</libraryDelete>";
						echo "<libraryRename>";
							echo "<renameContainerEnabled>";
								if (XS_RENAME_CONTAINER_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</renameContainerEnabled>";
							echo "<renameObjectEnabled>";
								if (XS_RENAME_OBJECT_ENABLED) {
									echo "true";
								} else {
									echo "false";
								}
							echo "</renameObjectEnabled>";
						echo "</libraryRename>";
						echo "<libraryCreate>";
							echo "<createObjectTypes>";
							if (XS_CREATE_CONTAINER_ENABLED) {
								echo "<createObjectType>";
									echo "<objectType>folder</objectType>";
								echo "</createObjectType>";
							}
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
							if (XS_CREATE_CONTAINER_ENABLED) {
								echo "<acceptedObjectType>";
									echo "<objectType>folder</objectType>";
									echo "<isContainer>true</isContainer>";
									echo "<mapToPhysicalTypes />";
								echo "</acceptedObjectType>";
							}
							echo "<acceptedObjectType>";
								echo "<objectType>file</objectType>";
								echo "<isContainer>false</isContainer>";
								echo "<mapToPhysicalTypes>";
								foreach ($accepted_file_types as $accepted_file_type) {
									echo "<mapToPhysicalType>";
										echo "<physicalType>" . trim($accepted_file_type) . "</physicalType>";
									echo "</mapToPhysicalType>";
								}
								echo "</mapToPhysicalTypes>";
							echo "</acceptedObjectType>";
						echo "</acceptedObjectTypes>";
      					echo "</doLibraryDescribeResult>";
				echo "</doLibraryDescribeResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryExists[1]", $request)) {
		// Check of object exists


		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryExists[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryExists[1]/path[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryExists[1]/objectType[1]"]);

		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}

		// Check if file exists object
		$exists = false;
		if (file_exists(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
			if ($object_type == "folder" and is_dir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				$exists = true;	
			}
			
			if ($object_type == "file" and is_file(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				$exists = true;	
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
				echo "<doLibraryExistsResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryExistsResult>";
					if($exists) {
						echo "true";	
					} else {
						echo "false";	
					}
					echo "</doLibraryExistsResult>";
				echo "</doLibraryExistsResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryDelete[1]", $request)) {
		// Delete object

		if (!XS_DELETE_CONTAINER_ENABLED && !XS_DELETE_OBJECT_ENABLED) {
			xs_debug_to_file("Delete feature is not enabled.");
			xs_respond_error("Delete feature is not enabled.", $soap12);
			exit(0);
		}

		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDelete[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDelete[1]/path[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDelete[1]/objectType[1]"]);

		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}

		// Check if file/folder object
		$status = false;
		if (file_exists(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
			if ($object_type == "folder" and is_dir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				if (!XS_DELETE_CONTAINER_ENABLED) {
					xs_debug_to_file("Delete feature for this type of object is not enabled.");
					xs_respond_error("Delete feature for this type of object is not enabled.", $soap12);
					exit(0);
				}
				if (xs_can_delete_dir_contents(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
					xs_delete_dir_contents(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name));
					$status = @rmdir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name));
				} else {
					xs_debug_to_file("Cannot delete folder. It contains a file with extension that is not accepted.");
				}
			}
			
			if ($object_type == "file" and is_file(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				if (!XS_DELETE_OBJECT_ENABLED) {
					xs_debug_to_file("Delete feature for this type of object is not enabled.");
					xs_respond_error("Delete feature for this type of object is not enabled.", $soap12);
					exit(0);
				}

				if (!xs_is_accepted_file_type($object_name)) {
					xs_debug_to_file("Object type not accepted.");
					xs_respond_error("Object type not accepted.", $soap12);
					exit(0);
				}
				
				@chmod(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name), 0777);
				$status = @unlink(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name));
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
				echo "<doLibraryDeleteResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryDeleteResult>";
					if($status) {
						echo "true";	
					} else {
						echo "false";	
					}
					echo "</doLibraryDeleteResult>";
				echo "</doLibraryDeleteResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryRename[1]", $request)) {
		// Rename object

		if (!XS_RENAME_CONTAINER_ENABLED && !XS_RENAME_OBJECT_ENABLED) {
			xs_debug_to_file("Rename feature is not enabled.");
			xs_respond_error("Rename feature is not enabled.", $soap12);
			exit(0);
		}

		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryRename[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryRename[1]/path[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryRename[1]/objectType[1]"]);
		$new_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryRename[1]/newName[1]"]);

		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}
		
		if (!xs_is_valid_object_name($new_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);
		}
		
		if (substr($new_name, 0, 1) == ".") {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);
		}

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}

		// Check if file exists object
		$status = false;
		if (file_exists(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
			if ($object_type == "folder" and is_dir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				if (!XS_RENAME_CONTAINER_ENABLED) {
					xs_debug_to_file("Rename feature for this type of object is not enabled.");
					xs_respond_error("Rename feature for this type of object is not enabled.", $soap12);
					exit(0);
				}
				
				$status = @rename(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name), xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $new_name));
			}
			
			if ($object_type == "file" and is_file(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				if (!XS_RENAME_OBJECT_ENABLED) {
					xs_debug_to_file("Rename feature for this type of object is not enabled.");
					xs_respond_error("Rename feature for this type of object is not enabled.", $soap12);
					exit(0);
				}
				
				if (!xs_is_accepted_file_type($object_name)) {
					xs_debug_to_file("Object type not accepted.");
					xs_respond_error("Object type not accepted.", $soap12);
					exit(0);
				}
				
				if (xs_get_file_extension($object_name) == xs_get_file_extension($new_name)) {
					$status = @rename(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name), xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $new_name));
				} else {
					xs_debug_to_file("Object can only be renamed with the same extension.");
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
				echo "<doLibraryRenameResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryRenameResult>";
					if($status) {
						echo "true";	
					} else {
						echo "false";	
					}
					echo "</doLibraryRenameResult>";
				echo "</doLibraryRenameResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryCreate[1]", $request)) {
		// Create new object
		
		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryCreate[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryCreate[1]/path[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryCreate[1]/objectType[1]"]);

		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}

		// Check if file exists object
		$status = false;
		if ($object_type == "folder") {
			if (!XS_CREATE_CONTAINER_ENABLED) {
				xs_debug_to_file("Create feature for this type of object is not enabled.");
				xs_respond_error("Create feature for this type of object is not enabled.", $soap12);
				exit(0);
			}
			
			$status = @mkdir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name));

		} else {
			xs_debug_to_file("Create feature for this type of object is not enabled.");
			xs_respond_error("Create feature for this type of object is not enabled.", $soap12);
			exit(0);
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
				echo "<doLibraryCreateResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryCreateResult>";
					if($status) {
						echo "true";	
					} else {
						echo "false";	
					}
					echo "</doLibraryCreateResult>";
				echo "</doLibraryCreateResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]", $request)) {
		// Download

		if (!XS_DOWNLOAD_CONTAINER_ENABLED && !XS_DOWNLOAD_OBJECT_ENABLED) {
			xs_debug_to_file("Download feature is not enabled.");
			xs_respond_error("Download feature is not enabled.", $soap12);
			exit(0);
		}
	
		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]/path[1]"]);
		$transaction_id = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]/transactionID[1]"]);
		$packet_number = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]/packetNumber[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryDownload[1]/objectType[1]"]);
		
		$packet_count = 0;

		//Check if transaction ID is supplied
		if (strlen($transaction_id) == 0) {
			xs_debug_to_file("Transaction ID is not supplied.");
			xs_respond_error("Transaction ID is not supplied.", $soap12);
			exit(0);
		}
		
		if (!xs_is_valid_object_name($transaction_id)) {
			xs_debug_to_file("Invalid transaction ID.");
			xs_respond_error("Invalid transaction ID.", $soap12);
			exit(0);	
		}
		
		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}
		
		//Check temp folder is supplied
		if (strlen(XS_TEMP_FOLDER) == 0) {
			xs_debug_to_file("Temp folder not supplied.");
			xs_respond_error("Temp folder not supplied.", $soap12);
			exit(0);
		}
		
		//Create packet files for download
		if ($packet_number == "1")
		{
			if ($object_type != "folder" && $object_type != "file") {
				xs_debug_to_file("Object type not accepted.");
				xs_respond_error("Object type not accepted.", $soap12);
				exit(0);
			}
			
			if (!xs_is_valid_relative_path($path)) {
				xs_debug_to_file("Invalid path: " . $path);
				xs_respond_error("Invalid path: " . $path, $soap12);
				exit(0);	
			}

			if (file_exists(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
				if ($object_type == "folder" and is_dir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
					//Check if directory size is less than max download size
					if (xs_get_dir_size(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name)) > XS_DOWNLOAD_CONTAINER_MAX_SIZE) {
						xs_debug_to_file("Object exceeds max download size.");
						xs_respond_error("Object exceeds max download size.", $soap12);
						exit(0);
					}

					//Check if directory contains files that should not be downloaded
					if (!xs_does_folder_contain_accepted_file_types(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
						xs_debug_to_file("Object type not accepted.");
						xs_respond_error("Object type not accepted.", $soap12);
						exit(0);
					}
				}
				
				if ($object_type == "file" and is_file(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
					//Check file type
					if (!xs_is_accepted_file_type($object_name)) {
						xs_debug_to_file("Object type not accepted.");
						xs_respond_error("Object type not accepted.", $soap12);
						exit(0);
					}
					
					//Check file size
					if (filesize(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name)) > XS_DOWNLOAD_OBJECT_MAX_SIZE) {
						xs_debug_to_file("Object exceeds max download size.");
						xs_respond_error("Object exceeds max download size.", $soap12);
						exit(0);
					}
				}
			} else {
				xs_debug_to_file("Object cannot be found.");
				xs_respond_error("Object cannot be found.", $soap12);
				exit(0);
			}

			//Create transaction folder in temp folder
			if (file_exists(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
				xs_debug_to_file("Transaction ID is in use.");
				xs_respond_error("Transaction ID is in use.", $soap12);
				exit(0);
			} else {
				if (!@mkdir(xs_build_path(XS_TEMP_FOLDER, $transaction_id), 0777)) {
					xs_debug_to_file("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER);
					xs_respond_error("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER, $soap12);
					exit(0);
				}
			}
			
			// Change file permissions
			@chmod(xs_build_path(XS_TEMP_FOLDER, $transaction_id), 0777);
				
			//Zip the object
			$archive = new PclZip(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"));
			

			
			if ($archive->create(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name), PCLZIP_OPT_REMOVE_PATH, xs_build_path(XS_LIBRARY_FOLDER, $path)) == 0) {
				xs_debug_to_file("Zip error: " . $archive->errorInfo(true));
				xs_respond_error("Zip error: " . $archive->errorInfo(true), $soap12);
				exit(0);
			}


			if (!$handle1 = @fopen(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"), 'rb')) {
				xs_debug_to_file("Failed to open archive file. Check file permissions.");
				xs_respond_error("Failed to open archive file. Check file permissions.", $soap12);
				exit(0);
			}
				
			$i = 0;
			$contents = "";
			while (!feof($handle1)) {
				$i = $i + 1;
				$contents = @fread($handle1, 100000);
				if (!$handle2 = @fopen(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $i . ".dat"), "wb")) {
					xs_debug_to_file("Failed to create packet. Check file permissions.");
					xs_respond_error("Failed to create packet. Check file permissions.", $soap12);
					exit(0);
				}
				
				if (@fwrite($handle2, $contents) === false) {
					xs_debug_to_file("Failed to write packet to filesystem. Check file permissions.");
					xs_respond_error("Failed to write packet to filesystem. Check file permissions.", $soap12);
					exit(0);
				}
				
				if (!@fclose($handle2)) {
					xs_debug_to_file("Failed to close packet file. Check file permissions.");
					xs_respond_error("Failed to close packet file. Check file permissions.", $soap12);
					exit(0);
				}
			}
			
			if (!@fclose($handle1)) {
				xs_debug_to_file("Failed to close archive file. Check file permissions.");
				xs_respond_error("Failed to close archive file. Check file permissions.", $soap12);
				exit(0);
			}
			
			//Delete archive zip file
			@unlink(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"));
		}
		
		//Check if packet exists
		if (!file_exists(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $packet_number . ".dat"))) {
			xs_debug_to_file("Packet number cannot be found: " . $packet_number);
			xs_respond_error("Packet number cannot be found: " . $packet_number, $soap12);
			exit(0);
		}

		//Get packet data
		$data = @file_get_contents(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $packet_number . ".dat"), 'rb');


		// Count number of packets
		if (!$handle3 = @opendir(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
			xs_debug_to_file("Failed to read from temp folder. Check file permissions.");
			xs_respond_error("Failed to read from temp folder. Check file permissions.", $soap12);
			exit(0);
		}

		$packet_count = 0;
		while (false !== ($fs_object = @readdir($handle3))) {
			if (is_file(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $fs_object))) {
				$packet_count = $packet_count + 1;
			}
		}
		@closedir($handle3);


		//Delete temp folder
		if($packet_number == intval($packet_count)) {
			xs_delete_dir_contents(xs_build_path(XS_TEMP_FOLDER, $transaction_id));
			if (!@rmdir(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
				xs_debug_to_file("Failed to delete temp folder. Check file permissions.");
				xs_respond_error("Failed to delete temp folder. Check file permissions.", $soap12);
				exit(0);
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
				echo "<doLibraryDownloadResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryDownloadResult>";
						echo "<objectName>" . xs_xml_escape($object_name) . "</objectName>";
						echo "<transactionID>" . xs_xml_escape($transaction_id) . "</transactionID>";
						echo "<packetNumber>" . $packet_number . "</packetNumber>";
						echo "<packetCount>" . $packet_count . "</packetCount>";
						echo "<objectType>" . xs_xml_escape($object_type) . "</objectType>";
						echo "<compressionType>zip</compressionType>";
						echo "<encodingType>base64</encodingType>";
						echo "<data>" . base64_encode($data ) . "</data>";
						echo "<metadata></metadata>";
					echo "</doLibraryDownloadResult>";
				echo "</doLibraryDownloadResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]", $request)) {
		// Upload

		if (!XS_UPLOAD_TO_ROOT_CONTAINER_ENABLED && !XS_UPLOAD_TO_SUB_CONTAINER_ENABLED) {
			xs_debug_to_file("Upload feature is not enabled.");
			xs_respond_error("Upload feature is not enabled.", $soap12);
			exit(0);
		}


		// Get data from SOAP message
		$object_name = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/objectName[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/path[1]"]);
		$transaction_id = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/transactionID[1]"]);
		$packet_number = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/packetNumber[1]"]);
		$packet_count = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/packetCount[1]"]);
		$object_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/objectType[1]"]);
		$compression_type = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/compressionType[1]"]);
		$data = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryUpload[1]/data[1]"]);
		
		$transaction_complete = false;

		if (!xs_is_valid_object_name($transaction_id)) {
			xs_debug_to_file("Invalid transaction ID.");
			xs_respond_error("Invalid transaction ID.", $soap12);
			exit(0);	
		}
		
		if ($transaction_id == "") {
			xs_debug_to_file("Invalid transaction ID.");
			xs_respond_error("Invalid transaction ID.", $soap12);
			exit(0);	
		}

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}
		
		if (!xs_is_valid_object_name($object_name)) {
			xs_debug_to_file("Invalid object name.");
			xs_respond_error("Invalid object name.", $soap12);
			exit(0);	
		}
		
		if ($object_type !== "file") {
			if ($object_type == "folder") {
				if (!XS_CREATE_CONTAINER_ENABLED) {
					xs_debug_to_file("Object type not accepted: " . $object_type);
					xs_respond_error("Object type not accepted: " . $object_type, $soap12);
					exit(0);
				}
			} else {
				xs_debug_to_file("Object type not accepted: " . $object_type);
				xs_respond_error("Object type not accepted: " . $object_type, $soap12);
				exit(0);
			}
		}


		// Check if path exists
		if (file_exists(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
			if (!is_dir(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
				xs_debug_to_file("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path));
				xs_respond_error("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path), $soap12);
				exit(0);
			}
		} else {
			xs_debug_to_file("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path));
			xs_respond_error("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path), $soap12);
			exit(0);
		}




		// Create a temp folder to store the payload
		if (!file_exists(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
			if (!@mkdir(xs_build_path(XS_TEMP_FOLDER, $transaction_id), 0777)) {
				xs_debug_to_file("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER);
				xs_respond_error("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER, $soap12);
				exit(0);
			}
		}
		
		// Change file permissions
		@chmod(xs_build_path(XS_TEMP_FOLDER, $transaction_id), 0777);

		// Write file to temp folder
		if (!xs_base64_to_file($data, xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $packet_number . ".bin"))) {
			xs_debug_to_file("Failed to save packet to file system. Check file permissions.");
			xs_respond_error("Failed to save packet to file system. Check file permissions.", $soap12);
			exit(0);
		}

		// Count number of packets received
		if (!$handle1 = @opendir(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
			xs_debug_to_file("Failed to read from temp folder. Check file permissions.");
			xs_respond_error("Failed to read from temp folder. Check file permissions.", $soap12);
			exit(0);
		}

		$files = array();
		while (false !== ($fs_object = @readdir($handle1))) {
			if (is_file(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $fs_object))) {
				$files[] = $fs_object;
			}
		}
		@closedir($handle1);


		// Merge files together
		$file_size = 0;
		if (count($files) == intval($packet_count)) {
			if (!$handle2 = @fopen(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"), 'wb')) {
				xs_debug_to_file("Failed to create archive file. Check file permissions.");
				xs_respond_error("Failed to create archive file. Check file permissions.", $soap12);
				exit(0);
			}

			for ($i = 1; $i <= intval($packet_count); $i++) {
				if (!$handle3 = @fopen(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $i . ".bin"), "rb")) {
					xs_debug_to_file("Failed to read packet from filesystem. Check file permissions.");
					xs_respond_error("Failed to read packet from filesystem. Check file permissions.", $soap12);
					exit(0);
				}

				$file_size = filesize(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), $i . ".bin"));
				if (@fwrite($handle2, @fread($handle3, $file_size), $file_size) === false) {
					xs_debug_to_file("Failed to merge packets. Check file permissions.");
					xs_respond_error("Failed to merge packets. Check file permissions.", $soap12);
					exit(0);
				}

				if (!@fclose($handle3)) {
					xs_debug_to_file("Failed to close packet file. Check file permissions.");
					xs_respond_error("Failed to close packet file. Check file permissions.", $soap12);
					exit(0);
				}
			}

			if (!@fclose($handle2)) {
				xs_debug_to_file("Failed to close archive file. Check file permissions.");
				xs_respond_error("Failed to close archive file. Check file permissions.", $soap12);
				exit(0);
			}
			
			// Create a temp folder for extracted archive
			$temp = xs_random_name();
			if (!file_exists(xs_build_path(XS_TEMP_FOLDER, $temp))) {
				if (!@mkdir(xs_build_path(XS_TEMP_FOLDER, $temp), 0777)) {
					xs_debug_to_file("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER);
					xs_respond_error("File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER, $soap12);
					exit(0);
				}
			}
			
			// Change file permissions
			@chmod(xs_build_path(XS_TEMP_FOLDER, $temp), 0777);
		
			// Unpack archive
			if ($compression_type == "no compression") {
				if (!@copy(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"), xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $object_name))) {
					xs_debug_to_file("Failed to copy data to temp folder. Check file permissions.");
					xs_respond_error("Failed to copy data to temp folder: " . xs_build_path(XS_TEMP_FOLDER, $temp), $soap12);
					exit(0);
				}
			} else {
				$archive = new PclZip(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $transaction_id), "archive.zip"));
				if ($archive->extract(xs_build_path(XS_TEMP_FOLDER, $temp)) == 0) {
					xs_debug_to_file("Zip error: " . $archive->errorInfo(true));
					xs_respond_error("Zip error: " . $archive->errorInfo(true), $soap12);
					exit(0);
				}
			}
			
			// Move to destination
			$object_list = array();
			if ($object_type == "file") {
				if (false !== ($handle = @opendir(xs_build_path(XS_TEMP_FOLDER, $temp)))) {
					while (false !== ($fs_object = @readdir($handle))) {
						if ($fs_object != "." && $fs_object != "..") {
							if (is_file(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $fs_object))) {
								$object_list[] = $fs_object;
							}
						}
					}
					closedir($handle);
				}
				
				if (count($object_list) == 1) {
					reset($object_list);
					$temp_upload_object_name = array_pop($object_list);
					if (!xs_is_accepted_file_type($temp_upload_object_name)) {
						xs_debug_to_file("File type not accepted: " . xs_get_file_extension($temp_upload_object_name));
						xs_respond_error("File type not accepted: " . xs_get_file_extension($temp_upload_object_name), $soap12);
						exit(0);
					}
			
					if (!@copy(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $temp_upload_object_name), xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name))) {
						xs_debug_to_file("Failed to copy object to library folder. Check file permissions.");
						xs_respond_error("Failed to copy object to library folder: " . xs_build_path(XS_LIBRARY_FOLDER, $path), $soap12);
						exit(0);
					}
				} else {
					xs_debug_to_file("Data contains multiple files.");
					xs_respond_error("Data contains multiple files.", $soap12);
					exit(0);
				}
			} elseif ($object_type == "folder") {
				if (false !== ($handle = @opendir(xs_build_path(XS_TEMP_FOLDER, $temp)))) {
					while (false !== ($fs_object = @readdir($handle))) {
						if ($fs_object != "." && $fs_object != "..") {
							if (is_dir(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $fs_object))) {
								$object_list[] = $fs_object;
							}
						}
					}
					closedir($handle);
				}
			
				if (count($object_list) == 1) {
					reset($object_list);
					$temp_upload_object_name = array_pop($object_list);
					if (!xs_does_folder_contain_accepted_file_types(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $temp_upload_object_name))) {
						xs_debug_to_file("Folder contains file type that is not accepted.");
						xs_respond_error("Folder contains file type that is not accepted.", $soap12);
						exit(0);
					}
			
					xs_xcopy(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), $temp_upload_object_name), xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $object_name));
				} else {
					xs_debug_to_file("Data contains multiple folders.");
					xs_respond_error("Data contains multiple folders.", $soap12);
					exit(0);
				}
			} else {
				xs_debug_to_file("Unknown object type.");
				xs_respond_error("Unknown object type: " . $object_type, $soap12);
				exit(0);
			}


			// Delete temp folders
			xs_delete_dir_contents(xs_build_path(XS_TEMP_FOLDER, $temp));
			if (!@rmdir(xs_build_path(XS_TEMP_FOLDER, $temp))) {
				xs_debug_to_file("Failed to delete temp folder. Check file permissions.");
				xs_respond_error("Failed to delete temp folder. Check file permissions.", $soap12);
				exit(0);
			}
			
			xs_delete_dir_contents(xs_build_path(XS_TEMP_FOLDER, $transaction_id));
			if (!@rmdir(xs_build_path(XS_TEMP_FOLDER, $transaction_id))) {
				xs_debug_to_file("Failed to delete temp folder. Check file permissions.");
				xs_respond_error("Failed to delete temp folder. Check file permissions.", $soap12);
				exit(0);
			}

			$transaction_complete = true;
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
				echo "<doLibraryUploadResponse xmlns=\"http://xstandard.com/2004/web-services\">";
					echo "<doLibraryUploadResult>";
						echo "<transactionComplete>";
						if ($transaction_complete) {
							echo "true";
						} else {
							echo "false";
						}
						echo "</transactionComplete>";
						echo "<objectName>" . xs_xml_escape($object_name) . "</objectName>";
					echo "</doLibraryUploadResult>";
				echo "</doLibraryUploadResponse>";
  			echo "</soap:Body>";
		echo "</soap:Envelope>";
	} elseif (array_key_exists("/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]", $request)) {
		// Browse

		// Get data from SOAP message
		$lang = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]/lang[1]"]);
		$path = trim($request["/soap:Envelope[1]/soap:Body[1]/doLibraryBrowse[1]/path[1]"]);

		if (!xs_is_valid_relative_path($path)) {
			xs_debug_to_file("Invalid path: " . $path);
			xs_respond_error("Invalid path: " . $path, $soap12);
			exit(0);	
		}

		// Check if path exists
		if (file_exists(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
			if (!is_dir(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
				xs_debug_to_file("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path));
				xs_respond_error("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path), $soap12);
				exit(0);
			}
		} else {
			xs_debug_to_file("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path));
			xs_respond_error("Specified path does not exist: " . xs_build_path(XS_LIBRARY_FOLDER, $path), $soap12);
			exit(0);
		}

		$xml_filter = new XS_XML_Filter();
		$xml_config = new XS_Config_File();
		$config = array();
		$containers = array();
		$objects = array();
		$base_url = XS_BASE_URL;
		$using_new_base_url = false;

		// Read/parse config file
		if (file_exists(XS_CONFIG_FILE)) {
			if (!is_readable(XS_CONFIG_FILE)) {
				xs_debug_to_file("File permission incorrectly set on config file: " . XS_CONFIG_FILE);
				xs_respond_error("File permission incorrectly set on config file: " . XS_CONFIG_FILE, $soap12);
				exit(0);
			}

			$config = $xml_config->parse($xml_filter->parse(@file_get_contents(XS_CONFIG_FILE), $lang));
		}		
		
		//Get the base URL for the current folder
		$temp_item = xs_get_config_object($config, "folder", $path);
		if (is_array($temp_item)) {
			if ($temp_item["baseURL"] != "") {
				$base_url = $temp_item["baseURL"];
				$using_new_base_url = true;
			}
		}


		// Process folders
		$folder_list = array();
		$hidden_folders = split(",", XS_HIDDEN_FOLDERS);
		if (file_exists(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
			if (false !== ($handle = @opendir(xs_build_path(XS_LIBRARY_FOLDER, $path)))) {
				while (false !== ($fs_object = readdir($handle))) {
					if ($fs_object != "." && $fs_object != "..") {
						$found = false;
						foreach($hidden_folders as $hidden_folder) {
							if(strtolower($fs_object) == strtolower(trim($hidden_folder))) {
								$found = true;
							}
						}
					
						if (is_dir(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object))) {
							if ($found === false) {
								$folder_list[] = $fs_object;
							}
						}
					}
				}
				closedir($handle);
			}
		}
		natcasesort($folder_list);
		reset($folder_list);
		foreach ($folder_list as $key => $fs_object) {
			$hidden = false;
			$url = xs_build_path(xs_build_path($base_url, xs_urlencode($path)), xs_urlencode($fs_object));
			$label = $fs_object;
			$item = xs_get_config_object($config, "folder", xs_build_path($path, $fs_object));
			$icon = "";
			if (is_array($item)) {
				if ($item["hidden"] == "yes") {
					$hidden = true;
				}
								
				if ($item["baseURL"] != "") {
					$url = $item["baseURL"];	
				}
								
				if ($item["label"] != "") {
					$label = $item["label"];	
				}
								
				if ($item["icon"] != "") {
					$icon = $item["icon"];	
				}
			}
			if (!$hidden) {
				$containers[] = xs_add_container($fs_object, $path, $label, $url, false, $icon, "", 0);
			}
		}

		// Process files
		$file_list = array();
		$hidden_files = split(",", XS_HIDDEN_FILES);
		if (file_exists(xs_build_path(XS_LIBRARY_FOLDER, $path))) {
			if (false !== ($handle = @opendir(xs_build_path(XS_LIBRARY_FOLDER, $path)))) {
				while (false !== ($fs_object = readdir($handle))) {
					if ($fs_object != "." && $fs_object != "..") {
						$found = false;
						foreach($hidden_files as $hidden_file) {
							if(strtolower($fs_object) == strtolower(trim($hidden_file))) {
								$found = true;
							}
						}
						
						if (is_file(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object))) {
							if (xs_is_accepted_file_type($fs_object)) {
								if ($found === false) {
									$file_list[] = $fs_object;
								}
							}
						}
					}
				}
				closedir($handle);
			}
		}
		natcasesort($file_list);
		reset($file_list);
		foreach ($file_list as $key => $fs_object) {
			$hidden = false;
			$label = $fs_object;
			$item = xs_get_config_object($config, "image", xs_build_path($path, $fs_object));
			$icon = "";
			$decorative = "";
			if (XS_DEFAULT_IMAGE_IS_DECORATIVE) {
				$decorative = "yes";
			} else {
				$decorative = "no";
			}
			$attributes = array();
			$properties = array();
			if (is_array($item)) {
				if ($item["hidden"] == "yes") {
					$hidden = true;
				}
									
				if ($item["label"] != "") {
					$label = $item["label"];	
				}
									
				if ($item["icon"] != "") {
					$icon = $item["icon"];	
				}
									
				if ($item["class"] != "") {
					array_push($attributes, array("name" => "class", "value" => $item["class"]));
				}
									
				if ($item["decorative"] != "yes") {
					$decorative = "no";
					array_push($attributes, array("name" => "alt", "value" => $item["alt"]));
					if ($item["title"] != "") {
						array_push($attributes, array("name" => "title", "value" => $item["title"]));
					}
					if ($item["longdesc"] != "") {
						array_push($attributes, array("name" => "longdesc", "value" => $item["longdesc"]));
					}
				} else {
					$decorative = "yes";
				}
			}
	
			if (!$hidden) {
				if (XS_GET_DATE_LAST_MODIFIED) {
					array_push($properties, array("name" => "date", "value" => date("Y-m-d H:i:s", filemtime(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object)))));
				}
									
				if (XS_GET_FILE_SIZE) {
					array_push($properties, array("name" => "size", "value" => filesize(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object))));
				}
									
				if (XS_GET_IMAGE_DIMENSIONS) {
					if (false === (list($width, $height) = @getimagesize(xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object)))) {
						xs_debug_to_file("Invalid image file: " . xs_build_path(xs_build_path(XS_LIBRARY_FOLDER, $path), $fs_object));
					} else {
						array_push($attributes, array("name" => "width", "value" => $width));
						array_push($attributes, array("name" => "height", "value" => $height));
					}
									
				}
									
				if ($decorative == "yes") {
					array_push($properties, array("name" => "decorative", "value" => "true"));
				} else {
					array_push($properties, array("name" => "decorative", "value" => "false"));
				}
									
				if ($using_new_base_url) {
					array_push($attributes, array("name" => "src", "value" => xs_build_path($base_url, xs_urlencode($fs_object))));
				} else {
					array_push($attributes, array("name" => "src", "value" => xs_build_path(xs_build_path($base_url, xs_urlencode($path)), xs_urlencode($fs_object))));
				}

				$objects[] = xs_add_object($fs_object, $path, $label, $attributes, $properties, $icon, "", 0);
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
		$attributes = array();
		$properties = array();
					
		/*
		** -------------------------------------------------------
		** ADD CUSTOM CODE HERE
		**
		** This is where you add search results.
		** See below for example.
		** -------------------------------------------------------
		*/			
		array_push($attributes, array("name" => "src", "value" => "http://xstandard.com/images/logo.gif"));
		$objects[] = xs_add_object("logo.gif", "", "XStandard Web Site Logo", $attributes, $properties, "image", "", 0);



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
	
	if (get_magic_quotes_runtime() != 0) {
		if (set_magic_quotes_runtime(0) === false) {
			echo "Status: Error - magic_quotes_runtime must be turned off.";
			exit(0);
		}
	}

	// Do tests based on compression type
	if (XS_COMPRESSION_TYPE == "no compression") {
		// Create a temp folder and file and then remove it
		$temp = xs_random_name();
		if (!@mkdir(xs_build_path(XS_TEMP_FOLDER, $temp), 0777)) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [1] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [1] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}
	
		// Change file permissions
		@chmod(xs_build_path(XS_TEMP_FOLDER, $temp), 0777);

		// Simulate a received packet
		if (!xs_base64_to_file("SGVsbG8gV29ybGQh", xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "greetings.txt"))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [2] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [2] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}

		// Remove file
		if (!@unlink(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "greetings.txt"))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [4] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [4] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}

		// Remove temp folder
		if (!@rmdir(xs_build_path(XS_TEMP_FOLDER, $temp))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [5] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [5] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}
	} else {
		// Check for zlib package
		if (!extension_loaded("zlib")) {
			echo "Status: Error - zlib extension is not loaded.";
			exit(0);
		}

		// Create a temp folder and file and then remove it
		$temp = xs_random_name();
		if (!@mkdir(xs_build_path(XS_TEMP_FOLDER, $temp), 0777)) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [1] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [1] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}
	
		// Change file permissions
		@chmod(xs_build_path(XS_TEMP_FOLDER, $temp), 0777);

		// Simulate a received packet
		if (!xs_base64_to_file("UEsDBBQAAAAIAKd1Zi+jHCkcDgAAAAwAAAANAAAAZ3JlZXRpbmdzLnR4dPNIzcnJVwjPL8pJUQQA\nUEsBAhQAFAAAAAgAp3VmL6McKRwOAAAADAAAAA0AAAAAAAAAAQAgALaBAAAAAGdyZWV0aW5ncy50\neHRQSwUGAAAAAAEAAQA7AAAAOQAAAAAA", xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "archive.zip"))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [2] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [2] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}

		// Unpack archive
		$archive = new PclZip(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "archive.zip"));
		if ($archive->extract(xs_build_path(XS_TEMP_FOLDER, $temp)) == 0) {
			echo "Status: Error - Zip error: " . $archive->errorInfo(true);
			exit(0);
		}

		// Remove archive file
		if (!@unlink(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "archive.zip"))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [3] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [3] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}

		// Remove unzipped file
		if (!@unlink(xs_build_path(xs_build_path(XS_TEMP_FOLDER, $temp), "greetings.txt"))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [4] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [4] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}

		// Remove temp folder
		if (!@rmdir(xs_build_path(XS_TEMP_FOLDER, $temp))) {
			if(ini_get("safe_mode")) {
				echo "Status: Error - [5] PHP is running in Safe Mode. Make sure the temp folder (" . XS_TEMP_FOLDER . ") is a full path and sub-folder of a path in the php.ini 'safe_mode_include_dir' directive: " . ini_get("safe_mode_include_dir") . " You may also need to grant apache user ownership of the temp folder.";
				exit(0);
			} else {
				echo "Status: Error - [5] File permission incorrectly set on temp folder: " . XS_TEMP_FOLDER;
				exit(0);
			}
		}
	}

	echo "Status: Ready";
}
?> 
