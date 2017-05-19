<?php

namespace AppBundle\Entity;

class BlogEntryForm
{
    protected $title;
    protected $description;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
	return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
	return $this;
    }
}
