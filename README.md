Don't know what Zype is? <a href="http://www.zype.com/" target="_blank">Learn more about Zype here</a>.

# Table of Contents

- [Zype Wordpress Plugin](#zype-wordpress-plugin)
  - [Demo Website](#demo-website)
  - [Key Features & Capabilities](#features-capabilities)
  - [Monetization Supported](#monetization-supported)
- [Instalation](#installation)
  - [Requirements and Prerequisites](#requirements)
  - [Installation via prebundled zip archive (Recommended)](#installation-zip)
  - [Installation via cloning the repo (Optional for developers)](#installation-devs)
  - [Configurationn](#configuration)
- [Website Integration](#website-integration)
  - [Shortcodes](#shortcodes)
  - [URL rewrites](#url-rewrites)
- [Contributing to the repo](#contributing-to-the-repo)
  - [How to start the project](#how-to-start-the-project)
  - [Creating a new release](#new-release)
- [Support](#support)
- [Versioning](#versioning)
- [License](#license)

# Zype Wordpress Plugin

This free plugin allows you to turn your WordPress website into an eye-catching, easy to use video streaming destination integrated with the Zype platform with minimal coding and configuration. The plugin is built with PHP and the Zype API. With brief setup you can begin streaming video on your website.

Using the plugin you can sell subscriptions for premium video content, track analytics for video engagement, create playlists and insert videos using shortcodes, and even broadcast live events with just a few clicks.

The Zype cloud service provides publishing, monetization, streaming, audience management, and analytics software that is integrated into hundreds of web, mobile, and OTT apps and engaged by millions of viewers every month.


  ## Demo Website
  Full functionality of the plugin can be viewed on <a href="https://zypeplugin.com/" target="_blank">Zype Plugin</a> demo website.

  <a href="https://drive.google.com/uc?export=view&id=1YM2U2oq4I4kqDCH2FF2zEJEwo9EgOJwU"><img src="https://drive.google.com/uc?export=view&id=1YM2U2oq4I4kqDCH2FF2zEJEwo9EgOJwU" style="width: 500px; max-width: 100%; height: auto" title="Click for the larger version." /></a>

  ## Key Features & Capabilities <a name="features-capabilities"></a>

  - Easily add videos and playlist galleries to any page or post using shortcodes.
  - Responsive design instantly works on mobile and desktop with any theme.
  - Stream your content from a wide range of sources including native video uploads, YouTube, Vimeo, Hulu, and more.
  - Your branding front and center with your own custom branded HTML5 video player.
  - Generate revenue with built-in subscription paywalls to make more money from your premium content.
  - Maximize ad revenue with preroll, midroll, and postroll ads, including support for dynamic ad pairing.
  - Broadcast high quality Live Streams anywhere on your website with just a few clicks.
  - Improve discovery and navigation for your audience by displaying playlist galleries showcasing your entire video library.

  ## Monetization Supported

  - Subscription
  - Ads

# Installation

## Requirements and Prerequisites <a name="requirements"></a>

- A valid and current Zype account
- Requires WordPress version: 4.6 or higher | Tested up to: 4.9.5
- Requires PHP: 5.6

## Installation via docker compose(Recommended for developers)

The repo includes a dockerfile ready to download the dependencies and run the aplication(with xdebug v3 support for debugging). To setup everything you only need to run:

```
  docker-compose build
  docker-compose up -d
  docker-compose exec wordpress bash -c "cd /var/www/html/wp-content/plugins/zype-plugin && composer install --no-scripts"
```

The `composer install` step downloads the project dependencies, so is actually only needed to run the first time and whenever you add a new dependency.

## Installation via prebundled zip archive <a name="installation-zip"></a>

Download latest release from Google Drive link in [releases](https://github.com/zype/zype-wordpress-plugin/releases) section in Github

1. Log in to the administrator panel.
2. Go to Plugins Add > New > Upload.
3. Click Choose file (Browse) and select the downloaded zip file of the zype plugin.
4. Click Install Now button.
5. Click Activate Plugin button for activating the Zype.

## Installation via cloning the repo (Optional for developers) <a name="installation-devs"></a>

Clone or download zype-wordpress-plugin repo. If you downloaded the ZIP file, you need to unzip the file.

1. Open up **Terminal**. Navigate inside downloaded repo.

```shell
cd path/to/plugin-folder # change directory to plugin folder
```

2.  Enter the following command to get the required libraries:
```shell
composer install # get the latest version of required libraries with Composer
```

3. After the libraries have been updated, ZIP the plugin folder.

4. Manually add a new plugin in Wordpress and upload the ZIP file you just zipped.

## Configuration

API Keys & Consumer Settings
- Following installation you must import API and app keys from your Zype account. API and app keys will automatically validate as they are being added.
- In order to support consumer management, you must also import your consumer OAuth keys and secret.

Enhanced Playlists
- To set up enhanced playlists, there needs to be a root playlist set up in your Zype account. To create a root playlist, you can visit the [Manage Playlist Relationships](https://admin.zype.com/playlists/relationships) page in Zype and create a parent/child playlist structure.

Monetization
- In order to use subscription monetization on your Wordpress website, you must have a Braintree or Stripe account and have your Braintree / Stripe settings configured in the plugin as well as in the Zype platform.

# Website Integration

## Shortcodes
Shortcodes can be inserted anywere on your Wordpress website and will render underlying functionality based on plugin configuration.
- [zype_video id='Insert Video ID'] Will render a single video from your Zype account
- [zype_playlist id='Insert Playlist ID'] Will render a playlist and all nested content from your Zype account
- [zype_playlist id='Insert Playlist ID' zype_type=video_single] Will render all videos within a selected playlist
- [zype_auth] Will render login/signup functionality.
- [zype_signup] Will render signup functionality

## URL rewrites
URL rewrites add new pages to your website.

# Contributing to the repo

We welcome contributions to Zype Wordpress Plugin. If you have any suggestions or notice any bugs you can raise an issue. If you have any changes to the code base that you want to see added, you can fork the repository, then submit a pull request with your changes explaining what you changed, why you believe it should be added, and how one would test these changes. Thank you in advance to the community!

## How to start the project

Since the project is dockerized just runnning the following will have a wordpress running with the Zypw Wordpress plugin:

```bash
docker-compose build && docker-compose up -d
```

if this is the first time you run the project (or if you added a new dependency) remember that you must download the dependencies (check _Installation via docker compose_ section to see the command to download them)

Also you will probably need to change the `Permalink Settings` to `Post name` if not the routes from `resources/routes.php` won't work

## Creating Initial user
The first time you start the app it will ask you to sign up a new user. If by some reason you removed it from the DB or you just want to do a fresh start you can remove the volumes and build the images again from scratch:

``` 
docker-compose kill && docker-compose rm
docker volume rm zype-wordpress-plugin_db zype-wordpress-plugin_wordpress
docker rmi zype-wordpress-plugin_wordpress
docker-compose build
docker-compose up -d
```

## Debugging

### VS Code

In your launch.json add the following configuration:

```json
{
    "name": "Listen for XDebug",
    "type": "php",
    "request": "launch",
    "port": 9000,
    "log": true,
    "pathMappings": {
      "/var/www/html/wp-content/plugins/zype-plugin": "${workspaceRoot}"
    },
    "xdebugSettings": {
        "max_data": 65535,
        "show_hidden": 1,
        "max_children": 100,
        "max_depth": 5
    }
}
```

And just click play on

## Creating new release <a name="new-release"></a>

If you want to create a release you must remove:

1. Once your code is in master, create a new version: `git tag X.X.X` and push the tag to the repo.
2. Duplicate the project folder and rename the copy to something like `zype-wordpress-plugin-vX.X.X`
3. Remove the `.git` folder, the `docker-compose.yml` and `Dockerfile` files.
4. Compress the folder into a zip file.
5. Go to the [releases](https://github.com/zype/zype-wordpress-plugin/releases) page and create the release `Zype Wordpress Plugin vX.X.X` and upload the zipped file to the release.

# Support

If you need more information on how the Zype API works, you can read [documentation here](http://dev.zype.com/api_docs/intro/). If you have any other questions, feel free to contact us at [support@zype.com](mailto:support@zype.com).

# Versioning

For the versions available, see the [tags on this repository](https://github.com/zype/zype-wordpress-plugin/tags).

See also the list of [contributors](https://github.com/zype/zype-wordpress-plugin/graphs/contributors) who participated in this project.

# License

This project is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details

# Production account

There's a Zype production account https://zypeplugin.com/
