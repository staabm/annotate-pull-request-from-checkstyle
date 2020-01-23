# Annotate a Github Pull Request based on a Checkstyle XML-report

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

Run one of the following commands within your GithubAction workflow:

Example GithubAction workflow
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
                extensions: intl
                coverage: none # disable xdebug, pcov
          - run: |
                composer install # install your apps dependencies
                composer require staabm/annotate-pull-request-from-checkstyle
                vendor/bin/phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr
```

## Process a checkstyle formated file

```bash
vendor/bin/cs2pr /path/to/checkstyle-report.xml
```

## Pipe the output of another commmand

Using [PHPStan](https://github.com/phpstan/phpstan)
```bash
phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr
```

Using [Psalm](https://github.com/vimeo/psalm)
```bash
psalm --output-format=checkstyle | vendor/bin/cs2pr`
```

Using [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
```bash
php-cs-fixer --format=checkstyle | vendor/bin/cs2pr
```

... works for __any__ command which produces a checkstyle-formatted report.

# Idea

This script is based on a suggestion of [Benjamin Eberlei](https://twitter.com/beberlei/status/1218970454557372416)

The Code is inspired by https://github.com/mheap/phpunit-github-actions-printer
