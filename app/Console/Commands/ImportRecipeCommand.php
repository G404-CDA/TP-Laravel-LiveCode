<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportRecipeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-recipes {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private RepositoryInterface $recipeRepository;

    public function __construct(RepositoryInterface $repository)
    {
        parent::__construct();
        $this->recipeRepository = $repository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Importing recipes...');
        $filename = $this->argument('file');
        // dump($filename);

        $file = Storage::get($filename);
        $data = json_decode($file, true);
        foreach ($data['recipes'] as $key => $recipeData) {
            $recipe = $this->recipeRepository->create($recipeData);
        }
        $this->info('Recipes imported successfully!');
    }
}
