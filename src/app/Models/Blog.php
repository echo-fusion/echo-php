<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class Blog extends AbstractModel
{
    private string $title;
    private string $image_url;
    private string $description;
    private DateTime $create_at;
    private int $user_id;

    public function __construct()
    {
        $this->table = 'blogs';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getCreateAt(): DateTime
    {
        return $this->create_at;
    }

    /**
     * @param DateTime $create_at
     */
    public function setCreateAt(DateTime $create_at): void
    {
        $this->create_at = $create_at;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}