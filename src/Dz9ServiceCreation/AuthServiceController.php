<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

use OtusDZ\Src\Dz5IoC\IoC;

class AuthServiceController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = IoC::resolve(AuthService::class, []);
    }

    /**
     * @param string $login
     * @param string $pass
     * @return string
     */
    public function authenticateAction(string $login, string $pass): string
    {
        $token = $this->authService->authenticate($login, $pass);

        if (empty($token)) {
            $response = ['error' => 'User doesn\'t exist'];
        } else {
            $response = ['token' => $token];
        }

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $listOfPlayers
     * @throws \Exception
     * @return string
     */
    public function createGameAction(array $listOfPlayers): string
    {
        $this->authService->authorize();
        $userId = $this->authService->identity->getAuthUser()->getId();
        $gameId = $this->authService->credentialStorage->createGame($listOfPlayers, $userId);

        return json_encode(['gameId' => $gameId], JSON_UNESCAPED_UNICODE);
    }
}
