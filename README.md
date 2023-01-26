# Recolize Recommendation Engine for Magento 1

## Getting Started

Thank you very much for using our Recolize Recommendation Engine for Magento 1.
Please find some installation advices below and more information on [recolize.com](https://www.recolize.com/?utm_source=github&utm_medium=web&utm_campaign=github-help-area).

### Prerequisites

Recolize Recommendation Engine is fully compatible with Magento versions starting from 1.5 (Community/Open Source and Enterprise/Commerce Editions).

Please note that the Magento cronjob should be set up correctly to use all features of this extension without any limitations.

### Installation

We recommend to proceed with the following preparational steps before you start the installation of the Recolize Recommendation Engine Extension for Magento 1:
* For safety reasons please create a backup of your Magento installation in order to be able to reset the system to the current state in case of an emergency
* Disable the Magento compiler feature if it is activated in your shop. The configuration can be found in _System > Tools > Compilation_

#### Installation in 5 simple steps
1.	Unpack the setup package `Recolize_Recommendation_*.zip` and upload the unpacked contents into the root directory of your Magento installation (for example via FTP).
The folder structure is exactly the same as in your Magento installation (`app/`, `skin/`, `js/`, `lib/`). In case of a fresh module installation no files will be overwritten.

2.	Flush your Magento cache, e.g. in _System > Cache Management_ as well as the JavaScript-/CSS-Cache on the same page.

3. Logout from Magento admin area and login again.

4.	[Register for free](https://www.recolize.com/en/register?utm_source=github&utm_medium=web&utm_campaign=github-help-area), login into [Recolize Tool](https://tool.recolize.com/?utm_source=github&utm_medium=web&utm_campaign=github-help-area) and create a new domain with the product feed url that is displayed in _System > Configuration > Recolize Recommendation Engine > Settings > Recolize Product Feed_.

5.	Copy the JavaScript snippet code from the domain configuration into the appropriate Magento setting at _System > Configuration > Recolize Recommendation Engine > General Settings > JavaScript Snippet Code_.

## Authors

Recolize GmbH ([https://www.recolize.com](https://www.recolize.com/?utm_source=github&utm_medium=web&utm_campaign=github-help-area)).

## License

This project is licensed under the GPLv3 License - see the [LICENSE.txt](LICENSE.txt) file for details.

## Frequently Asked Questions

### I need help with the installation process
For more information regarding the installation of the Magento extension please have a look at [our FAQs](https://www.recolize.com/en/faq?utm_source=github&utm_medium=web&utm_campaign=github-help-area).

### How can I completely disable the extension if I am experiencing any problems?
In case of an emergency you can easily disable the extension temporarily via the configuration setting in _System > Configuration > Recolize Recommendation Engine > Enable extension_.
As an alternative you can change the ``<active>`` flag in file `app/etc/modules/Recolize_RecommendationEngine.xml` to ``false``.