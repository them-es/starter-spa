> [!WARNING]
> This theme is outdated and should not be used anymore. If you are looking for a modern Single Page Application implementation using the WP-API you should consider trying out our [ReactJS boilerplate](https://github.com/them-es/wp-react-app) instead.

## them.es Starter Theme for Single Page Applications

**them.es Starter (SPA)** is a Single Page Application Starter Theme using Web components - the future of Web Development. Please note that the Source files are only recommended for WordPress Developers who are searching for a simple, solid, proved and tested **SPA Starter Theme** to build upon. **_Don't_ expect a ready-to-use WordPress Theme!**

If you want to see it in action or want to download a customized Theme for free, check out [https://them.es/starter-SPA](https://them.es/starter-SPA).


## What's included?
* WordPress Theme
* [Polymer](https://github.com/Polymer/polymer) Web Components
* Bower configuration to install Polymer and keep required Components updated
* Theme Customizer API
* 1 Menu
* 1 Blog section


## What's not included?
* jQuery is not needed for Polymer Web Components and will not be used on the frontend anymore


## Getting Started
* Download the Starter Theme under [https://them.es/starter-SPA](https://them.es/starter-SPA) and unzip it in a new Project folder
* Prerequisites: [Bower](https://bower.io) needs to be installed on your system
* Open the **Project directory** in Terminal and run this command to install all required packages
* `$ bower install`
* Upload the Theme to your WordPress instance and activate it
* ~~Install and activate the [WP REST API Plugin](https://wordpress.org/plugins/rest-api)~~ Make sure you are using WordPress v4.7+
* Add new Pages and Blog Posts
* Select the **Front page** and the **Posts page** under `Settings / Reading`
* Create a new menu and assign the location `Main Navigation Menu`
* Use the Theme Customizer to add a logo
* Update `manifest.json` and replace all icons in `/img`
* Now it's up to you to add and build new Web elements under `/elements`
* Don't forget to keep your Polymer elements updated using Bower
* `$ bower update`


## Technology

* [Polymer](https://github.com/Polymer/polymer) and [Polymer Starter Kit](https://github.com/PolymerElements/polymer-starter-kit), [BSD license](https://github.com/Polymer/polymer/blob/master/LICENSE.txt)
* [Bower](https://github.com/bower/bower), [MIT license](https://github.com/bower/bower/blob/master/LICENSE)


## Copyright & License

Code and Documentation &copy; [them.es](https://them.es)

Code released under [GPLv2+](https://www.gnu.org/licenses/gpl-2.0.html)
