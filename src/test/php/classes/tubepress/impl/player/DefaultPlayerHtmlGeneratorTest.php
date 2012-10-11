<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class tubepress_impl_player_DefaultPlayerHtmlGeneratorTest extends TubePressUnitTest
{
    private $_sut;

    private $_mockVideo;

    private $_mockExecutionContext;

    private $_mockThemeHandler;

    private $_mockEventDispatcher;

    private $_mockServiceCollectionsRegistry;

    function setUp()
    {
        $this->_mockVideo              = new tubepress_api_video_Video();
        $this->_mockVideo->setAttribute(tubepress_api_video_Video::ATTRIBUTE_ID, 'video-id');
        $this->_mockEventDispatcher    = Mockery::mock('ehough_tickertape_api_IEventDispatcher');
        $this->_mockExecutionContext   = Mockery::mock(tubepress_spi_context_ExecutionContext::_);
        $this->_mockThemeHandler     = Mockery::mock(tubepress_spi_theme_ThemeHandler::_);
        $this->_mockServiceCollectionsRegistry = Mockery::mock(tubepress_spi_patterns_sl_ServiceCollectionsRegistry::_);

        tubepress_impl_patterns_ioc_KernelServiceLocator::setEventDispatcher($this->_mockEventDispatcher);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setExecutionContext($this->_mockExecutionContext);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setThemeHandler($this->_mockThemeHandler);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setServiceCollectionsRegistry($this->_mockServiceCollectionsRegistry);

        $this->_sut = new tubepress_impl_player_DefaultPlayerHtmlGenerator();
    }

    function testGetHtmlSuitablePlayerLocations()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_const_options_names_Embedded::PLAYER_LOCATION)->andReturn('x');

        $mockPlayerLocation = Mockery::mock(tubepress_spi_player_PluggablePlayerLocationService::_);
        $mockPlayerLocation->shouldReceive('getName')->andReturn('x');

        $mockPlayerLocations = array($mockPlayerLocation);

        $this->_mockServiceCollectionsRegistry->shouldReceive('getAllServicesOfType')->once()->with(tubepress_spi_player_PluggablePlayerLocationService::_)->andReturn($mockPlayerLocations);

        $mockTemplate = Mockery::mock('ehough_contemplate_api_Template');
        $mockTemplate->shouldReceive('toString')->once()->andReturn('foobarr');

        $mockPlayerLocation->shouldReceive('getTemplate')->once()->with($this->_mockThemeHandler)->andReturn($mockTemplate);

        $mockPlayerTemplateEvent = new tubepress_api_event_TubePressEvent(

            $mockTemplate, array(
                'video' => $this->_mockVideo,
                'playerName' => 'x'
            )
        );

        $mockVideo = $this->_mockVideo;

        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(

            tubepress_api_const_event_CoreEventNames::PLAYER_TEMPLATE_CONSTRUCTION,
            Mockery::on(function ($arg) use ($mockTemplate, $mockVideo) {

                return $arg->getSubject() == $mockTemplate && $arg->getArgument('video') == $mockVideo
                    && $arg->getArgument('playerName') === 'x';
            })
        )->andReturn($mockPlayerTemplateEvent);

        $mockPlayerHtmlEvent = new tubepress_api_event_TubePressEvent('foobarr', array(

            'video' => $mockVideo,
            'playerName' => 'current-player-name'
        ));

        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(

            tubepress_api_const_event_CoreEventNames::PLAYER_HTML_CONSTRUCTION,
            Mockery::on(function ($arg) use ($mockVideo) {

                return $arg->getSubject() == 'foobarr' && $arg->getArgument('video') == $mockVideo
                    && $arg->getArgument('playerName') === 'x';
            })
        )->andReturn($mockPlayerHtmlEvent);

        $mockHtmlEvent = new tubepress_api_event_TubePressEvent('foobarr');

        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(

            tubepress_api_const_event_CoreEventNames::HTML_CONSTRUCTION,
            Mockery::on(function ($arg) {

                return $arg->getSubject() === 'foobarr';
            })
        )->andReturn($mockHtmlEvent);

        $this->assertEquals('foobarr', $this->_sut->getHtml($this->_mockVideo));
    }

    function testGetHtmlNoSuitablePlayerLocations()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_const_options_names_Embedded::PLAYER_LOCATION)->andReturn('x');

        $mockPlayerLocation = Mockery::mock(tubepress_spi_player_PluggablePlayerLocationService::_);
        $mockPlayerLocation->shouldReceive('getName')->andReturn('z');
        $mockPlayerLocations = array($mockPlayerLocation);

        $this->_mockServiceCollectionsRegistry->shouldReceive('getAllServicesOfType')->once()->with(tubepress_spi_player_PluggablePlayerLocationService::_)->andReturn($mockPlayerLocations);

        $html = $this->_sut->getHtml($this->_mockVideo);

        $this->assertNull($html);
    }

    function testGetHtmlNoPlayerLocations()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_const_options_names_Embedded::PLAYER_LOCATION)->andReturn('x');

        $this->_mockServiceCollectionsRegistry->shouldReceive('getAllServicesOfType')->once()->with(tubepress_spi_player_PluggablePlayerLocationService::_)->andReturn(array());

        $html = $this->_sut->getHtml($this->_mockVideo);

        $this->assertNull($html);
    }
}