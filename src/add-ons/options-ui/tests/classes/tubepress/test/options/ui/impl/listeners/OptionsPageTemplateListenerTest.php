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

/**
 * @covers tubepress_options_ui_impl_listeners_OptionsPageTemplateListener
 */
class tubepress_test_options_ui_impl_listeners_OptionsPageTemplateListenerTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var Mockery\MockInterface
     */
    private $_mockIncomingEvent;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockEnvironment;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockFieldProviderVimeo;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockMediaProviderVimeo;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockMediaProviderYouTube;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockFieldProviderPlayer;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockCategoryEmbedded;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockCategoryGallerySource;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockTranslator;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockBaseUrl;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockStringUtils;

    /**
     * @var tubepress_options_ui_impl_listeners_OptionsPageTemplateListener
     */
    private $_sut;

    /**
     * @var array
     */
    private $_fieldsVar;

    public function onSetup()
    {
        $this->_mockIncomingEvent         = $this->mock('tubepress_api_event_EventInterface');
        $this->_mockEnvironment           = $this->mock(tubepress_api_environment_EnvironmentInterface::_);
        $this->_mockFieldProviderVimeo    = $this->mock('tubepress_spi_options_ui_FieldProviderInterface');
        $this->_mockFieldProviderPlayer   = $this->mock('tubepress_spi_options_ui_FieldProviderInterface');
        $this->_mockMediaProviderVimeo    = $this->mock('tubepress_spi_media_MediaProviderInterface');
        $this->_mockMediaProviderYouTube  = $this->mock('tubepress_spi_media_MediaProviderInterface');
        $this->_mockCategoryEmbedded      = $this->mock('tubepress_api_options_ui_ElementInterface');
        $this->_mockCategoryGallerySource = $this->mock('tubepress_api_options_ui_ElementInterface');
        $this->_mockTranslator            = $this->mock(tubepress_api_translation_TranslatorInterface::_);
        $this->_mockStringUtils           = $this->mock(tubepress_api_util_StringUtilsInterface::_);

        $this->_sut = new tubepress_options_ui_impl_listeners_OptionsPageTemplateListener(
            $this->_mockEnvironment,
            $this->_mockTranslator,
            $this->_mockStringUtils
        );

        $this->_sut->setFieldProviders(array($this->_mockFieldProviderVimeo, $this->_mockFieldProviderPlayer));
        $this->_sut->setMediaProviders(array($this->_mockMediaProviderVimeo, $this->_mockMediaProviderYouTube));
    }

    public function testEvent()
    {
        $this->_prepMockCategories();
        $this->_prepMockFieldProviders();
        $this->_prepEnvironment();
        $this->_prepEvent();
        $this->_prepTranslator();
        $this->_prepMediaProviders();
        $this->_prepStringUtils();

        $this->_sut->onOptionsGuiTemplate($this->_mockIncomingEvent);

        $this->assertTrue(true);
    }

    private function _prepStringUtils()
    {
        $stringUtils = new tubepress_util_impl_StringUtils();

        $this->_mockStringUtils->shouldReceive('startsWith')->andReturnUsing(array($stringUtils, 'startsWith'));
    }

    private function _prepMediaProviders()
    {
        $this->_mockMediaProviderYouTube->shouldReceive('getDisplayName')->once()->andReturn('YouTube');
        $this->_mockMediaProviderVimeo->shouldReceive('getDisplayName')->once()->andReturn('Vimeo');
        $this->_mockMediaProviderYouTube->shouldReceive('getName')->once()->andReturn('youtube-media-provider');
        $this->_mockMediaProviderVimeo->shouldReceive('getName')->once()->andReturn('vimeo-media-provider');

        $ytProps    = new tubepress_internal_collection_Map();
        $vimeoProps = new tubepress_internal_collection_Map();

        $ytProps->put('miniIconUrl', 'yt-icon');
        $vimeoProps->put('miniIconUrl', 'vimeo-icon');
        $ytProps->put('untranslatedModeTemplateMap', array(
            'tag'  => 'tag template',
            'user' => 'user template',
        ));
        $vimeoProps->put('untranslatedModeTemplateMap', array(
            'vimeoChannel' => 'template for channel',
            'vimeoAlbum'   => 'template for album',
        ));

        $this->_mockMediaProviderYouTube->shouldReceive('getGallerySourceNames')->once()->andReturn(array(
            'tag', 'user',
        ));
        $this->_mockMediaProviderVimeo->shouldReceive('getGallerySourceNames')->once()->andReturn(array(
            'vimeoChannel', 'vimeoAlbum',
        ));

        $this->_mockMediaProviderYouTube->shouldReceive('getProperties')->times(4)->andReturn($ytProps);
        $this->_mockMediaProviderVimeo->shouldReceive('getProperties')->times(4)->andReturn($vimeoProps);
    }

    private function _prepTranslator()
    {

    }

    private function _prepEnvironment()
    {
        $this->_mockBaseUrl = $this->mock(tubepress_api_url_UrlInterface::_);

        $this->_mockEnvironment->shouldReceive('isPro')->once()->andReturn(true);
        $this->_mockEnvironment->shouldReceive('getBaseUrl')->once()->andReturn($this->_mockBaseUrl);
    }

    private function _prepEvent()
    {
        $mockVimeoGallerySourceField1       = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockVimeoGallerySourceField2       = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockVimeoEmbeddedMultiSourceField1 = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockVimeoEmbeddedMultiSourceField2 = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockCoreGallerySourceField1        = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockCoreGallerySourceField2        = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockCoreEmbeddedMultiSourceField1  = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');
        $mockCoreEmbeddedMultiSourceField2  = $this->mock('tubepress_api_options_ui_MultiSourceFieldInterface');

        $mockVimeoGallerySourceField1->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-999999-vimeoGallerySource');
        $mockVimeoGallerySourceField2->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-888888-vimeoGallerySource');
        $mockVimeoEmbeddedMultiSourceField1->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-999999-vimeoEmbeddedOption');
        $mockVimeoEmbeddedMultiSourceField2->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-888888-vimeoEmbeddedOption');
        $mockCoreGallerySourceField1->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-999999-coreGallerySource');
        $mockCoreGallerySourceField2->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-888888-coreGallerySource');
        $mockCoreEmbeddedMultiSourceField1->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-999999-coreEmbeddedOption');
        $mockCoreEmbeddedMultiSourceField2->shouldReceive('getId')->atLeast(1)->andReturn('tubepress-multisource-888888-coreEmbeddedOption');

        $this->_fieldsVar = array(

            'tubepress-multisource-999999-vimeoGallerySource'  => $mockVimeoGallerySourceField1,
            'tubepress-multisource-888888-vimeoGallerySource'  => $mockVimeoGallerySourceField2,
            'tubepress-multisource-999999-vimeoEmbeddedOption' => $mockVimeoEmbeddedMultiSourceField1,
            'tubepress-multisource-888888-vimeoEmbeddedOption' => $mockVimeoEmbeddedMultiSourceField2,
            'tubepress-multisource-999999-coreGallerySource'   => $mockCoreGallerySourceField1,
            'tubepress-multisource-888888-coreGallerySource'   => $mockCoreGallerySourceField2,
            'tubepress-multisource-999999-coreEmbeddedOption'  => $mockCoreEmbeddedMultiSourceField1,
            'tubepress-multisource-888888-coreEmbeddedOption'  => $mockCoreEmbeddedMultiSourceField2,
        );

        $this->_mockIncomingEvent->shouldReceive('getSubject')->once()->andReturn(array(
            'foo'    => 'bar',
            'fields' => $this->_fieldsVar,
        ));

        $gallerySourceCategory = $this->_mockCategoryGallerySource;
        $embeddedCategory      = $this->_mockCategoryEmbedded;
        $vimeoFieldProvider    = $this->_mockFieldProviderVimeo;
        $playerFieldProvider   = $this->_mockFieldProviderPlayer;
        $baseUrl               = $this->_mockBaseUrl;
        $fieldsVar             = $this->_fieldsVar;

        $this->_mockIncomingEvent->shouldReceive('setSubject')->once()->with(Mockery::on(function ($candidate) use (

            $gallerySourceCategory,
            $embeddedCategory,
            $vimeoFieldProvider,
            $playerFieldProvider,
            $baseUrl,
            $fieldsVar
        ) {

            if (!is_array($candidate)) {

                return false;
            }

            if ($candidate['foo'] !== 'bar') {

                return false;
            }

            if ($candidate['categories'] !== array(
                    $gallerySourceCategory,
                    $embeddedCategory, )) {

                return false;
            }

            if ($candidate['categoryIdToProviderIdToFieldsMap'] !== array(
                    tubepress_api_options_ui_CategoryNames::EMBEDDED => array(
                        'field-provider-player' => array(
                            'coreEmbeddedOption',
                        ),
                        'field-provider-vimeo' => array(
                            'vimeoEmbeddedOption',
                        ),
                    ),
                    tubepress_api_options_ui_CategoryNames::GALLERY_SOURCE => array(
                        'field-provider-player' => array(
                            'coreGallerySource',
                        ),
                        'field-provider-vimeo' => array(
                            'vimeoGallerySource',
                        ),
                    ),
                )) {

                return false;
            }

            if ($candidate['fieldProviders'] !== array(
                    'field-provider-vimeo' => $vimeoFieldProvider,
                    'field-provider-player' => $playerFieldProvider, )) {

                return false;
            }

            if ($candidate['baseUrl'] !== $baseUrl) {

                return false;
            }

            if ($candidate['isPro'] !== true) {

                return false;
            }

            if ($candidate['fields'] !== $fieldsVar) {

                return false;
            }

            if (!is_array($candidate['gallerySources']) || count($candidate['gallerySources']) !== 2) {

                return false;
            }

            $firstSource = $candidate['gallerySources'][0];

            if (!is_array($firstSource)) {

                return false;
            }

            if ($firstSource['id'] !== 999999) {

                return false;
            }

            if ($candidate['mediaProviderPropertiesAsJson'] !== '{"vimeo-media-provider":{"displayName":"Vimeo","sourceNames":["vimeoChannel","vimeoAlbum"],"miniIconUrl":"vimeo-icon","untranslatedModeTemplateMap":{"vimeoChannel":"template for channel","vimeoAlbum":"template for album"}},"youtube-media-provider":{"displayName":"YouTube","sourceNames":["tag","user"],"miniIconUrl":"yt-icon","untranslatedModeTemplateMap":{"tag":"tag template","user":"user template"}}}') {

            }

            return true;
        }));
    }

    private function _prepMockFieldProviders()
    {
        $this->_mockFieldProviderVimeo->shouldReceive('getId')->atLeast(1)->andReturn('field-provider-vimeo');
        $this->_mockFieldProviderPlayer->shouldReceive('getId')->atLeast(1)->andReturn('field-provider-player');

        $this->_mockFieldProviderVimeo->shouldReceive('getCategories')->atLeast(1)->andReturn(array(
            $this->_mockCategoryEmbedded,
            $this->_mockCategoryGallerySource,
        ));

        $this->_mockFieldProviderPlayer->shouldReceive('getCategories')->atLeast(1)->andReturn(array(
            $this->_mockCategoryGallerySource,
        ));

        $this->_mockFieldProviderVimeo->shouldReceive('getCategoryIdsToFieldIdsMap')->atLeast(1)->andReturn(array(

            tubepress_api_options_ui_CategoryNames::EMBEDDED => array(

                'vimeoEmbeddedOption',
            ),
            tubepress_api_options_ui_CategoryNames::GALLERY_SOURCE => array(
                'vimeoGallerySource',
            ),
        ));

        $this->_mockFieldProviderPlayer->shouldReceive('getCategoryIdsToFieldIdsMap')->atLeast(1)->andReturn(array(

            tubepress_api_options_ui_CategoryNames::GALLERY_SOURCE => array(
                'coreGallerySource',
            ),
            tubepress_api_options_ui_CategoryNames::EMBEDDED => array(
                'coreEmbeddedOption',
            ),
        ));
    }

    private function _prepMockCategories()
    {
        $this->_mockCategoryEmbedded->shouldReceive('__toString')->andReturn('category-1-as-string');
        $this->_mockCategoryGallerySource->shouldReceive('__toString')->andReturn('category-2-as-string');

        $this->_mockCategoryEmbedded->shouldReceive('getId')->atLeast(1)->andReturn(tubepress_api_options_ui_CategoryNames::EMBEDDED);
        $this->_mockCategoryGallerySource->shouldReceive('getId')->atLeast(1)->andReturn(tubepress_api_options_ui_CategoryNames::GALLERY_SOURCE);
    }

}
