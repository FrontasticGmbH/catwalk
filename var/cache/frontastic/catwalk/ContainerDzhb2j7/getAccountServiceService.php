<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Common\AccountApiBundle\Domain\AccountService' shared service.

return $this->services['Frontastic\\Common\\AccountApiBundle\\Domain\\AccountService'] = new \Frontastic\Common\AccountApiBundle\Domain\AccountService(($this->services['frontastic.catwalk.account_api'] ?? $this->load('getFrontastic_Catwalk_AccountApiService.php')), ($this->services['Frontastic\\Common\\CoreBundle\\Domain\\Mailer'] ?? $this->load('getMailerService.php')));