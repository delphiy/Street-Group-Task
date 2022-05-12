<?php
/**
 * Loops over each element in the csv, extract names
 * Returns array of peoples.
 */
namespace App\Utils;

use App\Models\Person;

/**
 * Class CSVUtils
 * @package App\Utils
 */

class CSVUtils
{
    /**
     * PATH for csv file to import
     */
    const CSV_PATH = 'csv/peoples.csv';

    /**
     * Split pattern
     */
    const SPLIT_PATTERN = '/&|and/i';

    /**
     * Title pattern
     */
    const TITLE_PATTERN = '/^(Mr|Mrs|Miss|Mister|Dr|Mrs)/';

    /**
     * Names pattern
     */
    const INITIAL_PATTERN = '/([A-Z]+[.])/';

    /**
     * Names pattern
     */
    const FIRST_NAME_PATTERN = '/\s([a-zA-Z]{3,30})+/';

    /**
     * First and last name pattern
     */
    const FIRST_LAST_NAME_PATTERN = '/[a-zA-Z]+\s+[a-zA-Z]+[-]?[a-zA-z]+?$/';

    /**
     * @return array of person
     */
    public function loadPeoplesFromCSV(): array
    {
        $file = fopen(self::CSV_PATH, 'r');
        $flag = true;

        $peoples = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            //ignore first row
            if ($flag) {
                $flag = false;
                continue;
            }

            $rows = preg_split(self::SPLIT_PATTERN, $line[0]);
            //delete later
//            $rows = ["Mr John Smith"];
            $title = '';
            $initial = '';
            $firstName = '';
            $lastName = '';

            foreach ($rows as $row) {
                if (preg_match(self::TITLE_PATTERN, $row, $matches)) {
                    $title = $matches[0];
                }

                if (preg_match(self::INITIAL_PATTERN, $row, $matches)) {
                    $initial = $matches[0];
                }

                //Try to get first and last name, otherwise try to find first name only
                if (preg_match(self::FIRST_LAST_NAME_PATTERN, $row, $matches)) {
                    $fullName = explode(" ", $matches[0]);
                    $firstName = $fullName[0];
                    $lastName = $fullName[1];
                } else if (preg_match(self::FIRST_NAME_PATTERN, $row, $matches)) {
                    $firstName = $matches[0];
                }

                $person = new Person();
                $person->initial = $initial;
                $person->title = $title;
                $person->first_name = $firstName;
                $person->last_name = $lastName;

                $peoples[] = $person;
            }
        }

        fclose($file);

        return $peoples;
    }
}