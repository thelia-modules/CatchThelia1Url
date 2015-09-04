# Catch Thelia 1 Url

Capture unrewritten Thelia 1 URLs and perform a 301 redirection on Thelia 2 corresponding URLs

The URLs documents and images of Thelia 1 are also redirected


|Examples of urls captured |
|--- |
|mysite.com/client/gfx/photos/....... |
|mysite.com/client/cache/....... |
|mysite.com/client/document/....... |
|mysite.com/produit.php?id_produit=x |
|mysite.com/rubrique.php?id_rubrique=x |
|mysite.com/dossier.php?id_dossier=x |
|mysite.com/contenu.php?id_contenu=x |
|mysite.com/?fond=produit&id_produit=x |
|mysite.com/?fond=rubrique&id_rubrique=x |
|mysite.com/?fond=dossier&id_dossier=x |
|mysite.com/?fond=contenu&id_contenu=x |


## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CatchThelia1Url.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/catch-thelia1-url-module:~0.1
```

## Usage

* [Not Required] Please add in your Thelia 2 database, the correspondence tables created by the [Module ImportT1](https://github.com/thelia-modules/ImportT1) Module (t1_t2_category, t1_t2_product, t1_t2_folder, t1_t2_content)

## Roadmap

* Adding an interface for configuration of values
