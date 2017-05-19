<?php

namespace AppBundle\Entity;

class LoginForm
{
    protected $name;
    protected $password;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
	return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
	return $this;
    }
}
