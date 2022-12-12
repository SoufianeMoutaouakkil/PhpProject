<?php

namespace Core\Http;

class Request
{
    private $path;
    private $apiPath;
    private $uri;
    private $authorization;

    private $method;
    private $isGet;
    private $isPost;
    private $isApi;
    private $params = [];
    
    private $queryString;

    private $file;
    private $query;
    private $data;
    private $param;
    
    public function __get($name)
    {
        $allowedProps = [
            "path",
            "apiPath",
            "uri",
            "authorization",
            "method",
            "isGet",
            "isPost",
            "isApi",
            "queryString",
            "file",
            "query",
            "data",
            "param",
        ];
        if (in_array($name, $allowedProps)) {
            return $this->$name;
        }
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getApiPath()
    {
        return $this->apiPath;
    }

    public function __construct()
    {
        $this->file = $_FILES;
        $this->data = $_POST;
        $this->query = $_GET;
        $this->param = [];
        $this->setPath();
        $this->authorization = $this->getAuth();
        $this->queryString = $this->getQueryString();

        $this->uri = $_SERVER["REQUEST_URI"] ?? "";
        $this->setMethod();
    }
    
    private function setMethod()
    {
        $this->method = strtolower($_SERVER["REQUEST_METHOD"] ?? "get");
        $this->isGet = $this->method === "get";
        $this->isPost = $this->method === "post";
    }
    private function setPath()
    {
        if (!isset($_SERVER["PATH_INFO"])) {
            $this->path = "/";
            $this->isApi = false;
        } else {
            $path = $_SERVER["PATH_INFO"];
            
            // remove last slash
            $path = trim($path, "/");
            
            // set api
            if (str_starts_with($path, "api/")) {
                $this->apiPath = $path;
                $path = substr($path, 4);
                $this->isApi = true;
            } else {
                $this->apiPath = "";
                $this->isApi = false;
            }
            $this->path = $path;
        }
    }

    private function getAuth() : string
    {
        return $_SERVER["HTTP_AUTHORIZATION"] ?? "";
    }

    private function getQueryString() : string
    {
        return $_SERVER["QUERY_STRING"] ?? "";
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of isGet
     */
    public function isGet()
    {
        return $this->isGet;
    }

    /**
     * Get the value of isPost
     */
    public function isPost()
    {
        return $this->isPost;
    }

	/**
	 * @return mixed
	 */
	public function getParams()
    {
		return $this->params;
	}
	
	/**
	 * @param mixed $params
	 * @return void
	 */
	public function setParams($params)
    {
		$this->params = $params;
	}
}
