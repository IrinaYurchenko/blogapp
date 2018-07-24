<?php

namespace App\Manager\User;

use App\Entity\User\User;
use App\Exception\User\RoleNotFoundException;
use App\Exception\User\UserAlreadyExistsException;
use App\Exception\User\UserNotFoundException;
use App\Repository\User\RoleRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $entityManager->getRepository('App:User\User');
        $this->roleRepository = $entityManager->getRepository('App:User\Role');
        $this->passwordEncoder = $passwordEncoder;
    }

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
    public function create(User $user, string $type): int
    {
        $userFetched = $this->userRepository->findOneBy(['username' => $user->getUsername()]);
        if ($userFetched) {
            throw new UserAlreadyExistsException(sprintf('User with username %s already exists', $user->getUsername()));
        }

        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $role = $this->roleRepository->findOneBy(['name' => $type]);
        if (!$role) {
            throw new RoleNotFoundException(sprintf('Role %s not found', $type));
        }

        $user->addRole($role);

        $id = $this->userRepository->save($user);
        return $id;
    }

    /**
     * Get user by username.
     *
     * @throws UserNotFoundException
     *
     * @param string $username
     * @return User
     */
    public function getByUsername(string $username): User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * Get user by id.
     *
     * @param int $id
     *
     * @throws UserNotFoundException
     * @return User
     */
    public function getUserById(int $id): User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        return $user;
    }

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
    public function update(int $id, User $user): User
    {
        $existedUser = $this->getUserById($id);

        if ($user->getUsername() != $existedUser->getUsername()) {
            $existedUser->setUsername($user->getUsername());
        }

        if ($user->getEmail() != $existedUser->getEmail()) {
            $existedUser->setEmail($user->getEmail());
        }

        if (!$this->passwordEncoder->isPasswordValid($existedUser, $user->getPassword())) {
            $password = $this->passwordEncoder->encodePassword($existedUser, $user->getPassword());
            $existedUser->setPassword($password);
        }

        $this->userRepository->save($existedUser);
    }

    /**
     * Remove user.
     *
     * @throws UserNotFoundException
     * @param int $id
     */
    public function remove(int $id): void
    {
        $user = $this->getUserById($id);
        $user->setIsActive(false);
        $this->userRepository->save($user);
    }

}