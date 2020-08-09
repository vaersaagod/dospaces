# DigitalOcean Spaces Volume

This plugin provides an [DigitalOcean Spaces](https://www.digitalocean.com/products/spaces/) integration for [Craft CMS](https://craftcms.com/).

The plugin is based on the first-party [AWS S3 Volume for Craft](https://github.com/craftcms/aws-s3/tree/master/src), and behaves in much the same way since Spaces is S3 compatible.  

## Requirements

This plugin requires Craft CMS 3.0.0 or later. 

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require vaersaagod/dospaces

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for "DigitalOcean Spaces Volume".

## Usage

To create a new asset volume for your Spaces bucket, go to Settings → Assets, create a new volume, and set the Volume Type setting to “DigitalOcean Spaces”.

### Per-Environment Configuration

Once you’ve created your Spaces volume in the Control Panel, you can override its settings with different values for each environment.

First, add the following environment variables to your `.env` and `.env.example` files:

```
# The DigitalOcean API key with read/write access to Spaces
SPACES_API_KEY=""

# The DigitalOcean API key secret
SPACES_SECRET=""

# The (origin) API endpoint (w/o bucket name, ie https://nyc3.digitaloceanspaces.com)
SPACES_ENDPOINT=""

# The region the Spaces bucket is in
SPACES_REGION=""

# The name of the Spaces bucket
SPACES_BUCKET=""

``` 

Then fill in the values in your `.env` file (leaving the values in `.env.example` blank).

Finally, create a `config/volumes.php` file containing references to these variables:

```php
<?php

return [
    'mySpacesVolumeHandle' => [
        'hasUrls' => true,
        'url' => 'https://my-spaces-bucket.ams3.digitaloceanspaces.com/',
        'keyId' => getenv('SPACES_API_KEY'),
        'secret' => getenv('SPACES_SECRET'),
        'endpoint' => getenv('SPACES_ENDPOINT'),
        'region' => getenv('SPACES_REGION'),
        'bucket' => getenv('SPACES_BUCKET'),
    ],
];
```


## Price, license and support

The plugin is released under the MIT license, meaning you can do what ever you want with it as long as you don't blame us. **It's free**, which means there is absolutely no support included, but you might get it anyway. Just post an issue here on github if you have one, and we'll see what we can do. 
