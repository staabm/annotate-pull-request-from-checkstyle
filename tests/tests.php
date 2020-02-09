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

function testXml($xmlPath, $expectedExit, $expectedOutput = null)
{
    exec('cat '.$xmlPath .' | php '. __DIR__ .'/../cs2pr 2>&1', $output, $exit);
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


testXml(__DIR__.'/fail/empty.xml', 2);
testXml(__DIR__.'/fail/invalid.xml', 2);

testXml(__DIR__.'/fail/multiple-suites.xml', 2);

testXml(__DIR__.'/errors/minimal.xml', 1, file_get_contents(__DIR__.'/errors/minimal.expect'));

testXml(__DIR__.'/noerrors/only-header.xml', 0, file_get_contents(__DIR__.'/noerrors/only-header.expect'));
