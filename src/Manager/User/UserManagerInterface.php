<?php
namespace App\Manager\User;


use App\Entity\User\User;
use App\Exception\User\UserAlreadyExistsException;
use App\Exception\User\UserNotFoundException;

interface UserManagerInterface
{
    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';

    /**
     * Create user.
     *
     * @param User $user
     * @param string $type
     *
     * @throws UserAlreadyExistsException
     *
     * @return int
     */
    public function create(User $user, string $type): int;

    /**
     * Get user by username.
     *
     * @throws UserNotFoundException
     *
     * @param string $username
     * @return User
     */
    public function getByUsername(string $username): User;

    /**
     * Get user by id.
     *
     * @param int $id
     *
     * @throws UserNotFoundException
     * @return User
     */
    public function getUserById(int $id): User;

    /**
     * Update user.
     *
     * @param int $id
     * @param User $user
     *
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     *
     * @return User
     */
    public function update(int $id, User $user): User;

    /**
     * Remove user.
     *
     * @throws UserNotFoundException
     * @param int $id
     */
    public function remove(int $id): void;
}