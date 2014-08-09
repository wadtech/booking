<?php
set_error_handler(function($code, $message, $file, $line){
    throw new \ErrorException($message, 0, $code, $file, $line);
});

require __DIR__ . "/../../vendor/autoload.php";

$testfiles = array();

// @todo lazy, but it works. Replace this with with a recursive thing based on core Directory implementation
// to allow nested tests
foreach (scandir(__DIR__) as $folder) {
    if ($folder[0] === '.') {
        continue;
    }
    if (is_dir(__DIR__ . DIRECTORY_SEPARATOR . $folder)) {
        foreach (scandir(__DIR__ . DIRECTORY_SEPARATOR . $folder) as $testFile) {
            if ($testFile[0] === '.') {
                continue;
            }
            if (strpos($testFile, '.php') === strlen($testFile) - 4) {
                $tests[] = __DIR__ . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $testFile;
            }
        }
    }
}

$status = 0;
$failures = "\n";
foreach ($tests as $filepath) {
    try {

        include $filepath;
        echo ".";

    } catch (MML\Booking\Exceptions\Booking $e) {
        $failures .= "TEST FAILED: $filepath\n\n";
        $failures .= $e->getMessage() . "\n\n";
        echo 'F';
        $status = 1;
    } catch (Doctrine\DBAL\DBALException $e) {
        $failures .= "TEST FAILED: $filepath\n\n";
        $failures .= $e->getMessage() . "\n\n";
        echo 'F';
        $status = 1;
    } catch (\ErrorException $e) {
        echo "ERROR WHEN RUNNING: $filepath\n\n";
        echo "Error was {$e->getMessage()} in file {$e->getFile()} on line {$e->getLine()}\nCall Stack:\n";
        echo $e->getTraceAsString();
        echo "\n\n";
        exit(1);
    }
}

echo $failures;
exit($status);
