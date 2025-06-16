# PHP GraphQL Helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/worksome/graphql-helpers.svg?style=flat-square)](https://packagist.org/packages/worksome/graphql-helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/worksome/graphql-helpers/tests.yml?branch=main&style=flat-square&label=Tests)](https://github.com/worksome/graphql-helpers/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Static Analysis Action Status](https://img.shields.io/github/actions/workflow/status/worksome/graphql-helpers/static.yml?branch=main&style=flat-square&label=Static%20Analysis)](https://github.com/worksome/graphql-helpers/actions?query=workflow%3A"Static%20Analysis"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/worksome/graphql-helpers.svg?style=flat-square)](https://packagist.org/packages/worksome/graphql-helpers)

A collection of GraphQL helpers for [GraphQL PHP](https://github.com/webonyx/graphql-php).

## Installation

You can install the package via composer:

```bash
composer require worksome/graphql-helpers
```

## Usage

### Enum Type Registration

The [`PhpEnumType`](src/Definition/PhpEnumType.php) class can be used to override the `GraphQL\Type\Definition\EnumType` class with automatic case conversion.

```php
enum MyEnum
{
    case CaseOne;
}

new \Worksome\GraphQLHelpers\Definition\PhpEnumType(MyEnum::class);
```

### Enum Concerns

#### `GraphQLConvertable`

The [`GraphQLConvertable` concern](src/Definition/Concerns/GraphQLConvertable.php) is used to easily convert an enum instance to its GraphQL value within your codebase.

```php
enum MyEnum
{
    use \Worksome\GraphQLHelpers\Definition\Concerns\GraphQLConvertable;
    
    case CaseOne;
}

MyEnum::CaseOne->toGraphQLValue(); // CASE_ONE
```

#### `GraphQLDescribable`

The [`GraphQLDescribable` concern](src/Definition/Concerns/GraphQLDescribable.php) is used to easily retrieve the description of an enum instance using the value from a `GraphQL\Type\Definition\Description` attribute.

```php
enum MyEnum
{
    use \Worksome\GraphQLHelpers\Definition\Concerns\GraphQLDescribable;
    
    #[\GraphQL\Type\Definition\Description('The First Case!')]
    case CaseOne;
}

MyEnum::CaseOne->description(); // The First Case!
```

### Testing Enums

The [`HandlesEnumConversions` concern](src/Testing/Concerns/HandlesEnumConversions.php) adds support for quickly converting an enum to its GraphQL value.

```php
enum MyEnum
{
    case CaseOne;
}

// In Pest
uses(\Worksome\GraphQLHelpers\Testing\Concerns\HandlesEnumConversions::class);

$this->enumToGraphQL(MyEnum::CaseOne); // CASE_ONE
```

## Testing

```bash
composer test
```

## Changelog

Please see [GitHub Releases](https://github.com/worksome/graphql-helpers/releases) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Owen Voke](https://github.com/worksome)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
