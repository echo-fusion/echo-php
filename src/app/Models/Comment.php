<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class Comment extends AbstractModel
{
    private string $name;
    private string $mail;
    private string $url;
    private string $comment;
    private DateTime $create_at;
    private int $blog_id;

    public function __construct()
    {
        $this->table = 'comments';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
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
    public function getBlogId(): int
    {
        return $this->blog_id;
    }

    /**
     * @param int $blog_id
     */
    public function setBlogId(int $blog_id): void
    {
        $this->blog_id = $blog_id;
    }
}