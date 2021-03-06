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
 * @covers tubepress_options_impl_listeners_BasicOptionValidity<extended>
 */
class tubepress_test_options_impl_listeners_BasicOptionValidityTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var Mockery\MockInterface
     */
    private $_mockReference;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockTranslator;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockAcceptableValues;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockLangUtils;

    /**
     * @var tubepress_options_impl_listeners_BasicOptionValidity
     */
    private $_sut;

    public function onSetup()
    {
        $this->_mockTranslator       = $this->mock(tubepress_api_translation_TranslatorInterface::_);
        $this->_mockReference        = $this->mock(tubepress_api_options_ReferenceInterface::_);
        $this->_mockAcceptableValues = $this->mock(tubepress_api_options_AcceptableValuesInterface::_);
        $this->_mockLangUtils        = $this->mock(tubepress_api_util_LangUtilsInterface::_);

        $this->_sut = new tubepress_options_impl_listeners_BasicOptionValidity(

            $this->_mockReference,
            $this->_mockAcceptableValues,
            $this->_mockTranslator,
            $this->_mockLangUtils
        );
    }

    public function testBadAcceptableValues()
    {
        $mockEvent = $this->_getMockEvent('value');
        $mockEvent->shouldReceive('setSubject')->once()->with(array('abc <<name>> buzz value'));

        $this->_mockTranslator->shouldReceive('trans')->once()->with('NAME')->andReturn('<<name>>');
        $this->_mockTranslator->shouldReceive('trans')->once()->with('"%s" must be one of "%s". You supplied "%s".')->andReturn('abc %s %s %s');
        $this->_mockReference->shouldReceive('optionExists')->once()->with('name')->andReturn(true);
        $this->_mockReference->shouldReceive('isBoolean')->once()->with('name')->andReturn(false);
        $this->_mockReference->shouldReceive('getUntranslatedLabel')->twice()->with('name')->andReturn('NAME');
        $this->_mockAcceptableValues->shouldReceive('getAcceptableValues')->once()->with('name')->andReturn(array('buzz'));
        $this->_mockLangUtils->shouldReceive('isAssociativeArray')->once()->with(array('buzz'))->andReturn(false);

        $this->_sut->onOption($mockEvent);
        $this->assertTrue(true);
    }

    public function testBadBoolean()
    {
        $mockEvent = $this->_getMockEvent('value');
        $mockEvent->shouldReceive('setSubject')->once()->with(array('abc <<name>> value'));

        $this->_mockTranslator->shouldReceive('trans')->once()->with('NAME')->andReturn('<<name>>');
        $this->_mockTranslator->shouldReceive('trans')->once()->with('"%s" can only be "true" or "false". You supplied "%s".')->andReturn('abc %s %s');
        $this->_mockReference->shouldReceive('optionExists')->once()->with('name')->andReturn(true);
        $this->_mockReference->shouldReceive('isBoolean')->once()->with('name')->andReturn(true);
        $this->_mockReference->shouldReceive('getUntranslatedLabel')->twice()->with('name')->andReturn('NAME');

        $this->_sut->onOption($mockEvent);
        $this->assertTrue(true);
    }

    public function testNoSuchOption()
    {
        $mockEvent = $this->_getMockEvent('value');
        $mockEvent->shouldReceive('setSubject')->once()->with(array('abc name'));

        $this->_mockTranslator->shouldReceive('trans')->once()->with('No option with name "%s".')->andReturn('abc %s');
        $this->_mockReference->shouldReceive('optionExists')->once()->with('name')->andReturn(false);

        $this->_sut->onOption($mockEvent);
        $this->assertTrue(true);
    }

    /**
     * @return Mockery\MockInterface
     */
    private function _getMockEvent($value)
    {
        $mockEvent = $this->mock('tubepress_api_event_EventInterface');
        $mockEvent->shouldReceive('getSubject')->once()->andReturn(array());
        $mockEvent->shouldReceive('getArgument')->once()->with('optionName')->andReturn('name');
        $mockEvent->shouldReceive('getArgument')->once()->with('optionValue')->andReturn($value);

        return $mockEvent;
    }
}
