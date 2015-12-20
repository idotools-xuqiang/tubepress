<?php
/**
 * Copyright 2006 - 2015 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 *
 */
class tubepress_http_oauth2_impl_options_ui_ClientSecretField extends tubepress_options_ui_impl_fields_templated_AbstractTemplatedField
{
    /**
     * @var tubepress_http_oauth2_impl_util_PersistenceHelper
     */
    private $_persistenceHelper;

    /**
     * @var tubepress_spi_http_oauth2_Oauth2ProviderInterface
     */
    private $_provider;

    /**
     * @var tubepress_api_translation_TranslatorInterface
     */
    private $_translator;

    public function __construct(tubepress_spi_http_oauth2_Oauth2ProviderInterface $provider,
                                tubepress_api_options_PersistenceInterface        $persistence,
                                tubepress_api_http_RequestParametersInterface     $requestParams,
                                tubepress_api_template_TemplatingInterface        $templating,
                                tubepress_http_oauth2_impl_util_PersistenceHelper $persistenceHelper)
    {
        parent::__construct('clientSecret_' . $provider->getName(), $persistence, $requestParams, $templating, $provider->getUntranslatedTermForClientSecret());

        $this->_persistenceHelper = $persistenceHelper;
        $this->_provider          = $provider;
    }

    /**
     * @return string The absolute path to the template for this field.
     */
    protected function getTemplateName()
    {
        return 'options-ui/fields/oauth2/client-secret';
    }

    /**
     * @return array An associative array of template variables for this field.
     */
    protected function getTemplateVariables()
    {
        $clientId = $this->_persistenceHelper->getClientSecret($this->_provider);

        return array(

            'id'    => $this->getId(),
            'value' => $clientId
        );
    }

    /**
     * Invoked when the element is submitted by the user.
     *
     * @return string|null A string error message to be displayed to the user, or null if no problem.
     *
     * @api
     * @since 4.0.0
     */
    public function onSubmit()
    {
        return null;
    }

    /**
     * Gets whether or not this field is TubePress Pro only.
     *
     * @return boolean True if this field is TubePress Pro only. False otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function isProOnly()
    {
        return false;
    }
}