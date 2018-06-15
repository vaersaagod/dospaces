<?php
/**
 * @link https://www.vaersaagod.no/
 * @copyright Copyright (c) Værsågod
 * @license MIT
 */

namespace vaersaagod\dospaces;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Volumes;
use yii\base\Event;

/**
 * Plugin represents the DigitalOcean Spaces volume plugin.
 *
 * @author Værsågod
 * @since 3.0
 */
class Plugin extends \craft\base\Plugin
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(Volumes::class,
            Volumes::EVENT_REGISTER_VOLUME_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = Volume::class;
            });
    }
}
