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

$this->loadLanguageFile('tl_form');

/**
 * Table tl_form_field
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['recipients'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,multiple;{options_legend},options;{recipients_legend},subject,format,skipEmpty;{expert_legend:hide},class,accesskey,tabindex;{submit_legend},addSubmit';

array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields'], array_search('class', $GLOBALS['TL_DCA']['tl_form_field']['fields']) - 1, array(
    'subject' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form']['subject'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'format' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form']['format'],
        'default'                 => 'raw',
        'exclude'                 => true,
        'inputType'               => 'select',
        'options'                 => array('raw', 'xml', 'csv', 'email'),
        'reference'               => &$GLOBALS['TL_LANG']['tl_form'],
        'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50'),
        'sql'                     => "varchar(12) NOT NULL default ''"
    ),
    'skipEmpty' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'],
        'exclude'                 => true,
        'filter'                  => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('tl_class'=>'w50 m12'),
        'sql'                     => "char(1) NOT NULL default ''"
    ),
));