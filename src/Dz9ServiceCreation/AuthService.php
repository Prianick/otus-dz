<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

use Exception;
use OtusDZ\Src\Dz5IoC\IoC;

class AuthService
{
    public CredentialStorage $credentialStorage;

    public IdentityFunctions $identity;

    protected JWTFunctions $JWT;

    public function __construct()
    {
        $this->credentialStorage = IoC::resolve(CredentialStorage::class);
        $this->identity = IoC::resolve(IdentityFunctions::class);
        $this->JWT = IoC::resolve(JWTFunctions::class);
    }

    /**
     * @throws Exception
     * @return string
     */
    public function authorize(): string
    {
        $result = $this->JWT->checkToken(
            App::getInstance()->request->getJwtToken(),
            $this->credentialStorage->getSecret(App::getInstance()->request->getUserId()),
        );
        if ($result === true) {
            $this->identity->setCurrentUser(App::getInstance()->request->getUserId());

            return true;
        }

        throw new Exception('Not authorized', 403);
    }

    /**
     * @param string $login
     * @param string $pass
     * @return string
     */
    public function authenticate(string $login, string $pass): string
    {
        $token = '';

        if ($this->credentialStorage->checkPass($login, $pass)) {
            $userId = $this->credentialStorage->getUserId($login);
            $token = $this->JWT->generateSecret($userId, $pass);
            $this->credentialStorage->saveJWT($userId, $token);
            $this->identity->setCurrentUser($userId);
        }

        return $token;
    }
}
