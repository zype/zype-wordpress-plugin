Don't know what Zype is? <a href="http://www.zype.com/" target="_blank">Learn more about Zype here</a>.

# Zype Wordpress Plugin

This free plugin allows you to turn your WordPress website into an eye-catching, easy to use video streaming destination integrated with the Zype platform with minimal coding and configuration. The plugin is built with PHP and the Zype API. With brief setup you can begin streaming video on your website.

Using the plugin you can sell subscriptions for premium video content, track analytics for video engagement, create playlists and insert videos using shortcodes, and even broadcast live events with just a few clicks.

The Zype cloud service provides publishing, monetization, streaming, audience management, and analytics software that is integrated into hundreds of web, mobile, and OTT apps and engaged by millions of viewers every month.

## Demo Website
Full functionality of the plugin can be viewed on <a href="https://zypeplugin.com/" target="_blank">Zype Plugin</a> demo website.

<a href="https://drive.google.com/uc?export=view&id=1YM2U2oq4I4kqDCH2FF2zEJEwo9EgOJwU"><img src="https://drive.google.com/uc?export=view&id=1YM2U2oq4I4kqDCH2FF2zEJEwo9EgOJwU" style="width: 500px; max-width: 100%; height: auto" title="Click for the larger version." /></a>

## Key Features & Capabilities

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

## Requirements and Prerequisites

- A valid and current Zype account
- Requires WordPress version: 4.6 or higher | Tested up to: 4.9.5
- Requires PHP: 5.6

## 


## Installation via prebundled zip archive (recommended)

Download latest release from Google Drive link in [releases](https://github.com/zype/zype-wordpress-plugin/releases) section in Github

1. Log in to the administrator panel.
2. Go to Plugins Add > New > Upload.
3. Click Choose file (Browse) and select the downloaded zip file of the zype plugin.
4. Click Install Now button.
5. Click Activate Plugin button for activating the Zype.

## Installation via cloning the repo

Clone or dowbload zype-wordpress-plugin repo. If you downloaded the ZIP file, you need to unzip the file.

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

## Website Integration

### Shortcodes
Shortcodes can be inserted anywere on your Wordpress website and will render underlying functionality based on plugin configuration.
- [zype_video id='Insert Video ID'] Will render a single video from your Zype account
- [zype_playlist id='Insert Playlist ID'] Will render a playlist and all nested content from your Zype account
- [zype_playlist id='Insert Playlist ID' zype_type=video_single] Will render all videos within a selected playlist
- [zype_auth] Will render login/signup functionality.
- [zype_signup] Will render signup functionality

### URL rewrites
URL rewrites add new pages to your website.

## Contributing to the repo

We welcome contributions to Zype Wordpress Plugin. If you have any suggestions or notice any bugs you can raise an issue. If you have any changes to the code base that you want to see added, you can fork the repository, then submit a pull request with your changes explaining what you changed, why you believe it should be added, and how one would test these changes. Thank you in advance to the community!

## Support

If you need more information on how the Zype API works, you can read [documentation here](http://dev.zype.com/api_docs/intro/). If you have any other questions, feel free to contact us at [support@zype.com](mailto:support@zype.com).

## Versioning

For the versions available, see the [tags on this repository](https://github.com/zype/zype-wordpress-plugin/tags). 

## Authors
* **Aleksandr Stolbov** - *Initial Work* - [Osoro](https://github.com/Osoro)
* **Andrey Kasatkin** - *Product Lead* - [Svetliy](https://github.com/svetdev)

See also the list of [contributors](https://github.com/zype/zype-ios/graphs/contributors) who participated in this project.

## License

This project is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details
