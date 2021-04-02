<?php
declare(strict_types=1);

namespace brokiem\snpc\entity\npc;

use brokiem\snpc\entity\BaseNPC;
use pocketmine\entity\Entity;

class WitchNPC extends BaseNPC {

    public const SNPC_ENTITY_ID = Entity::WITCH;

    public $height = 1.95;
    public $width = 1;
}
