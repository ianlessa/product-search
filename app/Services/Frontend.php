<?php

namespace IanLessa\ProductSearchApp\Services;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Frontend
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct()
    {
        $this->initTwig();
    }

    private function initTwig()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . DIRECTORY_SEPARATOR . '../design');
        $this->twig = new Twig_Environment($loader);
    }

    public function getFrontPage()
    {
        $template = $this->twig->load('frontpage.twig');
        return $template->render();
    }

}