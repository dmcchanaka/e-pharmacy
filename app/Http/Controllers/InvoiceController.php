<?php

namespace App\Http\Controllers;

use Exception;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class InvoiceController extends Controller{

    public function test_print(){

        try {
            // Enter the share name for your USB printer here
            // $connector = null;
            $connector = new WindowsPrintConnector("EPSON TM-T81III Receipt"); //shared printer name
        
            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer -> text("Hello World!\n");
            $printer -> cut();
            
            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
}