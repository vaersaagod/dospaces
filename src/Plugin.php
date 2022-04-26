<?php
/**
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) Værsågod
 * @license   MIT
 */

namespace vaersaagod\dospaces;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Fs as FsService;
use craft\services\Volumes;
use yii\base\Event;

/**
 * Plugin represents the DigitalOcean Spaces volume plugin.
 *
 * @author Værsågod
 * @since  2.0
 */
class Plugin extends \craft\base\Plugin
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        Event::on(FsService::class, FsService::EVENT_REGISTER_FILESYSTEM_TYPES, static function(RegisterComponentTypesEvent $event) {
            $event->types[] = Fs::class;
        });

        /*
        Event::on(Volumes::class,
            Volumes::EVENT_REGISTER_VOLUME_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = Volume::class;
            });
        */
    }
}
