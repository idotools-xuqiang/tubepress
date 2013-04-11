<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
class tubepress_addons_core_impl_patterns_ioc_IocContainerExtensionTest extends TubePressUnitTest
{
    /**
     * @var tubepress_addons_core_impl_patterns_ioc_IocContainerExtension
     */
    private $_sut;

    /**
     * @var ehough_iconic_ContainerBuilder
     */
    private $_mockParentContainer;

    public function onSetup()
    {
        $this->_sut = new tubepress_addons_core_impl_patterns_ioc_IocContainerExtension();

        $this->_mockParentContainer = new ehough_iconic_ContainerBuilder();
        $this->_mockParentContainer->register('ehough_tickertape_EventDispatcherInterface', 'ehough_tickertape_EventDispatcher');
    }

    public function testGetAlias()
    {
        $this->assertEquals('core', $this->_sut->getAlias());
    }

    public function testLoad()
    {
        $this->_sut->load(array(), $this->_mockParentContainer);

        foreach ($this->_getExpectedServices() as $service) {

            $definition = $this->_mockParentContainer->getDefinition($service->id);

            $this->assertNotNull($definition);

            $this->assertTrue($definition->getClass() === $service->type);

            if (isset($service->tag)) {

                $this->assertTrue($definition->hasTag($service->tag));
            }
        }

        foreach ($this->_getExpectedAliases() as $expectedAliasMap) {

            $aliasedService = $this->_mockParentContainer->get($expectedAliasMap[1]);
            $originalService = $this->_mockParentContainer->get($expectedAliasMap[0]);

            $this->assertSame($originalService, $aliasedService);
        }
    }

    private function _getExpectedAliases()
    {
        return array(

            array(tubepress_spi_http_AjaxHandler::_, 'tubepress_impl_http_DefaultAjaxHandler'),
            array('ehough_stash_PoolInterface', 'ehough_stash_Pool'),
            array(tubepress_spi_embedded_EmbeddedHtmlGenerator::_, 'tubepress_impl_embedded_DefaultEmbeddedPlayerHtmlGenerator'),
            array(tubepress_spi_context_ExecutionContext::_, 'tubepress_impl_context_MemoryExecutionContext'),
            array('ehough_filesystem_FilesystemInterface', 'ehough_filesystem_Filesystem'),
            array(tubepress_spi_feed_FeedFetcher::_, 'tubepress_impl_feed_CacheAwareFeedFetcher'),
            array(tubepress_spi_html_CssAndJsGenerator::_, 'tubepress_impl_html_DefaultCssAndJsGenerator'),
            array('ehough_shortstop_impl_exec_DefaultHttpMessageParser', 'ehough_shortstop_impl_exec_DefaultHttpMessageParser'),
            array('ehough_shortstop_api_HttpClientInterface', 'ehough_shortstop_impl_DefaultHttpClient'),
            array(tubepress_spi_http_HttpRequestParameterService::_, 'tubepress_impl_http_DefaultHttpRequestParameterService'),
            array(tubepress_spi_http_ResponseCodeHandler::_, 'tubepress_impl_http_DefaultResponseCodeHandler'),
            array(tubepress_spi_options_OptionDescriptorReference::_, 'tubepress_impl_options_DefaultOptionDescriptorReference'),
            array(tubepress_spi_options_OptionValidator::_, 'tubepress_impl_options_DefaultOptionValidator'),
            array(tubepress_spi_options_ui_FieldBuilder::_, 'tubepress_impl_options_ui_DefaultFieldBuilder'),
            array(tubepress_spi_player_PlayerHtmlGenerator::_, 'tubepress_impl_player_DefaultPlayerHtmlGenerator'),
            array(tubepress_spi_querystring_QueryStringService::_, 'tubepress_impl_querystring_SimpleQueryStringService'),
            array(tubepress_spi_shortcode_ShortcodeHtmlGenerator::_, 'tubepress_impl_shortcode_DefaultShortcodeHtmlGenerator'),
            array(tubepress_spi_shortcode_ShortcodeParser::_, 'tubepress_impl_shortcode_SimpleShortcodeParser'),
            array('ehough_contemplate_api_TemplateBuilder', 'ehough_contemplate_impl_SimpleTemplateBuilder'),
            array(tubepress_spi_theme_ThemeHandler::_, 'tubepress_impl_theme_SimpleThemeHandler'),
            array(tubepress_spi_collector_VideoCollector::_, 'tubepress_impl_collector_DefaultVideoCollector'),
        );
    }

