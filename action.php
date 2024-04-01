<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_piwiktagmanager extends DokuWiki_Action_Plugin {

    const PWTMID = 'PWTMID';
    const PWTMHOST = 'PWTMHOST';

    /**
         * return some info
         */
        function getInfo(){
                return array(
                        'author' => 'Alexander Lehmann',
                        'email'  => 'alexlehm@gmail.com',
                        'date'   => '2024-04-01',
                        'name'   => 'Piwik Tag Manager Plugin',
                        'desc'   => 'Plugin to embed Piwik Tag Manager in your wiki.',
                        'url'    => 'https://www.lehmann.cx/wiki/projects:dokuwiki_piwik',
                );
        }

        /**
         * Register its handlers with the DokuWiki's event controller
         */
        function register(Doku_Event_Handler $controller) {
            $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE',  $this, '_addHeaders');
        }

        function _addHeaders (&$event, $param) {

                if(!$this->getConf(self::PWTMID)) return;

		$event->data['noscript'][] = array (
                    '_data' => '',
                );
                $event->data['script'][] = array (
                    'type' => 'text/javascript',
                    '_data' => "
  var _mtm = window._mtm = window._mtm || [];
  _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
  (function() {
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src='https://".$this->getConf(self::PWTMHOST)."/piwik/js/container_".$this->getConf(self::PWTMID).".js'; s.parentNode.insertBefore(g,s);
  })();",
                );
        }
}
?>
