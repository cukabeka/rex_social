<?php

/**
 * This file is part of the YFeed package.
 *
 * @author (c) Yakamara Media GmbH & Co. KG
 * @author thomas.blum@redaxo.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class rex_yfeed_stream_abstract
{
    protected $typeParams = [];
    protected $streamId;
    protected $title;
    protected $etag;
    protected $lastModified;
    protected $countAdded = 0;
    protected $countUpdated = 0;
    protected $countNotUpdatedChangedByUser = 0;

    public function setTypeParams(array $params)
    {
        $this->typeParams = $params;
    }

    public function setStreamId($value)
    {
        $this->streamId = $value;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setEtag($value)
    {
        $this->etag = $value;
    }

    public function setLastModified($value)
    {
        $this->lastModified = $value;
    }

    public function getAddedCount()
    {
        return $this->countAdded;
    }

    public function getUpdateCount()
    {
        return $this->countUpdated;
    }

    public function getChangedByUserCount()
    {
        return $this->countNotUpdatedChangedByUser;
    }

    abstract public function getTypeName();

    abstract public function getTypeParams();

    abstract public function fetch();

    protected function updateCount(rex_yfeed_item $item)
    {
        if ($item->changedByUser()) {
            ++$this->countNotUpdatedChangedByUser;
        } elseif ($item->exists()) {
            ++$this->countUpdated;
        } else {
            ++$this->countAdded;
        }
    }
}
