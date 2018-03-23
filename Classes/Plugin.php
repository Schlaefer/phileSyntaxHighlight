<?php

namespace Phile\Plugin\Siezi\PhileSyntaxHighlight;

use GeSHi;
use Phile\Core\ServiceLocator;
use Phile\Plugin\AbstractPlugin;

class Plugin extends AbstractPlugin
{

    protected $events = [
        'after_init_core' => 'init',
        'after_parse_content' => 'highlight',
        'after_render_template' => 'outputCss'
    ];

    protected $cache;

    protected $css = [];

    /**
     * @var Geshi
     */
    protected $Geshi;

    protected $sendCss = false;

    protected function init() {
        if (ServiceLocator::hasService('Phile_Cache')) {
            $this->cache = ServiceLocator::getService('Phile_Cache');
        }
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
        $this->sendCss = true;

        $key = 'Siezi.PhileSyntaxHighligh.highlight.' . md5($source . $language);
        if ($this->cache && $this->cache->has($key)) {
            $storage = $this->cache->get($key);
            $this->css[$language] = $storage['css'];
            return $storage['html'];
        }

        // Geshi will encode again
        $source = html_entity_decode($source);
        $source = trim($source);

        if (!$this->Geshi) {
            $this->Geshi = new GeSHi();
            $this->Geshi->enable_classes($this->settings['enable_classes']);
            $this->settings['geshiConfigurator']($this->Geshi);
        }
        $this->Geshi->set_source($source);
        $this->Geshi->set_language($language);

        $html = $this->Geshi->parse_code();

        if ($this->settings['enable_classes']) {
            $css = $this->Geshi->get_stylesheet();
            $this->css[$language] = $css;
        }

        if ($this->cache) {
            $this->cache->set($key, ['css' => $css, 'html' => $html]);
        }

        return $html;
    }

    protected function outputCss($data)
    {
        if (!$this->sendCss || empty($this->css)) {
            return;
        }
        $css = '<style>' . implode("\n", $this->css) . '</style>';
        $data['output'] = preg_replace(
          '/(<\/head>)/i',
          $css . "\n\\0\n",
          $data['output']
        );

    }

}
