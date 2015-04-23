<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	// Classes
	'Contao\LexFormRecipients\Recipients' => 'system/modules/LexFormRecipients/classes/Recipients.php',

	// Forms
	'Contao\LexFormRecipients'            => 'system/modules/LexFormRecipients/forms/LexFormRecipients.php',
));
