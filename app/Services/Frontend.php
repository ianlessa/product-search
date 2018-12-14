<?php

namespace IanLessa\ProductSearchApp\Services;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Manage the Twig template engine and serves the html for the frontpage.
 *
 * @package IanLessa\ProductSearchApp\Services
 */
class Frontend
{
    /**
     * The Twig Environment.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->initTwig();
    }

    /**
     * Initiates the Twig environment, setting the template path to app/design/ .
     */
    private function initTwig()
    {
        $loader = new Twig_Loader_Filesystem(
            __DIR__ . DIRECTORY_SEPARATOR . '../design'
        );
        $this->twig = new Twig_Environment($loader);
    }

    /**
     * Serves the front page html.
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getFrontPage()
    {
        $template = $this->twig->load('frontpage.twig');
        return $template->render();
    }

}