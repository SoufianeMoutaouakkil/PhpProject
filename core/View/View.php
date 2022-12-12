<?php

namespace Core\View;

use InvalidArgumentException;

class View
{

    private $viewDir;
    private $layoutDir;
    private $viewPath;
    private $layoutPath;

    public function __construct(string $view, $layout = "index")
    {
        $this->viewDir = $_ENV["VIEW"]["VIEW_DIR"]. DIRECTORY_SEPARATOR;
        $this->layoutDir = $this->viewDir . "layouts". DIRECTORY_SEPARATOR;
        $this->viewPath = $this->viewDir . str_replace(".", DIRECTORY_SEPARATOR, $view) . ".php";
        $this->layoutPath = $this->layoutDir . $layout . ".php";
        if (!is_file($this->viewPath)) {
            throw new InvalidArgumentException("$this->viewPath is not a valid file!");
        }
        if (!is_file($this->layoutPath)) {
            throw new InvalidArgumentException("$this->layoutPath is not a valid file!");
        }
    }

    public function render(array $data = [], string $m = "")
    {
        extract($data);
        ob_start();
        require_once $this->viewPath;
        $result = ob_get_clean();
        $message = $m;
        $content = $result;
        require_once $this->layoutPath;
    }
}
