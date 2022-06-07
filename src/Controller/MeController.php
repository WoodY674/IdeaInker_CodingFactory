<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class MeController extends AbstractController
{
    // private Security $security;

    public function __construct(private Security $security)
    {
        $this->security = $security;
    }

    public function __invoke()
    {
        return $this->security->getUser();
    }
}
