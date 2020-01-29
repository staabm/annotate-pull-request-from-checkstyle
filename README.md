# Annotate a Pull Request based on a Checkstyle XML-report

[![Continuous Integration](https://github.com/staabm/annotate-pull-request-from-checkstyle/workflows/Continuous%20Integration/badge.svg)](https://github.com/staabm/annotate-pull-request-from-checkstyle/actions)

Turns [checkstyle based XML-Reports](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/checkstyle.xsd) into Github Pull Request [Annotations via the Checks API](https://developer.github.com/v3/checks/).
This script is meant for use within your GithubAction.

That means you no longer search thru your GithubAction logfiles.
No need to interpret messages which are formatted differently with every tool.
Instead you can focus on your Pull Request, and you don't need to leave the Pull Request area.

![Logs Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-logs.png?raw=true)

![Context Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-context.png?raw=true)
_Images from https://github.com/mheap/phpunit-github-actions-printer_

# Installation

Install the binary via composer
```bash
composer require staabm/annotate-pull-request-from-checkstyle
```

# Example Usage

`cs2pr` can be used on a already existing checkstyle-report xml-file. Alternatively you might use it in the unix-pipe notation to chain it into your existing cli command.

Run one of the following commands within your GithubAction workflow:

## Process a checkstyle formatted file

```bash
vendor/bin/cs2pr /path/to/checkstyle-report.xml
```

## Pipe the output of another commmand

... works for __any__ command which produces a checkstyle-formatted report.

Examples can bee seen below:

### Using [PHPStan](https://github.com/phpstan/phpstan)

```bash
phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr
```

### Using [Psalm](https://github.com/vimeo/psalm)

```bash
psalm --output-format=checkstyle | vendor/bin/cs2pr
```

_Psalm even supports the required format natively, therefore you might even use this instead:_

```bash
psalm --output-format=github
```

### Using [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

```bash
php-cs-fixer --format=checkstyle | vendor/bin/cs2pr
```

### Using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

```bash
phpcs --report=checkstyle /path/to/code | vendor/bin/cs2pr
```

## phpunit support?

PHPUnit does not support checkstyle, therefore cs2pr will not work for you.

you might instead try
- a [phpunit problem matcher](https://github.com/shivammathur/setup-php#problem-matchers)
- a [phpunit-github-actions-printer](https://github.com/mheap/phpunit-github-actions-printer)

## Example GithubAction workflow

```
# ...

jobs:
    phpstan-analysis:
      name: phpstan static code analysis
      runs-on: ubuntu-latest
      steps:
          - uses: actions/checkout@v2
          - name: Setup PHP
            uses: shivammathur/setup-php@v1
            with:
                php-version: 7.3
                coverage: none # disable xdebug, pcov
          - run: |
                composer install # install your apps dependencies
                composer require staabm/annotate-pull-request-from-checkstyle # install cs2pr
                vendor/bin/phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr
```

# Resources

[GithubAction Problem Matchers](https://github.com/actions/toolkit/blob/master/docs/problem-matchers.md)

# Idea

This script is based on a suggestion of [Benjamin Eberlei](https://twitter.com/beberlei/status/1218970454557372416)

The Code is inspired by https://github.com/mheap/phpunit-github-actions-printer
