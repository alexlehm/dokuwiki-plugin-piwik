<?php

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\EventHandler;

class action_plugin_piwiktagmanager extends ActionPlugin
{
    public const PWTMID = 'PWTMID';
    public const PWTMHOST = 'PWTMHOST';

    /**
      * Register its handlers with the DokuWiki's event controller
      */
    public function register(EventHandler $controller)
    {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'addHeaders');
    }

    public function addHeaders(&$event, $param)
    {

            if (!$this->getConf(self::PWTMID)) return;

            $event->data['script'][] = ['type' => 'text/javascript', '_data' => "
  var _mtm = window._mtm = window._mtm || [];
  _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
  (function() {
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src='https://" .
        $this->getConf(self::PWTMHOST) .
        "/piwik/js/container_" .
        $this->getConf(self::PWTMID) .
        ".js'; s.parentNode.insertBefore(g,s);
  })();"];
    }
}
