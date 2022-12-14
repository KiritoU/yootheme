<?php

namespace YOOtheme\Theme\Wordpress;

use YOOtheme\Application;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Container;
use YOOtheme\Event;
use YOOtheme\Theme\Updater;

class ThemeLoader
{
    /**
     * @var array
     */
    protected static $configs = [];

    /**
     * Load theme configurations.
     *
     * @param Container $container
     * @param array     $configs
     */
    public static function load(Container $container, array $configs)
    {
        static::$configs = array_merge(static::$configs, $configs);
    }

    /**
     * Setup theme.
     *
     * @param Application $app
     * @param Config      $configuration
     */
    public static function setupTheme(Application $app, Config $configuration)
    {
        // load childtheme config
        if (is_child_theme()) {
            $app->load(get_stylesheet_directory() . '/config.php');
        }

        // add configurations
        foreach (static::$configs as $config) {
            if ($config instanceof \Closure) {
                $config = $config($configuration, $app);
            }

            $configuration->add('theme', (array) $config);
        }

        $configuration->add('theme', [
            'id' => get_current_blog_id(),
            'default' => is_main_site(),
            'template' => basename($configuration('theme.rootDir')),
        ]);
    }

    /**
     * Initialize theme.
     *
     * @param Application $app
     * @param Config      $configuration
     */
    public static function initTheme(Application $app, Config $configuration)
    {
        // get config params
        $params = get_theme_mod('config', '{}');
        $params = json_decode($params, true) ?: [];

        if (empty($params)) {
            $params['version'] = $configuration('theme.version');
        }

        $config = $app(Updater::class)->update($params, ['app' => $app, 'config' => $params]);
        if (empty($params['version']) || $params['version'] !== $config['version']) {
            set_theme_mod('config', json_encode($config));
        }

        // merge defaults with configuration
        $configuration->set('~theme', Arr::merge($configuration('theme.defaults', []), $config));

        Event::emit('theme.init');
    }
}
