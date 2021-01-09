# Reify

Reify is a small package used to hydrate objects. This can be useful when implementing API clients or creating
abstraction layers in your application.

## Installation

*Insert warning about production usage here*

`composer require reify/reify:"dev-master"`

## Getting started

Let's say you have a dataset containing information about a person.

```json
{
	"name": "Leroy",
	"profession": "Developer",
	"colleagues": [
		{
			"name": "Peter",
			"profession": "Developer"
		},
		{
			"name": "Sandra",
			"profession": "Developer"
		}
	],
	"spouse": null
}
```

You want to use this data in your application, however just using `json_decode` would result in a plain object with no
typing and no way to add some functionality to them.

Reify can automatically map the data to any class you want. Let's create our `Person` class first.

```php

use Reify\Attributes\Construct;
use Reify\Attributes\Type;

class Person {
    public string $name;
    
    #[Construct]
    public Profession $profession;
    
    #[Type(Person::class)]
    public array $colleagues;
    
    public ?Person $spouse;
    
    public object $meta;
}


```

Neat right? Let's dissect what's happening here.

```php

class Person {
    /**
     * Using PHP 8 syntax we can define a type for each property
     * Reify supports all scalar values and custom types defined by you
     */
    public string $name;
    
    
    /**
     * If you have a single value that needs to be mapped to a type
     * You can use the Construct attribute. This will call the constructor with the value instead of mapping it.
     */
    #[Construct]
    public Profession $profession;
    
    /**
     * Unfortunately PHP does not have Generics.
     * We can however still define a type using the Type attribute.
 *   * Reify will try to map each value in the list to the given value
     */
    #[Type(Person::class)]
    public array $colleagues;

    /**
    * Not sure if the data is available? 
    * Reify will take your nullables in to account. 
    */    
    public ?Person $spouse;
    
    /**
    * You don't know what the data is going to look like? 
    * Reify supports mapping to plain objects aswell. 
    */
    public object $meta;
}

```

## Conclusion

Reify is a concept I've been tinkering with over the years. It's a simple concept that I use very often in the projects
I am working on.

This package is build with PHP 8 features for me to learn about them and fiddling with the new Attributes. Turns out I
love metaprogramming and creating developer tools with them. 

Building Reify i also had a chance to meet [PestPHP](https://pestphp.com/) and learn more about writing tests for my code.

## Alternatives
If you're looking for production worthy recommendations that I use myself please see below:

- https://github.com/cweiske/jsonmapper (JSON)
- https://github.com/schmittjoh/serializer (XML)