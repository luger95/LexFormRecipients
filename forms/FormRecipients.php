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

namespace Contao;


/**
 * Class FormRecipients
 *
 * @package   Contao\LexFormRecipients
 * @author    Luger95 <normand-alexandre@orange.fr>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Luger95 2015
 */
class FormRecipients extends \FormSelectMenu
{

    /**
     * Email addresses
     * @var array
     */
    protected $arrEmails = array();


    /**
     * Add specific attributes
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'options':
                $options = deserialize($varValue);
                $emails = array();
                for ($e = count($options) - 1; $e >= 0; $e--)
                {
                    $emailId = 'email-' . $e;
                    $emails[$emailId] = $options[$e]['value'];
                    $options[$e]['value'] = $emailId;
                }
                $this->arrEmails = $emails;
                $this->arrOptions = $options;
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }


    /**
     * Return a parameter
     * @param string
     * @return string
     */
    public function __get($strKey)
    {
        switch ($strKey)
        {
            case 'value':
                $value = (array) $this->varValue;
                foreach ($value as &$val)
                {
                    if (isset($this->arrEmails[$val]))
                        $val = $this->arrEmails[$val];
                }
                return is_array($this->varValue)? $value: (count($value)? $value[0]: null);
                break;

            default:
                return parent::__get($strKey);
                break;
        }
    }
}
