<?php

namespace App\Http;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

abstract class AbstractPageController extends AbstractController
{
    /**
     * Render a template
     *
     * @param string $templatePath
     * @param array $vars
     * @return DefaultResponse
     */
    public function render(string $templatePath, array $vars = [])
    {
        $templateDir = sgv()->server('APP_TEMPLATE_DIR');
        $options = [
            // Cache disabled for development
            //'cache' => sgv()->server('APP_TEMPLATE_CACHE_DIR'),
        ];
        $loader = new FilesystemLoader($templateDir);
        $twig = new Environment($loader, $options);

        $html = $twig->render($templatePath, $vars);

        return $this
            ->response
            ->write($html)
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
        ;
    }
}
