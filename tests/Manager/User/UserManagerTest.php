<?php


namespace App\Tests\Manager\User;

use App\Entity\User\User;
use App\Manager\User\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class UserManagerTest extends WebTestCase
{
    /**
     * @var Container $appContainer
     */
    private $appContainer;

    public function setUp()
    {
        self::bootKernel();
        $this->appContainer = self::$kernel->getContainer();
    }

    public function testCreateUser()
    {
        $testUser = new User();
        $testUser->setUsername('testuser')
            ->setPassword('testpassword')
            ->setEmail('test@mail.com')
            ->setKarma(0)
            ->setIsActive(true);

        $userManager = $this->appContainer->get('test.App\Manager\User\UserManager');
        $id = $userManager->create($testUser, UserManagerInterface::TYPE_USER);
        $this->assertEquals(1, $id);

        $em = $this->appContainer->get('doctrine')->getManager();
        $userRepository = $em->getRepository('App:User\User');

        $createdUser = $userRepository->find($id);

        $this->assertInstanceOf(User::class, $createdUser);

        $this->assertEquals('testuser', $createdUser->getUsername());
        $this->assertEquals('test@mail.com', $createdUser->getEmail());

        $pass = $this->appContainer
            ->get('security.password_encoder')->isPasswordValid($createdUser, 'testpassword');
        $this->assertTrue($pass);


    }
}
