<?php
/**
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) Værsågod
 * @license   MIT
 */

namespace vaersaagod\dospaces;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Fs as FsService;
use yii\base\Event;

/**
 * Plugin represents the DigitalOcean Spaces filesystem plugin.
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
    }
}
