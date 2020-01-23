# Annotate a Github Pull Request based on a Checkstyle XML-report

Turns [checkstyle based XML-Reports](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/checkstyle.xsd) into Github Pull Request [Annotations via the Checks API](https://developer.github.com/v3/checks/).
This script is meant for use within your GithubAction.

![PHPUnit Action Matcher Logs Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-logs.png?raw=true)

![PHPUnit Action Matcher Context Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-context.png?raw=true)

# Installation

Install the binary via composer
`composer require staabm/annotate-pull-request-from-checkstyle`

# Example Usage

## Process a checkstyle formated file

`vendor/bin/cs2pr /path/to/checkstyle-report.xml`

## Pipe the output of another commmand

`phpstan analyse --no-progress --error-format=checkstyle | vendor/bin/cs2pr`

`psalm --output-format=checkstyle | vendor/bin/cs2pr`

`php-cs-fixer --format=checkstyle | vendor/bin/cs2pr`

# Idea

This script is based on a suggestion of [Benjamin Eberlei](https://twitter.com/beberlei/status/1218970454557372416)

The Code is inspired by https://github.com/mheap/phpunit-github-actions-printer
