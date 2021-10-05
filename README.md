
![Adeliom](https://adeliom.com/public/uploads/2017/09/Adeliom_logo.png)
[![Quality gate](https://sonarcloud.io/api/project_badges/quality_gate?project=agence-adeliom_easy-fields-bundle)](https://sonarcloud.io/dashboard?id=agence-adeliom_easy-fields-bundle)

# Easy Fields Bundle

Provide some fields for Easyadmin.

## Installation

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
...
yield AssociationField::new('property', "label");
```

### EnumField

#### Usage

```php
use Adeliom\EasyFieldsBundle\Admin\Field\EnumField;
...
yield EnumField::new('property', "label")
    ->setEnum(YourEnumClass::class);
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
...
// NOTE : property can be a *ToMany or an array.
yield SortableCollectionField::new('property', "label")
    ->setEntryType(YourEntryFromType::class)
    ->allowAdd() // Allow to add new entry
    ->allowDelete() // Allow to remove entries
    ->allowDrag()  // Allow to drag entries
    ;
```

### PositionSortableField

TODO

## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@arnaud-ritti](https://github.com/arnaud-ritti)
- [@JeromeEngelnAdeliom](https://github.com/JeromeEngelnAdeliom)

  
