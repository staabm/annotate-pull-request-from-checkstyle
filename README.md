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

Run one of the following commands within your GithubAction workflow

## Process a checkstyle formated file

```bash
vendor/bin/cs2pr /path/to/checkstyle-report.xml
```

## Pipe the output of another commmand

```bash
phpstan analyse --no-progress --error-format=checkstyle | vendor/bin/cs2pr
```

```bash
psalm --output-format=checkstyle | vendor/bin/cs2pr`
```

```bash
php-cs-fixer --format=checkstyle | vendor/bin/cs2pr
```

Works for __any__ command which produces a checkstyle-formatted report.

# Idea

This script is based on a suggestion of [Benjamin Eberlei](https://twitter.com/beberlei/status/1218970454557372416)

The Code is inspired by https://github.com/mheap/phpunit-github-actions-printer
