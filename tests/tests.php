<?php

/*
 * Turns checkstyle based XML-Reports into Github Pull Request Annotations via the Checks API. This script is meant for use within your GithubAction.
 *
 * (c) Markus Staab <markus.staab@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * https://github.com/staabm/annotate-pull-request-from-checkstyle
 */

function testXml($xmlPath, $expectedExit, $expectedOutput = null, $options = '')
{
    exec('cat '.$xmlPath .' | php '. __DIR__ .'/../cs2pr '.$options.' 2>&1', $output, $exit);
    $output = implode("\n", $output);

    if ($exit != $expectedExit) {
        var_dump($output);

        throw new Exception('Test with ' . $xmlPath . ' failed, expected exit-code ' . $expectedExit . ' got ' . $exit);
    } elseif ($expectedOutput && $expectedOutput != $output) {
        echo "EXPECTED:\n";
        var_dump($expectedOutput);
        echo "\n";

        echo "GOT:\n";
        var_dump($output);
        echo "\n";

        throw new Exception('Test with ' . $xmlPath . ' failed, output mismatch');
    } else {
        echo "success: $xmlPath\n\n";
    }
}


testXml(__DIR__.'/fail/empty.xml', 2, "Error: Expecting xml stream starting with a xml opening tag.\n");
testXml(__DIR__.'/fail/invalid.xml', 2, "Error: Start tag expected, '<' not found on line 1, column 1\n\n" .file_get_contents(__DIR__.'/fail/invalid.xml'));

testXml(__DIR__.'/fail/multiple-suites.xml', 2, "Error: Extra content at the end of the document on line 8, column 1\n\n" .file_get_contents(__DIR__.'/fail/multiple-suites.xml'));

testXml(__DIR__.'/errors/minimal.xml', 1, file_get_contents(__DIR__.'/errors/minimal.expect'));
testXml(__DIR__.'/errors/mixed.xml', 1, file_get_contents(__DIR__.'/errors/mixed.expect'));
testXml(__DIR__.'/errors/warning-only.xml', 1, file_get_contents(__DIR__.'/errors/warning-only.expect'));

testXml(__DIR__.'/errors/minimal.xml', 1, file_get_contents(__DIR__.'/errors/minimal.expect'), '--graceful-warnings');
testXml(__DIR__.'/errors/mixed.xml', 1, file_get_contents(__DIR__.'/errors/mixed.expect'), '--graceful-warnings');
testXml(__DIR__.'/errors/warning-only.xml', 0, file_get_contents(__DIR__.'/errors/warning-only.expect'), '--graceful-warnings');

testXml(__DIR__.'/errors/notices.xml', 1, file_get_contents(__DIR__.'/errors/notices.expect'));
testXml(__DIR__.'/errors/notices.xml', 1, file_get_contents(__DIR__.'/errors/notices-as-warnings.expect'), '--notices-as-warnings');

testXml(__DIR__.'/errors/prepend-filename.xml', 1, file_get_contents(__DIR__.'/errors/prepend-filename.expect'), '--prepend-filename');
testXml(__DIR__.'/errors/mixed-source-attributes.xml', 1, file_get_contents(__DIR__.'/errors/mixed-source-attributes.expect'), '--prepend-source');

testXml(__DIR__.'/errors/mixed.xml', 1, file_get_contents(__DIR__.'/errors/mixed-colors.expect'), '--colorize');

testXml(__DIR__.'/noerrors/only-header.xml', 0, file_get_contents(__DIR__.'/noerrors/only-header.expect'));
testXml(__DIR__.'/noerrors/only-header-php-cs-fixer.xml', 0, file_get_contents(__DIR__.'/noerrors/only-header-php-cs-fixer.expect'));

testXml(__DIR__.'/errors/mixed-case.xml', 1, file_get_contents(__DIR__.'/errors/mixed-case.expect'));

testXml(__DIR__.'/errors/mixed.xml', 1, file_get_contents(__DIR__.'/errors/errors-as-warnings.expect'), '--errors-as-warnings');

testXml(__DIR__.'/errors/without-line.xml', 1, file_get_contents(__DIR__.'/errors/without-line.expect'));
