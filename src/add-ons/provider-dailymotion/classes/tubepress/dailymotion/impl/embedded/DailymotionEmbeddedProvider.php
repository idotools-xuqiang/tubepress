<?php
/**
 * Copyright 2006 - 2016 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Embedded player command for native Vimeo
 */
class tubepress_dailymotion_impl_embedded_DailymotionEmbeddedProvider implements tubepress_spi_embedded_EmbeddedProviderInterface, tubepress_spi_template_PathProviderInterface
{
    private static $_URL_PARAM_ID               = 'id';
    private static $_URL_PARAM_AUTOPLAY         = 'autoplay';
    private static $_URL_PARAM_UI_START_SCREEN  = 'ui-start_screen_info';

    /**
     * @var tubepress_api_options_ContextInterface
     */
    private $_context;

    /**
     * @var tubepress_api_util_LangUtilsInterface
     */
    private $_langUtils;

    /**
     * @var tubepress_api_url_UrlFactoryInterface
     */
    private $_urlFactory;

    public function __construct(tubepress_api_options_ContextInterface $context,
                                tubepress_api_util_LangUtilsInterface  $langUtils,
                                tubepress_api_url_UrlFactoryInterface  $urlFactory)
    {
        $this->_context    = $context;
        $this->_langUtils  = $langUtils;
        $this->_urlFactory = $urlFactory;
    }

    /**
     * @return string[] The names of the media providers that this provider can handle.
     *
     * @api
     * @since 4.0.0
     */
    public function getCompatibleMediaProviderNames()
    {
        return array(
            'dailymotion',
        );
    }

    /**
     * @return string The name of this embedded provider.
     *
     * @api
     * @since 4.0.0
     */
    public function getName()
    {
        return 'dailymotion';
    }

    /**
     * @return string The template name for this provider.
     *
     * @api
     * @since 4.0.0
     */
    public function getTemplateName()
    {
        return 'single/embedded/dailymotion_iframe';
    }

    /**
     * @param tubepress_api_media_MediaItem $mediaItem
     *
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getTemplateVariables(tubepress_api_media_MediaItem $mediaItem)
    {
        $playerId = $this->_context->get(tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID);

        if (!$playerId) {

            $playerId = 'tubepress-player-dailymotion-' . (string) md5(mt_rand());
        }

        return array(

            tubepress_api_template_VariableNames::EMBEDDED_DATA_URL => $this->_getDataUrl($mediaItem, $playerId),
            'player_id'                                             => $playerId,
        );
    }

    /**
     * @return string The display name of this embedded provider.
     *
     * @api
     * @since 4.0.0
     */
    public function getUntranslatedDisplayName()
    {
        return 'Dailymotion';
    }

    /**
     * @return string[] A set of absolute filesystem directory paths
     *
     * @api
     * @since 4.0.0
     */
    public function getTemplateDirectories()
    {
        return array(

            TUBEPRESS_ROOT . '/src/add-ons/provider-dailymotion/templates'
        );
    }

    private function _getDataUrl(tubepress_api_media_MediaItem $mediaItem, $playerId)
    {
        $autoPlay = $this->_context->get(tubepress_api_options_Names::EMBEDDED_AUTOPLAY);
        $showInfo = $this->_context->get(tubepress_api_options_Names::EMBEDDED_SHOW_INFO);

        /* build the data URL based on these options */
        $link  = $this->_urlFactory->fromString('https://www.dailymotion.com/embed/video/' . $mediaItem->getId());
        $query = $link->getQuery();

        $query->set(self::$_URL_PARAM_AUTOPLAY,         $this->_langUtils->booleanToStringOneOrZero($autoPlay));
        $query->set(self::$_URL_PARAM_UI_START_SCREEN,  $this->_langUtils->booleanToStringOneOrZero($showInfo));
        $query->set(self::$_URL_PARAM_ID,               $playerId);

        return $link;
    }
}
