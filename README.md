# Syntax Highlight Plugin for PhileCMS #

Syntax highlight source code.

[Project Home](https://github.com/Schlaefer/phileSyntaxHighlight)

### 1.1 Installation (composer) ###

```json
{
	"siezi/phile-syntax-highlight": "*"
}
```

### 1.2 Installation (Download)

* download this plugin into `plugins/siezi/phileSyntaxHighlight`
* install and include Geshi 1.0.x 

### 2. Activation

After you have installed the plugin you need to activate it. Add the following line to your `/config.php` file:

```php
$config['plugins']['siezi\\phileSyntaxHighlight'] = ['active' => true];
```

### 3. Start ###

In the standard Phile installation use [markdown fenced code blocks](https://help.github.com/articles/github-flavored-markdown/#fenced-code-blocks) and specify a language:

<pre><code>```php
$foo = bar;
```
</code></pre>

### 4. Config ###

See `config.php`.