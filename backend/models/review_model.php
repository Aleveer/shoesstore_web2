<?php
class ReviewModel
{
    private $id, $userId, $productId, $content, $rating;

    public function __construct($id, $userId, $productId, $content, $rating)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->content = $content;
        $this->rating = $rating;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }
}
