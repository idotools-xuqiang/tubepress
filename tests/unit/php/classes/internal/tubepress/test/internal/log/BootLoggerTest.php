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
 * @covers tubepress_internal_logger_BootLogger
 */
class tubepress_test_internal_logger_BootLoggerTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_internal_logger_BootLogger
     */
    private $_sut;

    public function onSetup()
    {
        $this->_sut = new tubepress_internal_logger_BootLogger(true);
    }

    public function testEnabled()
    {
        $this->assertTrue($this->_sut->isEnabled());
    }

    public function testFlushTo()
    {
        $mockLogger = $this->mock(tubepress_api_log_LoggerInterface::_);

        $mockLogger->shouldReceive('debug')->once()->with(ehough_mockery_Mockery::on(function ($m) {

            $utils = new tubepress_util_impl_StringUtils();
            return $utils->endsWith($m, 'something');
        }), array());
        $this->_sut->debug('something');

        $this->_sut->flushTo($mockLogger);
        $this->assertTrue(true);
    }

    public function testError()
    {
        $this->expectOutputRegex('~some message~');

        $this->_sut->error('some message', array('foo' => 'bar'));

        $this->_sut->handleBootException(new RuntimeException());
    }

    public function testDebug()
    {
        $this->expectOutputRegex('~some message~');

        $this->_sut->debug('some message', array('foo' => 'bar'));

        $this->_sut->handleBootException(new RuntimeException());
    }
}

