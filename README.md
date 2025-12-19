
![Adeliom](./doc/adeliom.jpg)

> **Note:** This is a read-only repository. All pull requests must be made on [agence-adeliom/easyadmin-sandbox](https://github.com/agence-adeliom/easyadmin-sandbox).

# Easy Fields Bundle

Provide some fields for Easyadmin.

## Versions

| Repository Branch | Version | Symfony Compatibility | PHP Compatibility | Status                     |
|-------------------|---------|-----------------------|-------------------|----------------------------|
| `3.x`             | `3.x`   | `6.4`, and `7.x`      | `8.2` or higher   | New features and bug fixes |
| `2.x`             | `2.x`   | `5.4`, and `6.x`      | `8.0.2` or higher | No longer maintained       |
| `1.x`             | `1.x`   | `4.4`, and `5.x`      | `7.2.5` or higher | No longer maintained       |

## Installation with Symfony Flex

Add our recipes endpoint

```json
{
  "extra": {
    "symfony": {
      "endpoint": [
        "https://api.github.com/repos/agence-adeliom/symfony-recipes/contents/index.json?ref=flex/main",
        ...
        "flex://defaults"
      ],
      "allow-contrib": true
    }
  }
}
```

Install with composer

```bash
composer require agence-adeliom/easy-fields-bundle
```

## Documentation

### AssociationField

Is an extension of EasyAdmin's AssociationField that allow you to create new object et select one from the curent page.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;

// You have to add this form theme @EasyFields/form/association_widget.html.twig
...
yield AssociationField::new('property', "label");
```

### FormTypeField

This field is a custom integration that allow you to bind any raw form type to your admin.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
...
yield FormTypeField::new('property', "label", YourFormTypeClass::class)
```

### TranslationField

An A2lix TranslationFormBundle integration for EasyAdmin.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;

// You have to add this form theme @EasyFields/form/translations_widget.html.twig
...
yield TranslationField::new('property', "label", [
    'description' => [
        'field_type' => 'textarea',
        'label' => 'descript.',
        'locale_options' => [
            'es' => ['label' => 'descripción']
            'fr' => ['display' => false]
        ]
    ]
])
```

### ChoiceMaskField

An fork of Sonata's ChoiceMaskField for EasyAdmin.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;

// You have to add this form theme @EasyFields/form/choice_mask_widget.html.twig
...
yield ChoiceMaskField::new('property', "label")
    ->setChoices([
        'uri' => 'uri',
        'route' => 'route',
    ])
    // Associative array. Describes the fields that are displayed for each choice.
    ->setMap([
        'route' => ['route', 'parameters'],
        'uri' => ['uri'],
    ]);
```

### SortableCollectionField

Is an extension of EasyAdmin's CollectionField that allow you to sort entries.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;

// You have to add this form theme @EasyFields/form/sortable_widget.html.twig
...
// NOTE : property can be a *ToMany or an array.
yield SortableCollectionField::new('property', "label")
    ->setEntryType(YourEntryFromType::class)
    ->allowAdd() // Allow to add new entry
    ->allowDelete() // Allow to remove entries
    ->allowDrag()  // Allow to drag entries
    ;
```

### IconField

Is an icon picker.

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\IconField;

// You have to add this form theme @EasyFields/form/icon_widget.html.twig
...
yield IconField::new('property', "label")
    ->setJsonUrl($url) // Must be a public json file with an array of your icon's classes
    ->setFonts($fonts) // Must be an array of yours fonticon css file
    ->setSelectButtonLabel() // Change label
    ->setCancelButtonLabel()  // Change label
    ->setShowAllButtonLabel()  // Change label
    ->setSearchPlaceholder()  // Change label
    ->setNotResultMessage()  // Change label
    ->setDeleteLabel()
    ;
```

### PositionSortableField

#### Usage
```php
use Adeliom\EasyFieldsBundle\Admin\Field\PositionSortableField;

// You have to add this form theme @EasyFields/form/form-easy-field-position-sortable.html.twig
...
yield PositionSortableField::new('property', "label");
```

### OembedField

To use this field, you need to add the bundle specific routes:  

```yaml
# config/routes/easy_fields.yaml

easy_fields:
  resource: '@EasyFieldsBundle/Resources/config/routes.xml'
  prefix: /
```

#### Usage
```php
use Adeliom\EasyFieldsBundle\Admin\Field\OembedField;

// You have to add this form theme @EasyFields/form/oembed_widget.html.twig
...
yield OembedField::new('property', "label");
```

##### Twig render

```php
# Get HTML code
{{ property|oembed_html }}

# Get Dimensions
{{ property|oembed_size }}
```

## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@arnaud-ritti](https://github.com/arnaud-ritti)
- [@JeromeEngelnAdeliom](https://github.com/JeromeEngelnAdeliom)
- [@jeandaviddaviet](https://github.com/JeanDavidDaviet)

  
