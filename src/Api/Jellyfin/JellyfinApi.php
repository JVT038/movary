<?php declare(strict_types=1);

namespace Movary\Api\Jellyfin;

use Movary\Api\Jellyfin\Dto\JellyfinAccessToken;
use Movary\Api\Jellyfin\Dto\JellyfinAuthenticationData;
use Movary\Api\Jellyfin\Dto\JellyfinUser;
use Movary\Api\Jellyfin\Dto\JellyfinUserId;
use Movary\Domain\User\UserApi;
use Movary\Service\ServerSettings;
use Movary\ValueObject\RelativeUrl;
use Movary\ValueObject\Url;
use Psr\Log\LoggerInterface;

class JellyfinApi
{
    public function __construct(
        private readonly JellyfinClient $jellyfinClient,
        private readonly ServerSettings $serverSettings,
        private readonly UserApi $userApi,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function deleteJellyfinAccessToken(JellyfinAuthenticationData $jellyfinAuthentication) : void
    {
        $url = $jellyfinAuthentication->getServerUrl()->appendRelativeUrl(RelativeUrl::create('/Users/'));

        $query = [
            'id' => $this->serverSettings->requireJellyfinDeviceId()
        ];
        $this->jellyfinClient->delete($url, $query, $jellyfinAuthentication->getAccessToken());
        $this->logger->info('Jellyfin access token has been invalidated');
    }

    public function fetchJellyfinServerInfo(Url $jellyfinServerUrl) : ?array
    {
        $url = $jellyfinServerUrl->appendRelativeUrl(RelativeUrl::create('/system/info/public'));

        return $this->jellyfinClient->get($url);
    }

    public function fetchJellyfinUser(JellyfinAuthenticationData $jellyfinAuthentication) : ?JellyfinUser
    {
        $relativeUrl = RelativeUrl::create('/Users/' . $jellyfinAuthentication->getUserId());

        $url = $jellyfinAuthentication->getServerUrl()->appendRelativeUrl($relativeUrl);

        $userInformation = $this->jellyfinClient->get($url, jellyfinAccessToken: $jellyfinAuthentication->getAccessToken());

        if ($userInformation === null) {
            return null;
        }

        return JellyfinUser::create(JellyfinUserId::create($userInformation['Id']), $userInformation['Name']);
    }

    public function createJellyfinAuthentication(int $userId, string $username, string $password) : ?JellyfinAuthenticationData
    {
        $jellyfinServerUrl = $this->userApi->findJellyfinServerUrl($userId);
        if ($jellyfinServerUrl === null) {
            throw new \RuntimeException('Server url required');
        }

        $url = $jellyfinServerUrl->appendRelativeUrl(RelativeUrl::create('/Users/authenticatebyname'));

        $data = [
            'Username' => $username,
            'Pw' => $password
        ];
        $response = $this->jellyfinClient->post($url, data: $data);
        if ($response === null) {
            return null;
        }

        $this->logger->info('Jellyfin account has been authenticated for user: ' . $userId);

        return JellyfinAuthenticationData::create(
            JellyfinAccessToken::create((string)$response['AccessToken']),
            JellyfinUserId::create((string)$response['User']['Id']),
            $jellyfinServerUrl,
        );
    }
}
