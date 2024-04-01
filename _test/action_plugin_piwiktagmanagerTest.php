<?php


class action_plugin_piwiktagmanagerTest extends DokuWikiTest
{

    const pwtmPluginName = 'piwiktagmanager';

    public function setUp()
    {
        $this->pluginsEnabled[] = self::pwtmPluginName;
        parent::setUp();
    }

    public function test_piwik_tag_manager()
    {

        global $conf;
        $pwtmValue = "Xabcdef";
        $conf['plugin'][self::pwtmPluginName][action_plugin_piwiktagmanager::PWTMID] = $pwtmValue;

        $pageId = 'start';
        saveWikiText($pageId, "Content", 'Script Test base');
        idx_addPage($pageId);

        $request = new TestRequest();
        $response = $request->get(array('id' => $pageId, '/doku.php'));

        /**
         * Tags to searched
         */
        $tagsSearched = ["script", "noscript"];

        foreach ($tagsSearched as $tagSearched) {

            $domElements = $response->queryHTML($tagSearched)->get();

            $patternFound = 0;
            foreach ($domElements as $domElement) {
                /**
                 * @var DOMElement $domElement
                 */
                if ($tagSearched=="script") {
                    $value = $domElement->textContent;
                } else {
                    // iframe src
                    $value = $domElement->firstChild->getAttribute("src");
                }
                $patternFound = preg_match("/$pwtmValue/i", $value);
                if ($patternFound === 1) {
                    break;
                }
            }
            $this->assertEquals(1, $patternFound, "The piwik scripts have been found for the tag $tagSearched");
        }


    }

}
