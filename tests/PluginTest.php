<?php

namespace Phile\Plugin\Siezi\PhileDebugbar\Tests;

use Phile\Core\Config;
use Phile\Test\TestCase;
use Phile\Core\Event;

class PluginTest extends TestCase
{
    public function testPluginInjectsInPage()
    {
        $config = new Config([
            'plugins' => [
                'siezi\\phileSyntaxHighlight' => ['active' => true]
            ]
        ]);
        $eventBus = new Event;
        $eventBus->register('after_load_content', function ($name, $data) {
            $data['page']->setContent("```php\n$a = 5;\n```");
        });
        $core = $this->createPhileCore(null, $config);
        $request = $this->createServerRequestFromArray();
        $response = $this->createPhileResponse($core, $request);

        $body = (string)$response->getBody();
        $this->assertContains('.php .re0 {', $body);
        $this->assertContains('<span class="re0">$a</span>', $body);
        $this->assertSame(200, $response->getStatusCode());
    }
}
