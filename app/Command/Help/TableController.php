<?php

namespace App\Command\Help;

use Minicli\Command\CommandController;
use Minicli\Output\Filter\ColorOutputFilter;
use Minicli\Output\Helper\TableHelper;

class TableController extends CommandController
{
    public function handle()
    {
        $this->getPrinter()->display('Testing Tables');

        $table = new TableHelper();
        $table->addHeader(['Header 1', 'Header 2', 'Header 3']);

        for($i = 1; $i <= 10; $i++) {
            $table->addRow([$i, rand(0, 10), "other string $i"]);
        }

        $this->getPrinter()->newline();
        $this->getPrinter()->rawOutput($table->getFormattedTable(new ColorOutputFilter()));
        $this->getPrinter()->newline();
    }
}