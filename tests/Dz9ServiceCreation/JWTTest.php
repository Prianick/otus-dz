<?php

namespace Dz9ServiceCreation;

use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz9ServiceCreation\AuthService;
use OtusDZ\Src\Dz9ServiceCreation\AuthServiceController;
use OtusDZ\Src\Dz9ServiceCreation\CredentialStorage;
use OtusDZ\Src\Dz9ServiceCreation\IdentityFunctions;
use OtusDZ\Src\Dz9ServiceCreation\JWT;
use OtusDZ\Src\Dz9ServiceCreation\JWTFunctions;
use OtusDZ\Src\Dz9ServiceCreation\RequestFunctions;
use OtusDZ\Src\Dz9ServiceCreation\RouterFunctions;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    protected CredentialStorage $storage;

    public function setUp(): void
    {
        $this->storage = $this->createMock(CredentialStorage::class);

        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, function () {
            return new IoCRegister();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, IdentityFunctions::class, function () {
            return $this->createMock(IdentityFunctions::class);
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, JWTFunctions::class, function () {
            return new JWT();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, CredentialStorage::class, function () {
            return $this->storage;
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, RouterFunctions::class, function () {
            return $this->createMock(RouterFunctions::class);
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, AuthService::class, function () {
            return new AuthService();
        })->execute();

        parent::setUp();
    }

    public function testAuth()
    {
        $this->storage->method('checkPass')->willReturn(true);
        $this->storage->method('getSecret')->willReturn('some_secret');
        // аутентифируемся и получаем jwt
        $login = 'login';
        $pass = md5('pass');

        $authService = new AuthServiceController();
        $response = $authService->authenticateAction($login, $pass);
        $this->assertTrue(!empty(json_decode($response, true)['token']));
    }

    public function testGameCreate()
    {
        $jwt = new JWT();
        $secret = 'some_secret';
        $gameId = 1234;
        $this->storage->method('getSecret')->willReturn($secret);
        $this->storage->method('createGame')->willReturn($gameId);
        $payload = [
            'operationId' => 'createGame',
            'userId' => 0,
        ];
        $jwtToken = $jwt->getHash($payload, $secret);

        $data = ['player1', 'player2'];

        IoC::resolve(IoC::IOC_REGISTER, RequestFunctions::class, function () use ($jwtToken, $payload) {
            $mock = $this->createMock(RequestFunctions::class);
            $mock->method('getJwtToken')->willReturn($jwtToken);
            $mock->method('getUserId')->willReturn($payload['userId']);

            return $mock;
        })->execute();
        $authService = new AuthServiceController();
        $result = $authService->createGameAction($data);
        $this->assertEquals(json_decode($result, true)['gameId'], $gameId);
    }
}
