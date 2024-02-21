# DigitalOcean Spaces Filesystem

This plugin provides an [DigitalOcean Spaces](https://www.digitalocean.com/products/spaces/) integration for [Craft CMS](https://craftcms.com/).

## Requirements

This plugin requires Craft CMS 5.0.0-beta.2 or later. 

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require vaersaagod/dospaces

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for "DigitalOcean Filesystem".

## Usage

To create a new asset filesystem for your Spaces bucket, go to Settings → Filesystems, 
create a new filesystem, and set the Filesystem Type setting to “DigitalOcean Spaces”.
When configuring your filesystem, make sure you use env-variables, since some of the
settings contain secrets that should not be exposed through your project
config. [Read the following docs](https://craftcms.com/docs/4.x/config/#environmental-configuration), 
and create variables as needed.

**Please note: If you want to use the Spaces CDN functionality, you only
need to change the Base URL setting to your `.cdn.` URL. The endpoint URL
should still be the one without `.cdn.`.**


## Price, license and support

The plugin is released under the MIT license, meaning you can do whatever 
you want with it as long as you don't blame us. **It's free**, which means 
there is absolutely no support included, but you might get it anyway. Just 
post an issue here on github if you have one, and we'll see what we can do. 
