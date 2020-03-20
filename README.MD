# Reverse print_r
This library provides six different handlers for reversing output of PHP's `print_r` function back to original variables.
If there's no handler available for a type it's returned as `string`.

## Assumptions
- all values should be type-casted, not returned as `string`
- empty string (`""`) is treated as `null` (see `NullHandler`)
- integers are treated as integers (no `boolean` support)
- multi-level `array` MUST be supported with type-casting
- `public`, `protected` and `private` properties of Objects MUST be supported with type-casting

## Known issues
- no support for Object Inheritance 

# Installation
Package is available via  [Composer](https://getcomposer.org/).

```
composer require simivar/reverse-print-r
```

# Usage
```php
<?php

$print_r_output = print_r([
    'string' => 'some text',
    'integer' => 1,
    'float' => 2.3,
    'subArray' => [
        'Hello World.',
    ],
], true);

$reverser = new \ReversePrintR\ReversePrintR($print_r_output);
echo $reverser->reverse()['float']; 
// outputs "2.3"
```

## Changing behavior of Handlers
All handlers are defined as `final`, but thanks to Dependency Injection it's easy to change the behavior of library 
and it's type-casting. Let's say you want to keep all the empty strings `""` as string, not `null`. All you have to do 
is create your own `HandlerRunner` without `NullHandler`.

```php
<?php

$print_r_output = print_r([
    'string' => '',
    'null' => null,
], true);

$handlerRunner = new \ReversePrintR\HandlerRunner(
    new \ReversePrintR\Handler\FloatHandler(),
    new \ReversePrintR\Handler\IntegerHandler(),
    new \ReversePrintR\Handler\ArrayHandler(),
    new \ReversePrintR\Handler\ObjectHandler()
);

$reverser = new \ReversePrintR\ReversePrintR($print_r_output, $handlerRunner);
var_dump($reverser->reverse()['null']); 
// outputs ""
```

## Own Handlers
The same way to removed `NullHandler` you can add your own handlers. All you have to do is make sure that it implements
`\ReversePrintR\Handler\HandlerInterface` and you are good to go.

# Versioning
Library is following [Semver](http://semver.org/). All minor and patch updates are backwards compatible.
 
# License
Please see the [license file](https://github.com/simivar/reverse-print-r/blob/master/LICENSE) for more information.