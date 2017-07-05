<?php

namespace FOS\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;

class User
{
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }
}

