<?php

class action_plugin_piwiktagmanager extends DokuWiki_Action_Plugin
{
    public const PWTMID = 'PWTMID';
    public const PWTMHOST = 'PWTMHOST';

    /**
         * return some info
         */
    public function getInfo()
    {
            return array(
                    'author' => 'Alexander Lehmann',
                    'email'  => 'alexlehm@gmail.com',
                    'date'   => '2025-12-06',
                    'name'   => 'Piwik (Matomo) Tag Manager',
                    'desc'   => 'Plugin to embed Piwik/Matomo Tag Manager in your wiki.',
                    'url'    => 'https://wiki.lehmann.cx/projects:dokuwiki_piwik',
            );
    }

        /**
         * Register its handlers with the DokuWiki's event controller
         */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'addHeaders');
    }

    private function addHeaders(&$event, $param)
    {

            if (!$this->getConf(self::PWTMID)) return;

            $event->data['script'][] = array (
                'type' => 'text/javascript',
                '_data' => "
  var _mtm = window._mtm = window._mtm || [];
  _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
  (function() {
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src='https://" .
        $this->getConf(self::PWTMHOST) .
        "/piwik/js/container_" .
        $this->getConf(self::PWTMID) .
        ".js'; s.parentNode.insertBefore(g,s);
  })();",
            );
    }
}
