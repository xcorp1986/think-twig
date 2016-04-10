<?php

namespace Think\Template\Driver;

use Think\Think;
use Think\View;

/**
 * Twig模板引擎驱动
 */
class Twig
{

    /* @var \Twig_Environment */
    static protected $instance;

    /**
     * @return \Twig_Environment
     */
    static public function getInstance()
    {
        if (null === self::$instance) {
            /* @var \Twig_Loader_Filesystem */
            $loader = new \Twig_Loader_Filesystem(array_filter(array(THEME_PATH)));
            self::$instance = new \Twig_Environment($loader, array(
                'debug' => APP_DEBUG ? 1 : 0,
                'strict_variables' => APP_DEBUG ? 1 : 0,
                'cache' => CACHE_PATH . DIRECTORY_SEPARATOR . MODULE_NAME.DIRECTORY_SEPARATOR.ACTION_NAME
            ));
        }
        return self::$instance;
    }

    /**
     * 渲染模板输出
     * @param string $templateFile 模板文件名
     * @param array $parameters 模板变量
     */
    public function fetch($templateFile, $parameters)
    {
        /* @var \Think\View */
        $view = Think::instance('Think\\View');
        $error_tpl = $view->parseTemplate(C('TMPL_ACTION_ERROR'));
        $success_tpl = $view->parseTemplate(C('TMPL_ACTION_SUCCESS'));
        if ($error_tpl === $templateFile || $success_tpl === $templateFile) {
            if (pathinfo($templateFile, PATHINFO_EXTENSION) !== 'twig') {
                $tpl = Think::instance('Think\\Template');
                echo $tpl->fetch($templateFile, $parameters);
                exit;
            }
        }
        $templateFile = substr($templateFile, strlen(THEME_PATH));
        $twig = self::getInstance();
        echo $twig->render($templateFile, $parameters);
    }
}
