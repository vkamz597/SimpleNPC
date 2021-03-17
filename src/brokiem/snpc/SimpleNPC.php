<?php

declare(strict_types=1);

namespace brokiem\snpc;

use brokiem\snpc\commands\Commands;
use brokiem\snpc\entity\BaseNPC;
use brokiem\snpc\entity\CustomHuman;
use brokiem\snpc\task\async\CheckUpdateTask;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use ReflectionClass;

class SimpleNPC extends PluginBase
{
    public const ENTITY_HUMAN = "human";

    /** @var array */
    public static $npcType = [];

    private static $entities = [];

    /** @var array */
    public $removeNPC = [];
    /** @var int */
    public $maxLookDistance = 10;
    /** @var bool */
    public $lookToPlayersEnabled = true;

    public function onEnable(): void
    {
        self::registerEntity(CustomHuman::class, self::ENTITY_HUMAN, true);

        $this->initConfiguration();
        $this->getServer()->getCommandMap()->register("SimpleNPC", new Commands("snpc", $this));
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $this->getServer()->getAsyncPool()->submitTask(new CheckUpdateTask($this->getDescription()->getVersion()));
    }

    private function initConfiguration(): void
    {
        $this->saveDefaultConfig();

        $this->lookToPlayersEnabled = $this->getConfig()->get("enable-look-to-players", true);
        $this->maxLookDistance = $this->getConfig()->get("max-look-distance", 8);
    }

    public static function registerEntity(string $entityClass, string $name, bool $force = true, array $saveNames = []): void
    {
        $class = new ReflectionClass($entityClass);
        if (is_a($entityClass, BaseNPC::class, true) or is_a($entityClass, CustomHuman::class, true) and !$class->isAbstract()) {
            self::$entities[$entityClass] = array_merge($saveNames, [$name]);
            self::$npcType[] = $name;

            Entity::registerEntity($entityClass, $force, array_merge($saveNames, [$name]));
        }
    }
}