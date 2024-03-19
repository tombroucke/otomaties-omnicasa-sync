<?php 

namespace Otomaties\Omnicasa;

use Omnicasa\Omnicasa;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Otomaties\Omnicasa\Helpers\View;
use Otomaties\Omnicasa\Modules\Admin;
use Otomaties\Omnicasa\Helpers\Assets;
use Otomaties\Omnicasa\Helpers\Config;
use Otomaties\Omnicasa\Helpers\Loader;
use Otomaties\Omnicasa\Modules\Frontend;
use Otomaties\Omnicasa\Command\CommandRegistrar;
use Otomaties\Omnicasa\Exceptions\CredentialsNotSetException;

class Plugin extends Container
{
    private array $modules = [
        Frontend::class,
        Admin::class,
    ];

    public function __construct(
        private Loader $loader,
        private Config $config
    ) {
        $this->addBindings();
    }

    private function addBindings() : void
    {
        $this->singleton(Omnicasa::class, function () {
            $username = getenv('OMNICASA_USERNAME');
            $password = getenv('OMNICASA_PASSWORD');
            if (!$username || !$password) {
                throw new CredentialsNotSetException('Omnicasa credentials not set');
            }
            return new \Omnicasa\Omnicasa($username, $password);
        });

        $this->singleton(Loader::class, function ($plugin) {
            return $plugin->getLoader();
        });

        $this->singleton(View::class, function ($plugin) {
            return new View($plugin->config('paths.views'));
        });

        $this->singleton(Assets::class, function ($plugin) {
            return new Assets($plugin->config('paths.public'));
        });
    }

    public function config(string $key) : mixed
    {
        return $this->config->get($key);
    }

    public function initialize() : self
    {
        $this->loader->addAction('init', $this, 'loadTextDomain');

        $this->activate();
        $this->loadModules();
        $this->initCommands();
        $this->initPostTypes();
        $this->initOptionsPages();

        return $this;
    }

    private function activate()
    {
        $this
            ->collectFilesIn('Database')
            ->each(function ($filename) {
                $className = $this->namespacedClassNameFromFilename($filename);
                register_activation_hook($this->config('paths.base') . '/otomaties-omnicasa-sync.php', [$className, 'create']);
            });
    }

    private function initCommands()
    {
        $this->make(CommandRegistrar::class)
            ->register();
    }

    private function initPostTypes()
    {
        collect([
            'PostTypes',
            'Taxonomies',
        ])->each(function ($registerableClassPath) {
            $this
                ->collectFilesIn("$registerableClassPath")
                ->each(function ($filename) {
                    $className = $this->namespacedClassNameFromFilename($filename);
                    $this->loader->addAction('init', new $className(), 'register');
                });
        });
    }

    private function initOptionsPages()
    {
        $this
            ->collectFilesIn('OptionsPages')
            ->each(function ($filename) {
                $className = $this->namespacedClassNameFromFilename($filename);
                $this->loader->addAction('acf/init', new $className(), 'register');
            });
    }

    private function loadModules() : self
    {
        collect($this->modules)
            ->each(function ($className) {
                ($this->make($className))
                    ->init();
            });
        return $this;
    }

    public function loadTextDomain() : void
    {
        load_plugin_textdomain(
            'otomaties-omnicasa-sync',
            false,
            basename($this->config('paths.base')) . '/resources/languages'
        );
    }

    public function getLoader() : Loader
    {
        return $this->loader;
    }

    public function runLoader() : void
    {
        apply_filters('plugin_boilerplate_loader', $this->getLoader())
            ->run();
    }
    
    private function collectFilesIn($path)
    {
        $fullPath = $this->config('paths.app') . "/$path";
        return collect(array_merge(
            glob("$fullPath/*.php"),
            glob("$fullPath/**/*.php")
        ))
        ->reject(function ($filename) {
            return Str::contains($filename, 'Example');
        })
        ->reject(function ($filename) {
            return Str::contains($filename, '/Abstracts') || Str::contains($filename, '/Concerns') || Str::contains($filename, '/Contracts');
        });
    }
    
    private function namespacedClassNameFromFilename($filename)
    {
        return Str::of($filename)
            ->replace($this->config('paths.app'), '')
            ->ltrim('/')
            ->replace('/', '\\')
            ->rtrim('.php')
            ->prepend(__NAMESPACE__. '\\')
            ->__toString();
    }
}
