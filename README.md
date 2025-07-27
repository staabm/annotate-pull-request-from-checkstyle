# Annotate a Pull Request based on a Checkstyle XML-report

[![Continuous Integration](https://github.com/staabm/annotate-pull-request-from-checkstyle/workflows/Continuous%20Integration/badge.svg)](https://github.com/staabm/annotate-pull-request-from-checkstyle/actions)
[![Continuous Deployment](https://github.com/staabm/annotate-pull-request-from-checkstyle/workflows/Continuous%20Deployment/badge.svg)](https://github.com/staabm/annotate-pull-request-from-checkstyle/actions)

Turns [checkstyle based XML-Reports](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/v3.0.2/doc/schemas/fix/checkstyle.xsd) into GitHub Pull Request [Annotations via the Checks API](https://docs.github.com/en/free-pro-team@latest/rest/reference/checks).
This script is meant for use within your GitHub Action.

That means you no longer search thru your GitHub Action logfiles.
No need to interpret messages which are formatted differently with every tool.
Instead you can focus on your Pull Request, and you don't need to leave the Pull Request area.

![Logs Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-logs.png?raw=true)

![Context Example](https://github.com/mheap/phpunit-github-actions-printer/blob/master/phpunit-printer-context.png?raw=true)
_Images from https://github.com/mheap/phpunit-github-actions-printer_

[DEMO - See how Pull Request warnings/errors are rendered in action](https://github.com/staabm/gh-annotation-example/pull/1/files)

# Installation

Install the binary via composer
```bash
composer require staabm/annotate-pull-request-from-checkstyle --dev
```

## 💌 Give back some love

[Consider supporting the project](https://github.com/sponsors/staabm), so we can make this tool even better even faster for everyone.


# Example Usage

`cs2pr` can be used on a already existing checkstyle-report xml-file. Alternatively you might use it in the unix-pipe notation to chain it into your existing cli command.

Run one of the following commands within your GitHub Action workflow:

## Process a checkstyle formatted file

```bash
cs2pr /path/to/checkstyle-report.xml
```

### Available Options

- `--graceful-warnings`: Don't exit with error codes if there are only warnings
- `--colorize`: Colorize the output. Useful if the same lint script should be used locally on the command line and remote on GitHub Actions. With this option, errors and warnings are better distinguishable on the command line and the output is still compatible with GitHub Annotations
- `--notices-as-warnings` Converts notices to warnings. This can be useful because GitHub does not annotate notices. 
- `--prepend-filename` Prepend the filename to the output message
- `--prepend-source` When the checkstyle generating tool provides a `source` attribute, prepend the source to the output message. 


## Pipe the output of another commmand

... works for __any__ command which produces a checkstyle-formatted report.

Examples can bee seen below:

### Using [PHPStan](https://github.com/phpstan/phpstan)

```bash
phpstan analyse --error-format=checkstyle | cs2pr
```

_Phpstan 0.12.32 introduced native github actions support, therefore you might use this instead:_

```bash
phpstan analyse
```

### Using [Psalm](https://github.com/vimeo/psalm)

```bash
psalm --output-format=checkstyle | cs2pr
```

_Psalm even supports the required format natively, therefore you might use this instead:_

```bash
psalm --output-format=github
```

### Using [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

```bash
php-cs-fixer fix --dry-run --format=checkstyle | cs2pr
```

### Using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

```bash
phpcs --report=checkstyle -q /path/to/code | cs2pr
```


Note: the `-q` option means that no output will be shown in the action logs anymore.
To see the output both in the PR as well as in the action logs, use two steps, like so:

```yaml
      - name: Check PHP code style
        id: phpcs
        run: phpcs --report-full --report-checkstyle=./phpcs-report.xml

      - name: Show PHPCS results in PR
        if: ${{ always() && steps.phpcs.outcome == 'failure' }}
        run: cs2pr ./phpcs-report.xml
```

### Using [PHP Parallel Lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint/)

```bash
vendor/bin/parallel-lint . --exclude vendor --checkstyle | cs2pr
```

### Using [Laravel Pint](https://github.com/laravel/pint)

```yaml
- name: Show Pint results in PR
run: pint --test --format=checkstyle | cs2pr
```

Note: if you want to have both logs and annotations you need to run `pint` twice:

```yaml
- name: Check PHP code style
id: cs-check
run: pint --test

- name: Generate Annotations on CS errors
if: failure() && steps.cs-check.outcome != 'success'
run: pint --test --format=checkstyle | cs2pr
```

## phpunit support?

PHPUnit does not support checkstyle, therefore `cs2pr` will not work for you.

you might instead try
- a [phpunit problem matcher](https://github.com/shivammathur/setup-php#problem-matchers)
- a [phpunit-github-actions-printer](https://github.com/mheap/phpunit-github-actions-printer)

## Example GithubAction workflow


If you're using `shivammathur/setup-php` to setup PHP, `cs2pr` binary is shipped within:

```yml
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
                tools: cs2pr
          - run: |
                composer install # install your apps dependencies
                vendor/bin/phpstan analyse --error-format=checkstyle | cs2pr
```

If you use a custom PHP installation, then your project needs to require `staabm/annotate-pull-request-from-checkstyle`

```yml
# ...
jobs:
    phpstan-analysis:
      name: phpstan static code analysis
      runs-on: ubuntu-latest
      steps:
          - uses: actions/checkout@v2
          - name: Setup PHP
            run: # custom PHP installation 
          - run: |
                composer install # install your apps dependencies
                composer require staabm/annotate-pull-request-from-checkstyle # install cs2pr
                vendor/bin/phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr
```

## Using cs2pr as a GitHub Action

You can also use [`cs2pr` itself as a GitHub Action](https://github.com/staabm/annotate-pull-request-from-checkstyle-action). This is useful if you want to for instance use it for a project that does not use PHP or if you want to use it with a custom PHP installation.

See the example at the [cs2pr GitHub Action repository](https://github.com/staabm/annotate-pull-request-from-checkstyle-action#readme).



# Resources

[GithubAction Problem Matchers](https://github.com/actions/toolkit/blob/master/docs/problem-matchers.md)

# Idea

This script is based on a suggestion of [Benjamin Eberlei](https://twitter.com/beberlei/status/1218970454557372416)

The Code is inspired by https://github.com/mheap/phpunit-github-actions-printer
