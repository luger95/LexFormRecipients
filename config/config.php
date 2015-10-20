<?php

/**
 * Form recipients.
 * 
 * Copyright (C) 2005-2012 Enisseo
 * 
 * @package   Contao\LexFormRecipients
 * @author    Luger95 <normand-alexandre@orange.fr>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Luger95 2015
 */


/**
 * Form fields
 */
$GLOBALS['TL_FFL']['recipients'] = 'LexFormRecipients';

/**
 * Callback
 */
$GLOBALS['TL_HOOKS']['processFormData'][] = array('Contao\LexFormRecipients\Recipients', 'processFormData');
