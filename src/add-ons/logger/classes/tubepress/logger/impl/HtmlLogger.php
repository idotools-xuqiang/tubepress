<?php
/*
 * Copyright 2006 - 2016 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class tubepress_logger_impl_HtmlLogger implements tubepress_api_log_LoggerInterface
{
    /**
     * @var bool
     */
    private $_enabled;

    /**
     * @var string[]
     */
    private $_bootMessageBuffer;

    /**
     * @var bool
     */
    private $_shouldBuffer;

    /**
     * @var string
     */
    private $_timezone;

    public function __construct(tubepress_api_options_ContextInterface        $context,
                                tubepress_api_http_RequestParametersInterface $requestParams)
    {
        $loggingRequested         = $requestParams->hasParam('tubepress_debug') && $requestParams->getParamValue('tubepress_debug') === true;
        $loggingEnabled           = $context->get(tubepress_api_options_Names::DEBUG_ON);
        $this->_enabled           = $loggingRequested && $loggingEnabled;
        $this->_bootMessageBuffer = array();
        $this->_shouldBuffer      = true;

        $this->_timezone = new DateTimeZone(@date_default_timezone_get() ? @date_default_timezone_get() : 'UTC');
    }

    /**
     * {@inheritdoc}
     */
    public function onBootComplete()
    {
        if (!$this->_enabled) {

            unset($this->_bootMessageBuffer);

            return;
        }

        $this->_shouldBuffer = false;

        foreach ($this->_bootMessageBuffer as $message) {

            echo $message;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = array())
    {
        $this->_write($message, $context, false);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = array())
    {
        $this->_write($message, $context, true);
    }

    private function _write($message, array $context, $error)
    {
        if (!$this->_enabled) {

            return;
        }

        $finalMessage = $this->_getFormattedMessage($message, $context, $error);

        if ($this->_shouldBuffer) {

            $this->_bootMessageBuffer[] = $finalMessage;

        } else {

            echo $finalMessage;
        }
    }

    private function _getFormattedMessage($message, array $context, $error)
    {
        $dateTime      = $this->_createDateTimeFromFormat();
        $formattedTime = $dateTime->format('H:i:s.u');
        $level         = $error ? 'ERROR' : 'INFO';
        $color         = $error ? 'red' : 'inherit';

        if (!empty($context)) {

            $message .= ' ' . json_encode($context);
        }

        return "<span style=\"color: $color\">[$formattedTime - $level] $message</span><br />\n";
    }

    /**
     * This is here for testing purposes.
     */
    public function ___write($message, array $context, $error)
    {
        $this->_write($message, $context, $error);
    }

    /**
     * @return DateTime
     */
    private function _createDateTimeFromFormat()
    {
        $toReturn = DateTime::createFromFormat(

            'U.u',
            sprintf('%.6F', microtime(true)),
            $this->_timezone
        );

        $toReturn->setTimezone($this->_timezone);

        return $toReturn;
    }
}
