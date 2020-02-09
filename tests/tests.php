<?php

/**
 * @param string $xmlPath
 * @param int $expectedExit
 * @param string|null $expectedOutput
 */
function testXml($xmlPath, $expectedExit, $expectedOutput = null) {
    exec('cat '.$xmlPath .' | php '. __DIR__ .'/../cs2pr 2>&1', $output, $exit);
    $output = implode("\n", $output);

    if ($exit != $expectedExit) {
        var_dump($output);

        throw new Exception('Test with ' . $xmlPath . ' failed, expected exit-code ' . $expectedExit . ' got ' . $exit);
    }elseif ($expectedOutput && $expectedOutput != $output) {
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