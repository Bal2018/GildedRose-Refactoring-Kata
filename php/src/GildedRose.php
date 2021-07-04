<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item) {
                case ($item->name === 'Conjured') :
                {
                    $item->quality = $item->quality - 2;
                    break;
                }

                case ($item->name === 'Aged Brie') :
                {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                    $item->sell_in = $item->sell_in - 1;
                    if ($item->sell_in < 0) {
                        if ($item->quality < 50) {
                            $item->quality = $item->quality + 1;
                        }
                    }
                    break;
                }
                case ($item->name === 'Backstage passes to a TAFKAL80ETC concert') :
                {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                    $item->quality = $item->quality + 1;
                                }
                            }
                    }

                    $item->sell_in = $item->sell_in - 1;
                    if ($item->sell_in < 0) {
                        $item->quality = $item->quality - $item->quality;
                    }
                    break;
                }

                case  ($item->name != 'Aged Brie' and
                    $item->name != 'Backstage passes to a TAFKAL80ETC concert' and
                    $item->name != 'Sulfuras, Hand of Ragnaros') :
                {
                   if ($item->quality > 0) {
                        $item->quality = $item->quality - 1;
                    }

                    $item->sell_in = $item->sell_in - 1;

                    if ($item->sell_in < 0) {
                        if ($item->quality > 0) {
                            $item->quality = $item->quality - 1;
                        }
                    }

                    break;
                }

            }
        }
    }
}
