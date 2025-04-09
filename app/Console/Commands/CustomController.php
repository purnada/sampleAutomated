<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CustomController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:cc {model} {--m} {controllerDir?} {viewDir?} {--language}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CustomController';

    protected $modelDir = 'Models';

    protected $controllerDir = 'Admin';

    protected $viewDir = 'admin';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->argument('controllerDir')) {
            $this->controllerDir = ucfirst($this->argument('controllerDir'));
        }

        if ($this->argument('viewDir')) {
            $this->viewDir = strtolower($this->argument('viewDir'));
        }

        // Get the model and folder name with first character Capital
        $this->model = ucfirst($this->argument('model'));
        // $this->folder = ucfirst($this->argument('folder'));

        // Create Model under default instalation folder
        if ($this->confirm('Do you wish to create'.' '.$this->model.' '.'Model?')) {

            if ($this->option('language')) {
                $this->createLanguageModel();
            } else {
                $this->createModel();
            }
        }
        if ($this->confirm('Do you wish to create'.' '.$this->model.' '.'Controler?')) {
            $controllerDirectory = app_path('Http/Controllers/'.$this->controllerDir);
            $this->createDirectory($controllerDirectory);

            // Controller File
            $sourceControllerFile = app_path('Console/Stubs/CustomController.stub');
            if ($this->option('language')) {
                $sourceControllerFile = app_path('Console/Stubs/Language/CustomController.stub');
            }
            $destinationControllerFile = app_path("Http/Controllers/{$this->controllerDir}/{$this->model}Controller.php");
            $firstCharacter = 'use Illuminate\Support\Facades\Route;';

            $table = Str::snake(\Str::plural($this->argument('model')));

            // Code you want to insert
            $firstCode = "use App\Http\Controllers\\".$this->controllerDir.'\\'.$this->model."Controller; \n";

            $this->appendRoute($table);

            $this->appendFunction();

            $menuCharacter = '</ul><div class="slide-right" id="slide-right">';
            $mname = Str::snake(class_basename($this->argument('model')));

            // Code you want to insert
            $menuCode = "\n  @can('view {$mname}')<li class=\"slide @if(request()->is('{$table}')) active @endif\">\n";
            $menuCode .= " <a href=\"{{ route('admin.$table.index') }}\" class=\"side-menu__item @if(request()->is('admin/{$table}*')) active @endif\"> \n";
            $menuCode .= ' <i class="ti ti-settings icon"></i><span class="side-menu__label">'.ucfirst(str_replace('_', ' ', $table))."</span> \n";
            $menuCode .= "</a> \n </li>@endcan\n";

            $menuFile = base_path('resources/views/admin/includes/sidebar.blade.php');
            $file = base_path('routes/admin.php');

            $this->writeCode($firstCharacter, $firstCode, $file);

            $this->writeCode($menuCharacter, $menuCode, $menuFile);

            // insert permissions

            $this->givePermission($mname);

            $msg = "Created {$this->model}Controller";
            $this->createFile($sourceControllerFile, $destinationControllerFile, $msg);
        }

        if ($this->confirm('Do you wish to create'.' '.$this->model.' '.'Form Request?')) {
            if ($this->option('language')) {
                $this->createLanguageRequest();
            } else {
                $this->createRequest();
            }
        }
        if ($this->confirm('Do you wish to create'.' '.$this->model.' '.'migration?')) {

            if ($this->option('language')) {
                $this->createLanguageMigration();
            } else {
                $this->createMigration();
            }
        }

        if ($this->confirm('Do you wish to create the views')) {

            if ($this->option('language')) {
                $this->createLanguageViews();
            } else {
                $this->createViews();
            }
        }

    }

    protected function appendRoute($table)
    {
        $secondCode = "Route::resource('{$table}',{$this->model}Controller::class);";
        $file = base_path('routes/admin.php');

        $fileHandle = fopen($file, 'a');

        // Write the string to the file
        fwrite($fileHandle, $secondCode);

        // Close the file handle
        fclose($fileHandle);
    }

    protected function appendFunction()
    {
        $fname = Str::camel($this->model).'Detail';
        $code = "public function {$fname}() { return \$this->hasOne({$this->model}Detail::class); } \n";
        $file = app_path('Models/Language.php');
        $character = 'public static function getStatus()';
        $this->writeCode($character, $code, $file);
    }

    protected function givePermission($mname)
    {
        $permissions = [
            'view '.$mname,
            'create '.$mname,
            'edit '.$mname,
            'delete '.$mname,
        ];

        $guardName = 'web';
        $roleName = 'Super Admin';
        $role = Role::where('name', $roleName)->first();

        foreach ($permissions as $permissionName) {
            Permission::updateOrCreate(['name' => $permissionName, 'guard_name' => $guardName]);
            // \Artisan::call('cache:forget spatie.permission.cache');
            // \Artisan::call('cache:clear');
            // if ($role) {
            //     $role->givePermissionTo($permissionName);
            // }
        }

    }

    protected function writeCode($character, $code, $file)
    {
        $content = file_get_contents($file);
        $position = strpos($content, $character);

        if ($position !== false) {
            // $before = substr($content, 0, $position + strlen($character));
            // $after = substr($content, $position + strlen($character));
            $before = substr($content, 0, $position);
            $after = substr($content, $position);

            // Concatenate the code with the extracted substrings
            $content = $before."\n".$code.$after;

            // Write the modified content back to the PHP file
            file_put_contents($file, $content);
        }
    }

    protected function createLanguageModel()
    {
        $ModelDir = app_path('Models');
        $this->createDirectory($ModelDir);
        $sourceModelFile = app_path('Console/Stubs/Language/CustomModel.stub');
        $sourceDetailModelFile = app_path('Console/Stubs/Language/CustomDetailModel.stub');
        $destinationModelFile = app_path("Models/{$this->model}.php");
        $destinationDetailModelFile = app_path("Models/{$this->model}Detail.php");
        $msg = "Created {$this->model}Model";
        $detailMsg = "Created {$this->model}DetailModel";
        $this->createFile($sourceModelFile, $destinationModelFile, $msg);
        $this->createFile($sourceDetailModelFile, $destinationDetailModelFile, $detailMsg);
    }

    protected function createModel()
    {
        $ModelDir = app_path('Models');
        $this->createDirectory($ModelDir);
        $sourceModelFile = app_path('Console/Stubs/CustomModel.stub');
        $destinationModelFile = app_path("Models/{$this->model}.php");
        $msg = "Created {$this->model}Model";
        $this->createFile($sourceModelFile, $destinationModelFile, $msg);
    }

    protected function createLanguageRequest()
    {
        $requestDir = app_path('Http/Requests/Admin');
        $this->createDirectory($requestDir);
        $sourceRequestFile = app_path('Console/Stubs/Language/CustomRequest.stub');
        $destinationRequestFile = app_path("Http/Requests/Admin/{$this->model}Request.php");
        $msg = "Created {$this->model}Request";
        $this->createFile($sourceRequestFile, $destinationRequestFile, $msg);
    }

    protected function createRequest()
    {
        $requestDir = app_path('Http/Requests/Admin');
        $this->createDirectory($requestDir);
        $sourceRequestFile = app_path('Console/Stubs/CustomRequest.stub');
        $destinationRequestFile = app_path("Http/Requests/Admin/{$this->model}Request.php");
        $msg = "Created {$this->model}Request";
        $this->createFile($sourceRequestFile, $destinationRequestFile, $msg);
    }

    protected function createLanguageMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('model'))));
        $detailTable = Str::snake(class_basename($this->argument('model')));

        $fileName = date('Y_m_d_His')."_1_create_{$table}_table";
        $detailFileName = date('Y_m_d_His')."_2_create_{$detailTable}_details_table";

        $sourceMigrationFile = app_path('Console/Stubs/Language/CustomMigration.stub');
        $sourceDetailMigrationFile = app_path('Console/Stubs/Language/CustomDetailMigration.stub');
        $destinationMigrationFile = database_path("migrations/{$fileName}.php");
        $destinationDetailMigrationFile = database_path("migrations/{$detailFileName}.php");
        $msg = "Created {$fileName}";
        $detailMsg = "Created {$detailFileName}";
        $this->createFile($sourceMigrationFile, $destinationMigrationFile, $msg);
        $this->createFile($sourceDetailMigrationFile, $destinationDetailMigrationFile, $detailMsg);

        // \Artisan::call('migrate --force');
    }

    protected function createFile($dummySource, $destinationPath, $message, $folder = null)
    {

        $dummy = Str::snake(class_basename($this->argument('model')));
        $dummies = \Str::plural($dummy);
        $this->model = ucfirst($this->argument('model'));
        $pluralModel = \Str::plural($this->model);
        $dummyFile = file_get_contents($dummySource);
        $toBeReplacedContent = str_replace(
            ['Dummy', 'Dummies', 'camel', 'dummies', 'dummy', 'folderDir', '{{$viewDir}}'],
            [$this->model, $pluralModel, Str::camel($this->model), $dummies, $dummy, $this->controllerDir, $this->viewDir],
            $dummyFile
        );
        file_put_contents($dummySource, $toBeReplacedContent);
        copy($dummySource, $destinationPath);
        file_put_contents($dummySource, $dummyFile);

        return $this->info($message);
    }

    /**
     *  Create the directory for the model
     *
     * @return void
     */
    protected function createDirectory($dir)
    {

        if (! is_dir($dir)) {

            mkdir($dir, 0755, true);
        }
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('model'))));

        $fileName = date('Y_m_d_His')."_1_create_{$table}_table";

        $sourceMigrationFile = app_path('Console/Stubs/CustomMigration.stub');
        $destinationMigrationFile = database_path("migrations/{$fileName}.php");
        $msg = "Created {$fileName}";
        $this->createFile($sourceMigrationFile, $destinationMigrationFile, $msg);
    }

    protected function createDetailMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('model').'Detail')));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    protected function createViews()
    {
        $folder = Str::snake(\Str::plural($this->model));

        $pluralModel = \Str::plural(ucfirst($this->argument('model')));

        $bladeFolder = strtolower($folder);

        $views = [
            'pages/index.stub' => "{$this->viewDir}/pages/{$bladeFolder}/index.blade.php",
            'pages/edit.stub' => "{$this->viewDir}/pages/{$bladeFolder}/edit.blade.php",
            'pages/create.stub' => "{$this->viewDir}/pages/{$bladeFolder}/create.blade.php",
        ];
        foreach ($views as $key => $value) {
            $this->createDirectory($this->getViewPath($this->viewDir).'/pages');
            if (file_exists($view = $this->getViewPath($value))) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            $sourceControllerFile = app_path("Console/Stubs/{$key}");

            $destinationControllerFile = $this->getViewPath("$value");
            $this->createDirectory($this->getViewPath("{$this->viewDir}/pages/{$bladeFolder}"));
            $msg = str_replace('.stub', '', explode('/', $key)[1]).' View Created';
            $this->createFile($sourceControllerFile, $this->getViewPath($value), $msg);
        }
    }

    protected function createLanguageViews()
    {
        $folder = Str::snake(\Str::plural($this->model));

        $bladeFolder = strtolower($folder);

        $views = [
            'Language/pages/index.stub' => "{$this->viewDir}/pages/{$bladeFolder}/index.blade.php",
            'Language/pages/edit.stub' => "{$this->viewDir}/pages/{$bladeFolder}/edit.blade.php",
            'Language/pages/create.stub' => "{$this->viewDir}/pages/{$bladeFolder}/create.blade.php",
        ];
        foreach ($views as $key => $value) {
            $this->createDirectory($this->getViewPath($this->viewDir).'/pages');
            if (file_exists($view = $this->getViewPath($value))) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            $sourceControllerFile = app_path("Console/Stubs/{$key}");

            $destinationControllerFile = $this->getViewPath("$value");
            $this->createDirectory($this->getViewPath("{$this->viewDir}/pages/{$bladeFolder}"));
            $msg = str_replace('.stub', '', explode('/', $key)[1]).' View Created';
            $this->createFile($sourceControllerFile, $this->getViewPath($value), $msg);
        }
    }

    protected function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }

    protected function writeCodeBelow($character, $code, $file)
    {
        $content = file_get_contents($file);
        $position = strpos($content, $character);

        if ($position !== false) {
            $before = substr($content, 0, $position + strlen($character));
            $after = substr($content, $position + strlen($character));

            // Concatenate the code with the extracted substrings
            $content = $before."\n".$code.$after;

            // Write the modified content back to the PHP file
            file_put_contents($file, $content);
        }
    }
}
