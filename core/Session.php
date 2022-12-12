<?php

namespace Core;

class Session
{
    public function isStarted()
    {
        return session_id() !== '';
    }
    
    public function start()
    {
        if (!$this->isStarted()) {
            session_start();
        }
    }
    
    public function remove(string $key)
    {
        $this->start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public function set(string $key, mixed $value)
    {
        $this->start();
        if (is_string($key)) {
            $_SESSION[$key] = $value;
        }
    }

    public function get(string $key, mixed $default = null)
    {
        $this->start();
        if (is_string($key)) {
            $value = $_SESSION[$key] ?? null;
        }
        
        return $value === null ? $default : $value;
    }
}
