<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ImportController extends Controller
{
    public function import()
    {
        return view('import');
    }

    public function parse(Request $request)
    {
        if($request->file('imported-file'))
        {
            $path = $request->file('imported-file')->getRealPath();
            echo "<a href='/'>To main page</a><br>";
            echo "<h3>Names</h3>";
            echo "<textarea style='width: 100%; height: 250px' rows=\"10\" cols='15'>";
            \Excel::load($path)->each(function ($row) {
                echo $row->full_name . ",";
            });
            echo "</textarea>";
            echo "<br><h3>Emails</h3>";
            echo "<textarea style='width: 100%; height: 250px' rows=\"10\" cols='15'>";
            \Excel::load($path)->each(function ($row) {
                echo $row->email . ",";
            });
            echo "</textarea>";
        }
    }
}
