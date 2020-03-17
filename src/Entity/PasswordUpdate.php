<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormTypeInterface;



class PasswordUpdate
{

    private $oldPassword;
    /**
     * @Assert\Length(min=8,minMessage="Votre mdp doit faire more than 8 caractéres !!!")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword" ,message="Vous n'avez pas correctement votre nouveau mdp")
     */

    private $confirmPassword;


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
