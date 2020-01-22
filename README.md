# Annotate a Github Pull Request based on a Checkstyle XML-report

Turns checkstyle based XML-Reports into Github Pull Request Annotations via the Checks API.

![PHPUnit Action Matcher Logs Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-logs.png?raw=true)

![PHPUnit Action Matcher Context Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-context.png?raw=true)


# Example Usage

`phpstan analyse --no-progress --error-format=checkstyle | vendor/bin/cs2pr`

`psalm --output-format=checkstyle | vendor/bin/cs2pr`

`php-cs-fixer --format=checkstyle | vendor/bin/cs2pr`
