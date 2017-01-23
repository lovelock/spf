<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 6:12 PM
 */

namespace App\View;


use Interop\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class Text
{
    private $view;
    private $ci;

    public function __construct(ContainerInterface $container)
    {
        $this->ci = $container;
        $this->view = new Twig(WEB_ROOT . '/view/templates',
            [
                'cache' => WEB_ROOT . '/view/cache'
            ]);

        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        $this->view->addExtension(new TwigExtension($container['router'], $basePath));
    }

    public function render($templateName, $data)
    {
        return $this->view->render($this->ci['response'], $templateName, $data);
    }


}