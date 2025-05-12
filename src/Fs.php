<?php
/**
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) Værsågod
 * @license   MIT
 */

namespace vaersaagod\dospaces;

use Craft;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\flysystem\base\FlysystemFs;
use craft\helpers\App;
use craft\helpers\Assets;
use craft\helpers\DateTimeHelper;

use DateTime;

use Aws\Credentials\Credentials;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\FilesystemAdapter;

/**
 * Class Fs
 *
 * @property mixed  $settingsHtml
 * @property string $rootUrl
 * @author Værsågod
 * @since  2.0
 */
class Fs extends FlysystemFs
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Digital Ocean Spaces';
    }

    // Properties
    // =========================================================================

    /**
     * @var bool Whether this is a local source or not. Defaults to false.
     */
    protected bool $isVolumeLocal = false;

    /**
     * @var string Subfolder to use
     */
    public string $subfolder = '';

    /**
     * @var string DO key ID
     */
    public string $keyId = '';

    /**
     * @var string DO key secret
     */
    public string $secret = '';

    /**
     * @var string DO Endpoint
     */
    public string $endpoint = '';

    /**
     * @var string Bucket to use
     */
    public string $bucket = '';

    /**
     * @var string Region to use
     */
    public string $region = '';

    /**
     * @var string Cache expiration period.
     */
    public string $expires = '';

    /**
     * @var string Content Disposition value.
     */
    public string $contentDisposition = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'subfolder',
                'keyId',
                'secret',
                'endpoint',
                'bucket',
                'region',
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            [['keyId', 'secret', 'region', 'bucket', 'endpoint'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('dospaces/fsSettings', [
            'fs' => $this,
            'periods' => array_merge(['' => ''], Assets::periodList()),
            'contentDispositionOptions' => [
                '' => 'none',
                'inline' => 'inline',
                'attachment' => 'attachment',
            ],
            'contentDisposition' => $this->contentDisposition,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getRootUrl(): ?string
    {
        if (($rootUrl = parent::getRootUrl()) !== false && $this->_subfolder()) {
            $rootUrl .= rtrim($this->_subfolder(), '/') . '/';
        }

        return $rootUrl;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     * @return AwsS3V3Adapter
     */
    protected function createAdapter(): FilesystemAdapter
    {
        $client = static::client($this->_getConfigArray(), $this->_getCredentials());

        return new AwsS3V3Adapter($client, App::parseEnv($this->bucket), $this->_subfolder(), null, null, [], false);
    }

    /**
     * Get the Amazon S3 client.
     *
     * @param array $config      client config
     * @param array $credentials credentials to use when generating a new token
     */
    protected static function client(array $config = [], array $credentials = []): S3Client
    {
        if (!empty($config['credentials']) && $config['credentials'] instanceof Credentials) {
            $config['generateNewConfig'] = static function() use ($credentials) {
                $args = [
                    $credentials['keyId'],
                    $credentials['secret'],
                    $credentials['region'],
                    true,
                ];

                return call_user_func_array(self::class . '::buildConfigArray', $args);
            };
        }

        return new S3Client($config);
    }

    /**
     * @inheritdoc
     */
    protected function addFileMetadataToConfig(array $config): array
    {
        if (!empty($this->expires) && DateTimeHelper::isValidIntervalString($this->expires)) {
            $expires = new DateTime();
            $now = new DateTime();
            $expires->modify('+' . $this->expires);
            $diff = (int)$expires->format('U') - (int)$now->format('U');
            $config['CacheControl'] = 'max-age=' . $diff;
            $config['ContentDisposition'] = $this->contentDisposition;
        }

        return parent::addFileMetadataToConfig($config);
    }


    // Private Methods
    // =========================================================================
    /**
     * Returns the parsed subfolder path
     */
    private function _subfolder(): string
    {
        if ($this->subfolder && ($subfolder = rtrim(App::parseEnv($this->subfolder), '/')) !== '') {
            return $subfolder . '/';
        }

        return '';
    }

    /**
     * Get the config array for AWS Clients.
     */
    private function _getConfigArray(): array
    {
        $credentials = $this->_getCredentials();

        return self::_buildConfigArray($credentials['keyId'], $credentials['secret'], $credentials['region'], $credentials['endpoint']);
    }

    /**
     * Build the config array
     *
     * @param $keyId
     * @param $secret
     * @param $region
     * @param $endpoint
     */
    private static function _buildConfigArray($keyId = null, $secret = null, $region = null, $endpoint = null): array
    {
        $config = [
            'region' => $region,
            'endpoint' => $endpoint,
            'version' => 'latest',
            'credentials' => [
                'key' => $keyId,
                'secret' => $secret,
            ],
        ];

        $client = Craft::createGuzzleClient();
        $config['http_handler'] = class_exists('Aws\\Handler\\Guzzle\\GuzzleHandler') ? new \Aws\Handler\Guzzle\GuzzleHandler($client) : new \Aws\Handler\GuzzleV6\GuzzleHandler($client);

        return $config;
    }


    /**
     * Return the credentials as an array
     */
    private function _getCredentials(): array
    {
        return [
            'keyId' => App::parseEnv($this->keyId),
            'secret' => App::parseEnv($this->secret),
            'region' => App::parseEnv($this->region),
            'endpoint' => App::parseEnv($this->endpoint),
        ];
    }

    protected function invalidateCdnPath(string $path): bool
    {
        // TODO: Not implemented
        return true;
    }
}
