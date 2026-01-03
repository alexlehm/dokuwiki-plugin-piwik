<?php

/**
 * @group plugin_piwiktagmanager
 * @group plugins
 */

class action_plugin_piwiktagmanagerTest extends \DokuWikiTest
{

    const pwtmPluginName = 'piwiktagmanager';

    public function setUp(): void
    {
        $this->pluginsEnabled[] = self::pwtmPluginName;
        parent::setUp();
    }

    public function testPiwikTagManager()
    {
        global $conf;
        $pwtmValue = "Xabcdef";
        $pwtmHostValue = "piwik.example.com";
        $conf['plugin'][self::pwtmPluginName]["PWTMID"] = $pwtmValue;
        $conf['plugin'][self::pwtmPluginName]["PWTMHOST"] = $pwtmHostValue;

        $pageId = 'start';
        saveWikiText($pageId, "Content", 'Script Test base');
        idx_addPage($pageId);

        $request = new TestRequest();
        $response = $request->get(['id' => $pageId, '/doku.php']);

        $domElements = $response->queryHTML("script");

        $patternFound = false;
        foreach ($domElements as $domElement) {
            $value = $domElement->textContent;
            $patternFound = preg_match("/$pwtmHostValue.*$pwtmValue/i", $value) === 1;
            if ($patternFound) {
                break;
            }
        }
        $this->assertTrue($patternFound, "The piwik script was not found in the script tags");
    }
}
