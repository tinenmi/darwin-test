<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * @Assert\Callback("checkCustomValidation")
 * @ORM\Entity
 * @ORM\Table(name="users")
 * UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="This name is already in use."
 * )
 */
class RegisterForm
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     */
    protected $password;

    /*
     * @Assert\NotBlank()
     */
    protected $password_retry;

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

    public function getPasswordRetry()
    {
        return $this->password_retry;
    }

    public function setPasswordRetry($password_retry)
    {
        $this->password_retry = $password_retry;

        return $this;
    }

    public function checkCustomValidation(ExecutionContext $context)
    {
        if ($this->password != $this->password_retry) {
 	          $context->buildViolation('Passwords Must be Equals!')
                ->atPath('name')
               ->addViolation();
        }
    }
}
