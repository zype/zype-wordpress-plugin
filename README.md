Don't know what Zype is? Check this [overview](http://www.zype.com/).

# Zype Wordpress Plugin

This free plugin allows you to transform your WordPress website into an eye-catching, easy to use video streaming destination integrated with the Zype platform with minimal coding and configuration. The plugin is built with PHP and the Zype API. With minimal setup you can have your website up and running.

Using the plugin you can sell subscriptions to media content keep statistics on video views and audio, create playlists and insert video with shortcode and to display the live broadcast.

The Zype cloud service provides publishing, monetization, streaming, audience management, and analytics software that is integrated into hundreds of web, mobile, and OTT apps and engaged by millions of viewers every month.

## Key Features & Capabilities

- Easily add videos and playlist galleries to any page or post using shortcodes.
- Responsive design instantly works on mobile and desktop with any theme.
- Stream your content from a wide range of sources including native video uploads, YouTube, Vimeo, Hulu, and more.
- Your branding front and center with your own custom branded HTML5 video player.
- Generate revenue with built-in subscription, purchase, and rental paywalls to make more money from your premium content.
- Maximize ad revenue with preroll, midroll, and postroll ads, including support for dynamic ad pairing.
- Broadcast high quality Live Streams anywhere on your website with just a few clicks.
- Improve discovery and navigation for your audience by displaying playlist galleries showcasing your entire video library.

## Monetization Supported

- Subscription
- Ads

# Installation

## Requirements and Prerequisites

- A valid and current Zype account
- Requires WordPress version: 4.6 or higher | Tested up to: 4.8.3
- Requires PHP: 5.6

## Manual Installation

After downloading the ZIP file of the zype plugin,

1. Log in to the administrator panel.
2. Go to Plugins Add > New > Upload.
3. Click Choose file (Browse) and select the downloaded zip file of the zype plugin.
*For Mac Users*
*Go to your Downloads folder and locate the folder with the zype. Right-click on the folder and select Compress. Now you have a newly created .zip file which can be installed as described here.*
4. Click Install Now button.
5. Click Activate Plugin button for activating the Zype.

## Configuration

API Keys & Consumer Settings
- Following installation you must import API keys from your Zype account. API keys will automatically validate as they are being added.
- In order to support consumer management, you must also import your consumer OAuth keys and secret.

Enhanced Playlists
- To set up enhanced playlists, there needs to be a root playlist set up on the platform. To set the root playlist, you can go to your Apple TV app settings under __Manage Apps__ and set the __Featured Playlist__ to your root playlist's id.

Monetization
- In order to use SVOD on the website, Braintree settings need to be configured in the plugin configuration as well as on the Zype platform

## Integration

### Shortcodes
Shortcodes can be inserted anywere in the content and will render underlying functionality based on plugin configuration.
- [zype_auth] Will render a login/signup functionality.
- [zype_signup] Will render signup functionality
- [zype_video id='Insert Video ID'] Will render a single video from Zype account
- [zype_playlist id='Insert Playlist ID'] Will render playlist from Zype account
- [zype_playlist id='Insert Playlist ID' zype_type=video_single] Will render all videos under selected playlist

### Url rewrites
URL rewrites adds new pages to your website.

## Contributing to the repo

We welcome contributions to Zype Wordpress Plugin. If you have any suggestions or notice any bugs you can raise an issue. If you have any changes to the code base that you want to see added, you can fork the repository, then submit a pull request with your changes explaining what you changed, why you believe it should be added, and how one would test these changes. Thank you to the community!

## Support

If you need more information on how Zype API works, you can read the [documentation here](http://dev.zype.com/api_docs/intro/). If you have any other questions, feel free to contact us at [support@zype.com](mailto:support@zype.com).

## Versioning

For the versions available, see the [tags on this repository](https://github.com/zype/zype-wordpress-plugin/tags). 

## Authors
* **Aleksandr Stolbov** - *Initial Work* - [Osoro](https://github.com/Osoro)
* **Andrey Kasatkin** - *Product Lead* - [Svetliy](https://github.com/svetdev)

See also the list of [contributors](https://github.com/zype/zype-ios/graphs/contributors) who participated in this project.

## License

This project is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details
