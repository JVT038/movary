<?php declare(strict_types=1);

namespace Movary\Application\Movie;

use Doctrine\DBAL\Connection;
use Movary\Api\Trakt\ValueObject\Movie\TraktId;
use Movary\ValueObject\DateTime;
use RuntimeException;

class Repository
{
    private Connection $dbConnection;

    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function create(string $title, ?int $rating, TraktId $traktId, string $imdbId, int $tmdbId) : Entity
    {
        $this->dbConnection->insert(
            'movie',
            [
                'title' => $title,
                'rating' => $rating,
                'trakt_id' => $traktId->asInt(),
                'imdb_id' => $imdbId,
                'tmdb_id' => $tmdbId,
            ]
        );

        return $this->fetchById((int)$this->dbConnection->lastInsertId());
    }

    public function fetchAll() : EntityList
    {
        $data = $this->dbConnection->fetchAllAssociative('SELECT * FROM `movie`');

        return EntityList::createFromArray($data);
    }

    public function findByTraktId(TraktId $traktId) : ?Entity
    {
        $data = $this->dbConnection->fetchAssociative('SELECT * FROM `movie` WHERE trakt_id = ?', [$traktId->asInt()]);

        return $data === false ? null : Entity::createFromArray($data);
    }

    public function updateDetails(
        int $id,
        ?string $overview,
        ?string $originalLanguage,
        ?DateTime $releaseDate,
        ?int $runtime,
        ?float $tmdbVoteAverage,
        ?int $tmdbVoteCount
    ) : Entity {
        $this->dbConnection->update(
            'movie',
            [
                'overview' => $overview,
                'original_language' => $originalLanguage,
                'release_date' => $releaseDate === null ? null : $releaseDate->format('Y-m-d'),
                'runtime' => $runtime,
                'tmdb_vote_average' => $tmdbVoteAverage,
                'tmdb_vote_count' => $tmdbVoteCount,
                'updated_at_tmdb' => DateTime::create()->format('Y-m-d H:i:s'),
            ],
            ['id' => $id]
        );

        return $this->fetchById($id);
    }

    public function updateRating(int $id, ?int $rating) : Entity
    {
        $this->dbConnection->update('movie', ['rating' => $rating], ['id' => $id]);

        return $this->fetchById($id);
    }

    private function fetchById(int $id) : Entity
    {
        $data = $this->dbConnection->fetchAssociative('SELECT * FROM `movie` WHERE id = ?', [$id]);

        if ($data === false) {
            throw new RuntimeException('No movie found by id: ' . $id);
        }

        return Entity::createFromArray($data);
    }
}