<?php

require_once(dirname(__FILE__) . '/../../../common/class/TubePressOptionsPackage.php');
require_once(dirname(__FILE__) . '/../../../common/messages.php');
require_once(dirname(__FILE__) . '/../../../common/defines.php');

class TubePressOptionsPackageTest extends PHPUnit_Framework_TestCase {
    var $v;
    
    function testCheckValidity() {
        $result = $this->v->checkValidity();
        $this->assertFalse(PEAR::isError($result));
        
        $this->v->_allOptions = NULL;
        $result = $this->v->checkValidity();
        $this->assertTrue(PEAR::isError($result));
        
        $this->v->_allOptions[TP_OPT_MODE] = "blll";
        $result = $this->v->checkValidity();
        $this->assertTrue(PEAR::isError($result));
        
        $this->v->_allOptions = "hello";
        $result = $this->v->checkValidity();
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testSetNonNumeric() {
        $result = $this->v->setValue(TP_OPT_VIDHEIGHT, "fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testSetNonNumeric2() {
        $result = $this->v->setValue(TP_OPT_TIMEOUT, "fake");
        $this->assertTrue(PEAR::isError($result), $result->message);
    }
    
    function testSetPos() {
        $result = $this->v->setValue(TP_OPT_VIDHEIGHT, 10);
        $this->assertFalse(PEAR::isError($result));
        $this->assertEquals    (10, $this->v->getValue(TP_OPT_VIDHEIGHT));
    }
    
    function testSetPos2() {
        $result = $this->v->setValue(TP_OPT_TIMEOUT, 2);
        $this->assertFalse(PEAR::isError($result), $result->message);
    }
    
    function testSetFakeValue() {
        $result = $this->v->setValue("fake", "fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testSetNonBoolMeta() {
        $result = $this->v->setValue(TP_VID_LENGTH, "fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testSetNonBoolMeta2() {
        $result = $this->v->setValue(TP_VID_LENGTH, true);
        $this->assertFalse(PEAR::isError($result));
    }
    
    function testSetNonBoolDebugger() {
        $result = $this->v->setValue(TP_OPT_DEBUG, "fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testSetNonBoolDebugger2() {
        $result = $this->v->setValue(TP_OPT_DEBUG, true);
        $this->assertFalse(PEAR::isError($result));
    }
    
    function testGetTitle() {
        $result = $this->v->getTitle(TP_OPT_THUMBHEIGHT);
        $this->assertFalse(PEAR::isError($result));
        $this->assertEquals    ($result, _tpMsg("THUMBHEIGHT_TITLE"));
    }
    
    function testGetValue() {
        $result = $this->v->getValue(TP_OPT_THUMBHEIGHT);
        $this->assertFalse(PEAR::isError($result));
        $this->assertEquals    ($result, "90");
    }
    
    function testGetDesc() {
        $result = $this->v->getDescription(TP_OPT_THUMBHEIGHT);
        $this->assertFalse(PEAR::isError($result), $result->message);
        $this->assertEquals    ($result, _tpMsg("THUMBHEIGHT_DESC"));
    }
    
    function testGetFakeTitle() {
        $result = $this->v->getTitle("fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testGetFakeValue() {
        $result = $this->v->getValue("fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function testGetFakeDesc() {
        $result = $this->v->getDescription("fake");
        $this->assertTrue(PEAR::isError($result));
    }
    
    function setUp() {
        $opts = TubePressOptionsPackage::getDefaultPackage();
        $this->v = new TubePressOptionsPackage();
        $this->v->_allOptions = $opts;
    }
    
}
?>
