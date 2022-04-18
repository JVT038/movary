<?php declare(strict_types=1);

namespace Movary\Application\Movie\Genre\Service;

use Movary\Application\Movie\Genre\Repository;

class Create
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function create(int $movieId, int $genreId, int $position) : void
    {
        $this->repository->create($movieId, $genreId, $position);
    }
}