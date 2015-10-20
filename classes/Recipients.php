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

namespace Contao\LexFormRecipients;

/**
 * Class Recipients
 *
 * Form field "recipients".
 * @author    Luger95 <normand-alexandre@orange.fr> 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Luger95 2015
 */
class Recipients extends \Controller
{
    /**
     * Send emails if form contains recipients fields.
     */
    public function processFormData($arrData, $formData, $arrFiles, $arrLabels)
    {
        $this->import('Database');

        $objFormRecipientsField = \FormFieldModel::findAll(array(
            'column'=> array('invisible != ?','pid = ?','type = ?'),
            'value' => array(1, $formData['id'],'recipients'),
        ));
        if (is_null($objFormRecipientsField))
            return;

        $arrSubmitted = null;

        $this->import('Input');
        $this->import('String');

        while ($objFormRecipientsField->next())
        {
            // Initialize $arrSubmitted array
            if (is_null($arrSubmitted))
            {
                $arrSubmitted = array();

                $objFields = \FormFieldModel::findPublishedByPid($formData['id']);
                if ($objFields != null)
                {
                    while ($objFields->next())
                    {
                        $strClass = $GLOBALS['TL_FFL'][$objFields->type];

                        // Continue if the class is not defined
                        if (!$this->classFileExists($strClass))
                            continue;

                        if (isset($arrData[$objFields->name]))
                            $arrSubmitted[$objFields->name] = $arrData[$objFields->name];
                    }
                }
            }

            $recipients = array();

            $keys = array();
            $values = array();
            $fields = array();
            $message = '';

            foreach ($arrSubmitted as $k=>$v)
            {
                if ($k == 'cc')
                    continue;

                if ($k == $objFormRecipientsField->name)
                    $recipients = (array) $v;

                $v = deserialize($v);

                // Skip empty fields
                if ($objFormRecipientsField->skipEmpty && !is_array($v) && !strlen($v))
                    continue;

                // Add field to message
                if('destinataire' === $k)
                {   foreach (unserialize($objFormRecipientsField->options) as $key => $option)
                        if($option['value'] == $v)
                            $subjet = $option['label'];
                }
                else{
                    $message .= (isset($arrLabels[$k]) && $arrLabels[$k] ? $arrLabels[$k] : ucfirst($k)) . ': ' . (is_array($v) ? implode(', ', $v) : $v) . "\n";
                }

                // Prepare XML file
                if ($objFormRecipientsField->format == 'xml')
                    $fields[] = array('name' => $k,'values' => (is_array($v) ? $v : array($v)));

                // Prepare CSV file
                if ($objFormRecipientsField->format == 'csv')
                {
                    $keys[] = $k;
                    $values[] = (is_array($v) ? implode(',', $v) : $v);
                }
            }

            if (empty($recipients))
                continue;

            $this->import('Email');
            $email = new \Contao\Email();

            if(isset($subjet))
            {
                $email->subject = $subjet;
            }

            // Set the admin e-mail as "from" address
            $email->from = $GLOBALS['TL_ADMIN_EMAIL'];
            $email->fromName = $GLOBALS['TL_ADMIN_NAME'];

            // Get the "reply to" address
            if (strlen(\Input::post('email', true)))
            {
                $replyTo = \Input::post('email', true);

                // Add name
                if (strlen(\Input::post('name')))
                    $replyTo = '"' . \Input::post('name') . '" <' . $replyTo . '>';

                $email->replyTo($replyTo);
            }

            // Fallback to default subject
            if (!strlen($email->subject))
                $email->subject = $this->replaceInsertTags($objFormRecipientsField->subject);

            // Send copy to sender
            if (strlen($arrData['cc']))
            {
                $email->sendCc(\Input::post('email', true));
                unset($_SESSION['FORM_DATA']['cc']);
            }

            // Attach XML file
            if ($objFormRecipientsField->format == 'xml')
            {
                $objTemplate = new \FrontendTemplate('form_xml');

                $objTemplate->fields = $fields;
                $objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];

                $email->attachFileFromString($objTemplate->parse(), 'form.xml', 'application/xml');
            }

            // Attach CSV file
            if ($objFormRecipientsField->format == 'csv')
                $email->attachFileFromString(\String::decodeEntities('"' . implode('";"', $keys) . '"' . "\n" . '"' . implode('";"', $values) . '"'), 'form.csv', 'text/comma-separated-values');

            $uploaded = '';

            // Attach uploaded files
            if (!empty($arrFiles))
            {
                foreach ($arrFiles as $file)
                {
                    // Add a link to the uploaded file
                    if ($file['uploaded'])
                    {
                        $uploaded .= "\n" . \Environment::get('base') . str_replace(TL_ROOT . '/', '', dirname($file['tmp_name'])) . '/' . rawurlencode($file['name']);
                        continue;
                    }

                    $email->attachFileFromString(file_get_contents($file['tmp_name']), $file['name'], $file['type']);
                }
            }

            $uploaded = strlen(trim($uploaded)) ? "\n\n---\n" . $uploaded : '';

            $re = "/^([0-9]+-)/";                
            $a_recipients = array_map('trim',explode(',', $recipients[0]));
            foreach ($a_recipients as $key => $recipients_email)
            {
                preg_match($re, $recipients_email, $matches);
                $a_recipients[$key] = trim(str_replace($matches[0], '', $recipients_email));
            }
            $recipients = $a_recipients;

            $text = \String::decodeEntities(trim($message)) . $uploaded . "\n\n";

            // Send e-mail
            $email->text = $text;
            $email->sendTo($recipients);
        }
    }
}