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
 * @covers tubepress_options_ui_impl_fields_templated_single_ThemeField<extended>
 */
class tubepress_test_app_impl_options_ui_fields_templated_single_ThemeFieldTest extends tubepress_test_app_impl_options_ui_fields_templated_single_AbstractSingleOptionFieldTest
{
    /**
     * @var Mockery\MockInterface
     */
    private $_mockThemeRegistry;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockLangUtils;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockAcceptableValues;

    public function testGetThemeDataAsJson()
    {
        $mockTheme  = $this->mock(tubepress_api_theme_ThemeInterface::_);
        $mockThemes = array($mockTheme);

        $mockTheme->shouldReceive('getName')->once()->andReturn('theme name');
        $mockTheme->shouldReceive('getDescription')->once()->andReturn('theme description');
        $mockTheme->shouldReceive('getAuthors')->once()->andReturn(array('theme author'));
        $mockTheme->shouldReceive('getLicense')->once()->andReturn(
            array('type' => 'foo license', 'urls' => array('http://foo.bar'))
        );
        $mockTheme->shouldReceive('getVersion')->once()->andReturn('8.0.0');
        $mockTheme->shouldReceive('getDemoUrl')->once()->andReturn('http://foo.bar/demo');
        $mockTheme->shouldReceive('getHomepageUrl')->once()->andReturn('http://foo.bar/home');
        $mockTheme->shouldReceive('getDocumentationUrl')->once()->andReturn('http://foo.bar/docs');
        $mockTheme->shouldReceive('getDownloadUrl')->once()->andReturn('http://foo.bar/download');
        $mockTheme->shouldReceive('getBugTrackerUrl')->once()->andReturn('http://foo.bar/bugs');
        $mockTheme->shouldReceive('getForumUrl')->once()->andReturn('http://foo.bar/forum');
        $mockTheme->shouldReceive('getSourceCodeUrl')->once()->andReturn('http://foo.bar/source');
        $mockTheme->shouldReceive('getScreenshots')->once()->andReturn(array('some', 'screen', 'shot'));
        $this->_mockThemeRegistry->shouldReceive('getAll')->once()->andReturn($mockThemes);

        $actual   = $this->getSut()->getThemeDataAsJson();
        $expected = '{"theme name":{"authors":["theme author"],"description":"theme description","license":{"type":"foo license","urls":["http:\/\/foo.bar"]},"screenshots":["some","screen","shot"],"support":{"demo":"http:\/\/foo.bar\/demo","homepage":"http:\/\/foo.bar\/home","docs":"http:\/\/foo.bar\/docs","download":"http:\/\/foo.bar\/download","bugs":"http:\/\/foo.bar\/bugs","forum":"http:\/\/foo.bar\/forum","sourceCode":"http:\/\/foo.bar\/source"},"version":"8.0.0"}}';

        $this->assertEquals($expected, $actual);
    }

    protected function getSut()
    {
        return new tubepress_options_ui_impl_fields_templated_single_ThemeField(

            $this->getMockPersistence(),
            $this->getMockHttpRequestParams(),
            $this->getMockOptionsReference(),
            $this->getMockTemplating(),
            $this->_mockLangUtils,
            $this->_mockThemeRegistry,
            $this->_mockAcceptableValues
        );
    }

    protected function onAfterSingleFieldSetup()
    {
        $this->_mockLangUtils        = $this->mock(tubepress_api_util_LangUtilsInterface::_);
        $this->_mockAcceptableValues = $this->mock(tubepress_api_options_AcceptableValuesInterface::_);
        $this->_mockThemeRegistry    = $this->mock(tubepress_api_contrib_RegistryInterface::_);
    }

    protected function getId()
    {
        return 'theme';
    }

    /**
     * @return string
     */
    protected function getExpectedTemplateName()
    {
        return 'options-ui/fields/dropdown';
    }

    /**
     * @return array
     */
    protected function getAdditionalExpectedTemplateVariables()
    {
        $this->_mockLangUtils->shouldReceive('isAssociativeArray')->once()->andReturn(true);

        $this->_mockAcceptableValues->shouldReceive('getAcceptableValues')->once()->with($this->getId())->andReturn(array(

            'foo' => 'abc', 'smack' => 'xyz',
        ));

        return array(
            'ungroupedChoices' => array('foo' => 'abc', 'smack' => 'xyz'),
        );
    }

    protected function getMultiSourcePrefix()
    {
        return '';
    }
}
