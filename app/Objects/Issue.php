<?php


namespace App\Objects;


class Issue
{
    public string $severity;
    public string $problem;
    public string $description;
    public string $solution;
    public string $uid = '';

    public function __construct(array $issue, bool $isHeader = false)
    {
        $this->severity = $issue[0];
        $this->problem = $issue[1];
        $this->description = $issue[2];
        if ($isHeader) {
            $this->solution = $issue[3];
        } else {
            // Set a unique id string to get the devices when the report is generated
            $this->uid = uniqid('', true);
            // "Solutions" has some bogus line feeds.  Replace these.
            $this->solution = str_replace('\n', '', $issue[3]);
        }

    }
}
