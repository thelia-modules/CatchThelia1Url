# Catch Thelia 1 Url

Capture unrewritten Thelia 1 URLs and perform a 301 redirection on Thelia 2 corresponding URLs
The URLs documents and images of Thelia 1 are also redirected

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CatchThelia1Url.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/catch-thelia1-url-module:~1.0
```

## Usage

* Please add in your Thelia 2 database, the correspondence tables created by the [Module ImportT1](https://github.com/thelia-modules/ImportT1) Module (t1_t2_category, t1_t2_product, t1_t2_folder, t1_t2_content)

## Roadmap

* Adding an interface for configuration of values
