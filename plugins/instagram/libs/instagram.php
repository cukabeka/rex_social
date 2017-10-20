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

use Instagram\Instagram;
use Instagram\Media;
use InstagramScraper\Instagram as InstagramScraper;
use InstagramScraper\Model\Media as ScapedMedia;

abstract class rex_yfeed_stream_instagram_abstract extends rex_yfeed_stream_abstract
{
    public function fetch()
    {
        $accessToken = rex_config::get('yfeed', 'instagram_access_token');

        if ($accessToken) {
            $this->fetchOfficialApi($accessToken);

            return;
        }

        $this->fetchFrontendApi();
    }

    /**
     * @param Instagram $instagram
     *
     * @return Media[]
     */
    abstract protected function fetchItemsFromOfficialApi(Instagram $instagram);

    /**
     * @param InstagramScraper $instagram
     *
     * @return ScapedMedia[]
     */
    abstract protected function fetchItemsFromFrontendApi(InstagramScraper $instagram);

    private function fetchOfficialApi($accessToken)
    {
        $instagram = new Instagram($accessToken);
        $instagramItems = $this->fetchItemsFromOfficialApi($instagram);

        foreach ($instagramItems as $instagramItem) {
            $item = new rex_yfeed_item($this->streamId, $instagramItem->getId());
            $item->setTitle($instagramItem->getCaption());

            $item->setUrl($instagramItem->getLink());
            $item->setDate(new DateTime($instagramItem->getCreatedTime('Y-m-d H:i:s')));

            $item->setMedia($instagramItem->getStandardResImage()->url);

            $item->setAuthor($instagramItem->getUser()->getFullName());
            $item->setRaw($instagramItem);

            $this->updateCount($item);
            $item->save();
        }
    }

    private function fetchFrontendApi()
    {
        $instagram = new InstagramScraper();
        $instagramItems = $this->fetchItemsFromFrontendApi($instagram);

        $owners = [];

        foreach ($instagramItems as $instagramItem) {
            $item = new rex_yfeed_item($this->streamId, $instagramItem->id);
            $item->setTitle(isset($instagramItem->caption) ? $instagramItem->caption : null);

            $item->setUrl($instagramItem->link);
            $item->setDate(new DateTime('@'.$instagramItem->createdTime));

            $item->setMedia($instagramItem->imageStandardResolutionUrl);

            if (!isset($instagramItem->owner->fullName)) {
                if (isset($owners[$instagramItem->ownerId])) {
                    $instagramItem->owner = $owners[$instagramItem->ownerId];
                } else {
                    $itemWithAuthor = $instagram->getMediaByUrl($instagramItem->link);
                    if (isset($itemWithAuthor->owner->fullName)) {
                        $instagramItem->owner = $itemWithAuthor->owner;
                        $owners[$instagramItem->ownerId] = $itemWithAuthor->owner;
                    }
                }
            }

            if (isset($instagramItem->owner->fullName)) {
                $item->setAuthor($instagramItem->owner->fullName);
            }

            $item->setRaw($instagramItem);

            $this->updateCount($item);
            $item->save();
        }
    }
}
