<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDto
{
    private string $name;
    #[Assert\When(
        expression: "this.getPassword() !== null and this.getPassword() !== ''",
        constraints: [new Assert\Length(min: 8)]
    )]
    private ?string $password;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UpdateUserDto
     */
    public function setName(string $name): UpdateUserDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UpdateUserDto
     */
    public function setPassword(?string $password): UpdateUserDto
    {
        $this->password = $password;
        return $this;
    }
}