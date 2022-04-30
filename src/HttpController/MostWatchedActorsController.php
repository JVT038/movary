<?php declare(strict_types=1);

namespace Movary\HttpController;

use Movary\Application\Movie\History\Service\Select;
use Movary\Util\Json;
use Movary\ValueObject\Http\Header;
use Movary\ValueObject\Http\Response;
use Movary\ValueObject\Http\StatusCode;

class MostWatchedActorsController
{
    public function __construct(private readonly Select $movieHistorySelectService)
    {
    }

    public function fetchMostWatchedActors() : Response
    {
        return Response::create(
            StatusCode::createOk(),
            Json::encode($this->movieHistorySelectService->fetchMostWatchedActors()),
            [Header::createContentTypeJson()]
        );
    }
}