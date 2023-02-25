<?php declare(strict_types=1);

namespace Movary\Api\Plex\Dto;

use Movary\ValueObject\Date;
use Movary\ValueObject\DateTime;
use Movary\ValueObject\PersonalRating;

class PlexItem
{
    public function __construct(
        private readonly int $itemId,
        private readonly string $type,
        private readonly string $title,
        private readonly ?PersonalRating $userRating,
        private readonly ?int $tmdbId,
        private readonly ?string $imdbId,
        private readonly ?string $lastViewedTimestamp
    ){
    }

    public static function createPlexItem(int $itemId, string $title, string $type, ?PersonalRating $userRating = null, ?int $tmdbId = null, ?string $imdbId = null, ?string $lastViewedTimestamp = null) : self
    {
        return new self($itemId, $title, $type, $userRating, $tmdbId, $imdbId, $lastViewedTimestamp);
    }

    public function getPlexItemId() : int
    {
        return $this->itemId;
    }

    public function getTmdbId() : ?int
    {
        return $this->tmdbId;
    }

    public function getImdbId() : ?string
    {
        return $this->imdbId;
    }
    
    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getUserRating() : ?PersonalRating
    {
        return $this->userRating;
    }

    public function getLastViewedAt() : ?Date
    {
        if($this->lastViewedTimestamp === null) {
            return null;
        } else {
            $dateTime = DateTime::createFromFormatAndTimestamp('U', $this->lastViewedTimestamp);
            return Date::createFromDateTime($dateTime);
        }
    }

    public function getType() : string
    {
        return $this->type;
    }
}