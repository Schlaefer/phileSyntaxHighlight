<?php

namespace Phile\Plugin\Siezi\PhileSyntaxHighlight;

use GeSHi;
use Phile\Core\Event;
use Phile\Plugin\AbstractPlugin;
use Phile\Gateway\EventObserverInterface;

class Plugin extends AbstractPlugin implements EventObserverInterface
{

    protected $registeredEvents = [
      'after_parse_content' => 'highlight',
      'after_render_template' => 'outputCss'
    ];

    /**
     * @var Geshi
     */
    protected $Geshi;

    protected $sendCss = false;

    public function __construct()
    {
        foreach ($this->registeredEvents as $event => $method) {
            Event::registerEvent($event, $this);
        }
    }

    public function on($eventKey, $data = null)
    {
        $method = $this->registeredEvents[$eventKey];
        $this->{$method}($data);
    }

    protected function highlight($data)
    {
        $data['content'] = preg_replace_callback(
          '/<pre><code\s+class="(?P<lang>.*?)">(?P<code>.*?)<\/code><\/pre>/s',
          function ($matches) {
              return $this->geshi($matches['code'], $matches['lang']);
          },
          $data['content']
        );
    }

    protected function geshi($source, $language)
    {
        if (!$this->Geshi) {
            $this->Geshi = new Geshi();
        }

        // Geshi will encode again
        $source = html_entity_decode($source);
        $source = trim($source);

        $this->Geshi->set_source($source);
        $this->Geshi->set_language($language);

        $this->Geshi->set_tab_width(2);
        $this->Geshi->enable_classes();
        $this->sendCss = true;

        return $this->Geshi->parse_code();
    }

    protected function outputCss($data)
    {
        if (!$this->sendCss) {
            return;
        }
        $css = '<style>' . $this->Geshi->get_stylesheet() . '</style>';
        $data['output'] = preg_replace(
          '/(<head>)/i',
          "\\0\n" . $css,
          $data['output']
        );

    }

}
