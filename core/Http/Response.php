<?php

namespace Core\Http;

use Core\View\View;

class Response
{
    private $headers = [];
    protected $status = 200;
    private $data;
    private $message;
    private View|null $view;

    public function __construct($data, string $message = null, View|null $view = null, int $status = 200)
    {
        
        $this->data = $data;
        $this->status = $status;
        $this->message = $message === null ? null : $message;
        $this->view = $view;
    }

    public function send()
    {
        // set headers
        http_response_code($this->status);
        foreach ($this->headers as $header => $value) {
            header($header.": ".$this->getHeaderLine($header, ""));
		}
        // echo view's content if view's value is a valid file
        if ($this->view !== null) {
            $this->view->render($this->data);
        } else {
            $result = [];
            if (!empty($this->message)) {
                $result["message"] = $this->message;
            }
            if (isset($this->data)) {
                $result["data"] = $this->data;
            }
            
            echo json_encode($result);
        }
    }

    public function addHeader($name, $value)
    {
        $name = strtolower($name);
        $this->headers[$name][] = $value;
    }

    public function setHeader($name, $value)
    {
        $name = strtolower($name);
        $this->headers[$name] = [
            (string) $value,
        ];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name, $default = null)
    {
        $name = strtolower($name);
        return $this->headers[$name] ?? $default;
    }

    public function getHeaderLine($name, $default = null)
    {
        $name = strtolower($name);
        $headers = $this->headers[$name] ?? null;

        if ($headers === null) {
            return $default;
        } elseif (is_string($headers)) {
            return $headers;
        } elseif (is_array($headers)) {
            return implode(", ", $headers);
        }
    }

    public function redirect($url)
    {
        header('Location: ', $url);
        exit;
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
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function withMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function withStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
