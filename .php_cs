<?php

declare(strict_types=1);

$header = <<<'EOF'
Turns checkstyle based XML-Reports into Github Pull Request Annotations via the Checks API. This script is meant for use within your GithubAction.

(c) Markus Staab <markus.staab@redaxo.org>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.

https://github.com/staabm/annotate-pull-request-from-checkstyle
EOF;

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->in(__DIR__)
    ->name('cs2pr');

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRules([
        '@PSR2' => true,
        'header_comment' => [
            'commentType' => 'comment',
            'header' => $header,
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ]
    ]);
