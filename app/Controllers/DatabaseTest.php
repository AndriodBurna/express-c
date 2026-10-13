<?php

namespace App\Controllers;

use App\Models\ProjectModel;

class DatabaseTest extends BaseController
{
    public function index()
    {
        $model = new ProjectModel();
        $projects = $model->findAll();

        echo '<pre>';
        print_r($projects);
        echo '</pre>';
    }
}