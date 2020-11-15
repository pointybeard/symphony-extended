# Extended Base Class Library for Symphony CMS

A collection of extended [Symphony CMS](https://www.getsymphony.com/) base classes that can be extended to add extra features and helpers.

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/symphony-extended` or add `"pointybeard/symphony-extended": "~1.0.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

This library requires PHP7.3 or greater.

This library also makes use of the [PHP Helpers: JSON Functions](https://github.com/pointybeard/helpers-functions-json) (`pointybeard/helpers-functions-json`). It is installed automatically via composer.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers`

## Usage

### AbstractExtension

Extend your `extension.driver.php` class with `pointybeard\Symphony\Extended\AbstractExtension` instead of `\Extension`.

Provides the following extra class method:

`status`, `handle`, `about`, and `install`

In addition, this extended class expects to find a file `extension.json` in the extension folder. This is essentially a JSON representation of the `extension.meta.xml` file however adds support for dependencies. E.g.

```json
{
    "id": "my_awesome_extension",
    "name": "My Awesome Extension",
    "description": "This describes the extension.",
    "repo": "https://github.com/pointybeard/my_awesome_extension.git",
    "homepage": "https://github.com/pointybeard/my_awesome_extension",
    "type": [
        "Utility"
    ],
    "authors": [
        {
            "name": "Alannah Kearney",
            "email": "hi@alannahkearney.com",
            "homepage": "http://alannahkearney.com",
            "role": "Developer"
        }
    ],
    "require": [
        "uuidfield",
        "uniqueinputfield",
        "selectbox_link_field"
    ],
    "releases": [
        {
            "version": "1.0.0",
            "date": "2020-03-28",
            "min": "2.7.10",
            "max": "2.x.x"
        },
        {
            "version": "0.1.0",
            "date": "2010-01-10",
            "min": "2.6.x",
            "max": "2.x.x"
        }
    ]
}
```

Dependencies are checked prior to commencing installation. If your extension has a custom `install()` method, be sure to include a call to `parent::install();`.

### AbstractSectionDatasource

Extend your custom Data Sources with `pointybeard\Symphony\Extended\AbstractSectionDatasource` instead of `\SectionDatasource`.

This extended class gives you the ability to define your `$dsParamFILTERS` array with field element names instead of ID values making your data source more portable (i.e. if the field ID changes, your data source doesn't need to be updated).

**Note: By extending AbstractSectionDatasource, your data source can no longer be edited via the Symphony Data Source editor.**

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/symphony-extended/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/symphony-extended/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"Extended Base Class Library for Symphony CMS" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
