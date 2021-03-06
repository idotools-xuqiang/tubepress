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
 * @covers tubepress_options_impl_Context<extended>
 */
class tubepress_test_options_impl_ContextTest extends tubepress_test_options_impl_AbstractOptionReaderTest
{
    /**
     * @var tubepress_options_impl_Context
     */
    private $_sut;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockStorageManager;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockReference;

    protected function doSetup()
    {
        $this->_mockStorageManager = $this->mock(tubepress_api_options_PersistenceInterface::_);
        $this->_mockReference      = $this->mock(tubepress_api_options_ReferenceInterface::_);

        $this->_sut = new tubepress_options_impl_Context(

            $this->_mockStorageManager,
            $this->getMockEventDispatcher(),
            $this->_mockReference
        );
    }

    public function testGetFromPersistence()
    {
        $this->_mockStorageManager->shouldReceive('fetch')->once()->with('a')->andReturn('b');

        $result = $this->_sut->get('a');

        $this->assertEquals('b', $result);
    }

    public function testSetGet()
    {
        $this->assertEquals(array(), $this->_sut->getEphemeralOptions());

        $this->setupEventDispatcherToPass('theme', 'crazytheme', 'CRAZYTHEME');

        $this->_sut->setEphemeralOption('theme', 'crazytheme');

        $this->assertEquals('CRAZYTHEME', $this->_sut->get('theme'));

        $this->assertEquals(array('theme' => 'CRAZYTHEME'), $this->_sut->getEphemeralOptions());

        $this->setupEventDispatcherToPass('foo', 'bar', 'BAR');

        $this->_sut->setEphemeralOptions(array('foo' => 'bar'));

        $this->assertEquals(array('foo' => 'BAR'), $this->_sut->getEphemeralOptions());
    }

    public function testSetSingleBadValue()
    {
        $this->setupEventDispatcherToFail('theme', 'hi', 'HI', 'something bad');

        $result = $this->_sut->setEphemeralOption('theme', 'hi');

        $this->assertEquals('something bad', $result);
    }

    public function testSetMultipleBadValues()
    {
        $this->setupEventDispatcherToFail('theme', 'hi', 'HI', 'something bad');

        $result = $this->_sut->setEphemeralOptions(array('theme' => 'hi'));

        $this->assertEquals(array('something bad'), $result);
    }

}