    private function _getExpectedServices()
    {
        $map = array(

            array(tubepress_spi_http_AjaxHandler::_, 'tubepress_impl_http_DefaultAjaxHandler'),
            array('ehough_stash_PoolInterface', 'ehough_stash_Pool'),
            array(tubepress_spi_embedded_EmbeddedHtmlGenerator::_, 'tubepress_impl_embedded_DefaultEmbeddedPlayerHtmlGenerator'),
            array(tubepress_spi_context_ExecutionContext::_, 'tubepress_impl_context_MemoryExecutionContext'),
            array('ehough_filesystem_FilesystemInterface', 'ehough_filesystem_Filesystem'),
            array(tubepress_spi_feed_FeedFetcher::_, 'tubepress_impl_feed_CacheAwareFeedFetcher'),
            array(tubepress_spi_html_CssAndJsGenerator::_, 'tubepress_impl_html_DefaultCssAndJsGenerator'),
            array('ehough_shortstop_impl_exec_DefaultHttpMessageParser', 'ehough_shortstop_impl_exec_DefaultHttpMessageParser'),
            array('ehough_shortstop_impl_exec_command_ExtCommand', 'ehough_shortstop_impl_exec_command_ExtCommand'),
            array('ehough_shortstop_impl_exec_command_CurlCommand', 'ehough_shortstop_impl_exec_command_CurlCommand'),
            array('ehough_shortstop_impl_exec_command_StreamsCommand', 'ehough_shortstop_impl_exec_command_StreamsCommand'),
            array('ehough_shortstop_impl_exec_command_FsockOpenCommand', 'ehough_shortstop_impl_exec_command_FsockOpenCommand'),
            array('ehough_shortstop_impl_exec_command_FopenCommand', 'ehough_shortstop_impl_exec_command_FopenCommand'),
            array('ehough_shortstop_impl_decoding_content_command_NativeGzipDecompressingCommand', 'ehough_shortstop_impl_decoding_content_command_NativeGzipDecompressingCommand'),
            array('ehough_shortstop_impl_decoding_content_command_SimulatedGzipDecompressingCommand', 'ehough_shortstop_impl_decoding_content_command_SimulatedGzipDecompressingCommand'),
            array('ehough_shortstop_impl_decoding_content_command_NativeDeflateRfc1950DecompressingCommand', 'ehough_shortstop_impl_decoding_content_command_NativeDeflateRfc1950DecompressingCommand'),
            array('ehough_shortstop_impl_decoding_content_command_NativeDeflateRfc1951DecompressingCommand', 'ehough_shortstop_impl_decoding_content_command_NativeDeflateRfc1951DecompressingCommand'),
            array('ehough_shortstop_impl_decoding_transfer_command_ChunkedTransferDecodingCommand', 'ehough_shortstop_impl_decoding_transfer_command_ChunkedTransferDecodingCommand'),
            array('ehough_shortstop_api_HttpClientInterface', 'ehough_shortstop_impl_DefaultHttpClient'),
            array(tubepress_spi_http_HttpRequestParameterService::_, 'tubepress_impl_http_DefaultHttpRequestParameterService'),
            array(tubepress_spi_http_ResponseCodeHandler::_, 'tubepress_impl_http_DefaultResponseCodeHandler'),
            array(tubepress_spi_options_OptionDescriptorReference::_, 'tubepress_impl_options_DefaultOptionDescriptorReference'),
            array(tubepress_spi_options_OptionValidator::_, 'tubepress_impl_options_DefaultOptionValidator'),
            array(tubepress_spi_options_ui_FieldBuilder::_, 'tubepress_impl_options_ui_DefaultFieldBuilder'),
            array(tubepress_spi_player_PlayerHtmlGenerator::_, 'tubepress_impl_player_DefaultPlayerHtmlGenerator'),
            array(tubepress_spi_querystring_QueryStringService::_, 'tubepress_impl_querystring_SimpleQueryStringService'),
            array(tubepress_spi_shortcode_ShortcodeHtmlGenerator::_, 'tubepress_impl_shortcode_DefaultShortcodeHtmlGenerator'),
            array(tubepress_spi_shortcode_ShortcodeParser::_, 'tubepress_impl_shortcode_SimpleShortcodeParser'),
            array('ehough_contemplate_api_TemplateBuilder', 'ehough_contemplate_impl_SimpleTemplateBuilder'),
            array(tubepress_spi_theme_ThemeHandler::_, 'tubepress_impl_theme_SimpleThemeHandler'),
            array(tubepress_spi_collector_VideoCollector::_, 'tubepress_impl_collector_DefaultVideoCollector'),
            array('tubepress_addons_core_impl_options_ui_CoreOptionsPageParticipant', 'tubepress_addons_core_impl_options_ui_CoreOptionsPageParticipant', tubepress_spi_options_ui_PluggableOptionsPageParticipant::_),
            array('tubepress_addons_core_impl_options_ui_CorePluggableFieldBuilder', 'tubepress_addons_core_impl_options_ui_CorePluggableFieldBuilder', tubepress_spi_options_ui_PluggableFieldBuilder::_),
            array('tubepress_addons_core_impl_http_PlayerPluggableAjaxCommandService', 'tubepress_addons_core_impl_http_PlayerPluggableAjaxCommandService', tubepress_spi_http_PluggableAjaxCommandService::_),
            array('tubepress_addons_core_impl_player_JqModalPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_JqModalPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_NormalPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_NormalPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_PopupPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_PopupPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_ShadowboxPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_ShadowboxPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_StaticPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_StaticPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_VimeoPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_VimeoPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_SoloPluggablePlayerLocationService', 'tubepress_addons_core_impl_player_SoloPluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_player_YouTubePluggablePlayerLocationService', 'tubepress_addons_core_impl_player_YouTubePluggablePlayerLocationService', tubepress_spi_player_PluggablePlayerLocationService::_),
            array('tubepress_addons_core_impl_shortcode_SearchInputPluggableShortcodeHandlerService', 'tubepress_addons_core_impl_shortcode_SearchInputPluggableShortcodeHandlerService', tubepress_spi_shortcode_PluggableShortcodeHandlerService::_),
            array('tubepress_addons_core_impl_shortcode_SearchOutputPluggableShortcodeHandlerService', 'tubepress_addons_core_impl_shortcode_SearchOutputPluggableShortcodeHandlerService', tubepress_spi_shortcode_PluggableShortcodeHandlerService::_),
            array('tubepress_addons_core_impl_shortcode_SingleVideoPluggableShortcodeHandlerService', 'tubepress_addons_core_impl_shortcode_SingleVideoPluggableShortcodeHandlerService', tubepress_spi_shortcode_PluggableShortcodeHandlerService::_),
            array('tubepress_addons_core_impl_shortcode_SoloPlayerPluggableShortcodeHandlerService', 'tubepress_addons_core_impl_shortcode_SoloPlayerPluggableShortcodeHandlerService', tubepress_spi_shortcode_PluggableShortcodeHandlerService::_),
            array('tubepress_addons_core_impl_shortcode_ThumbGalleryPluggableShortcodeHandlerService', 'tubepress_addons_core_impl_shortcode_ThumbGalleryPluggableShortcodeHandlerService', tubepress_spi_shortcode_PluggableShortcodeHandlerService::_),
            array('tubepress_addons_core_impl_listeners_embeddedhtml_PlayerJavaScriptApi','tubepress_addons_core_impl_listeners_embeddedhtml_PlayerJavaScriptApi'),
            array('tubepress_addons_core_impl_listeners_embeddedtemplate_CoreVariables', 'tubepress_addons_core_impl_listeners_embeddedtemplate_CoreVariables'),
            array('tubepress_addons_core_impl_listeners_galleryhtml_GalleryJs', 'tubepress_addons_core_impl_listeners_galleryhtml_GalleryJs'),
            array('tubepress_addons_core_impl_listeners_galleryinitjs_GalleryInitJsBaseParams', 'tubepress_addons_core_impl_listeners_galleryinitjs_GalleryInitJsBaseParams'),
            array('tubepress_addons_core_impl_listeners_gallerytemplate_CoreVariables', 'tubepress_addons_core_impl_listeners_gallerytemplate_CoreVariables'),
            array('tubepress_addons_core_impl_listeners_gallerytemplate_EmbeddedPlayerName', 'tubepress_addons_core_impl_listeners_gallerytemplate_EmbeddedPlayerName'),
            array('tubepress_addons_core_impl_listeners_gallerytemplate_Pagination', 'tubepress_addons_core_impl_listeners_gallerytemplate_Pagination'),
            array('tubepress_addons_core_impl_listeners_gallerytemplate_Player', 'tubepress_addons_core_impl_listeners_gallerytemplate_Player'),
            array('tubepress_addons_core_impl_listeners_gallerytemplate_VideoMeta', 'tubepress_addons_core_impl_listeners_gallerytemplate_VideoMeta'),
            array('tubepress_addons_core_impl_listeners_playertemplate_CoreVariables', 'tubepress_addons_core_impl_listeners_playertemplate_CoreVariables'),
            array('tubepress_addons_core_impl_listeners_prevalidationoptionset_StringMagic', 'tubepress_addons_core_impl_listeners_prevalidationoptionset_StringMagic'),
            array('tubepress_addons_core_impl_listeners_prevalidationoptionset_YouTubePlaylistPlPrefixRemover', 'tubepress_addons_core_impl_listeners_prevalidationoptionset_YouTubePlaylistPlPrefixRemover'),
            array('tubepress_addons_core_impl_listeners_searchinputtemplate_CoreVariables', 'tubepress_addons_core_impl_listeners_searchinputtemplate_CoreVariables'),
            array('tubepress_addons_core_impl_listeners_singlevideotemplate_CoreVariables', 'tubepress_addons_core_impl_listeners_singlevideotemplate_CoreVariables'),
            array('tubepress_addons_core_impl_listeners_singlevideotemplate_VideoMeta', 'tubepress_addons_core_impl_listeners_singlevideotemplate_VideoMeta'),
            array('tubepress_addons_core_impl_listeners_variablereadfromexternalinput_StringMagic', 'tubepress_addons_core_impl_listeners_variablereadfromexternalinput_StringMagic'),
            array('tubepress_addons_core_impl_listeners_videogallerypage_PerPageSorter', 'tubepress_addons_core_impl_listeners_videogallerypage_PerPageSorter'),
            array('tubepress_addons_core_impl_listeners_videogallerypage_ResultCountCapper', 'tubepress_addons_core_impl_listeners_videogallerypage_ResultCountCapper'),
            array('tubepress_addons_core_impl_listeners_videogallerypage_VideoBlacklist', 'tubepress_addons_core_impl_listeners_videogallerypage_VideoBlacklist'),
            array('tubepress_addons_core_impl_listeners_videogallerypage_VideoPrepender', 'tubepress_addons_core_impl_listeners_videogallerypage_VideoPrepender'),
        );


        $toReturn = array();

        foreach ($map as $s) {

            $service = new stdClass();
            $service->id = $s[0];
            $service->type = $s[1];

            if (isset($s[2])) {

                $service->tag = $s[2];
            }

            $toReturn[] = $service;
        }

        return $toReturn;
    }
}