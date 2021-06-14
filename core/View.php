<?php


namespace app\core;


class View
{
    public string $title = '';

    public function renderView($view, $params = [])
    {
        $ViewContent = $this->Viewrenderer($view, $params);
        $layoutContent = $this->layoutRender();
        return str_replace('{{Content}}', $ViewContent, $layoutContent);
    }

    protected function layoutRender()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$RootDir."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    protected function Viewrenderer($view, $params)
    {
        foreach ($params as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include_once Application::$RootDir."/views/$view.php";
        return ob_get_clean();
    }

}