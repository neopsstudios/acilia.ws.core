<?php

namespace WS\Core\Service\Entity;

use WS\Core\Entity\Administrator;
use WS\Core\Form\AdministratorType;
use WS\Core\Library\ActivityLog\ActivityLogInterface;
use WS\Core\Library\ActivityLog\ActivityLogTrait;
use WS\Core\Library\CRUD\AbstractService;

class AdministratorService extends AbstractService implements ActivityLogInterface
{
    use ActivityLogTrait;

    protected $roles = [];

    public function getEntityClass(): string
    {
        return Administrator::class;
    }

    public function getFormClass(): ?string
    {
        return AdministratorType::class;
    }

    public function getSortFields(): array
    {
        return ['name'];
    }

    public function addRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function getFormProfiles(): array
    {
        $profiles = [];

        foreach ($this->roles as $role) {
            $profile = sprintf('administrator_role.%s', str_replace('ROLE_', '', $role));
            $profiles[strtolower($profile)] = $role;
        }

        return $profiles;
    }

    public function getProfileLabel($profile): string
    {
        return sprintf('administrator_role.%s', strtolower(str_replace('ROLE_', '', $profile)));
    }
}
