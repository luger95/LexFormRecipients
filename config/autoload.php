<?php

/**
 * Form recipients.
 * 
 * Copyright (C) 2015 Luger95
 * 
 * @package   Contao\LexFormRecipients
 * @author    Luger95 <normand-alexandre@orange.fr>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Luger95 2015
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Contao\LexFormRecipients',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Forms
	'Contao\LexFormRecipients'             => 'system/modules/formrecipients/forms/LexFormRecipients.php',
	
	// Classes
	'Contao\LexFormRecipients\Recipients'	=> 'system/modules/formrecipients/classes/Recipients.php',
));

