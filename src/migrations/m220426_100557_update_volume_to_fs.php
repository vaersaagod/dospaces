<?php

namespace vaersaagod\dospaces\migrations;

use Craft;
use craft\db\Migration;
use craft\services\ProjectConfig;
use vaersaagod\dospaces\Fs;

/**
 * m220426_100557_update_volume_to_fs migration.
 */
class m220426_100557_update_volume_to_fs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $projectConfig = Craft::$app->getProjectConfig();

        // Don’t make the same changes twice
        $schemaVersion = $projectConfig->get('plugins.dospaces.schemaVersion', true);
        if (version_compare($schemaVersion, '2.0', '>=')) {
            return true;
        }

        $fsConfigs = $projectConfig->get(ProjectConfig::PATH_FS) ?? [];
        foreach ($fsConfigs as $uid => $config) {
            if ($config['type'] === 'vaersaagod\dospaces\Volume') {
                $config['type'] = Fs::class;
                $projectConfig->set(sprintf('%s.%s', ProjectConfig::PATH_FS, $uid), $config);
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m220426_100557_update_volume_to_fs cannot be reverted.\n";
        return false;
    }
}
