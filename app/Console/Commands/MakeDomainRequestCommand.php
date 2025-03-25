<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem; // Szükséges a fájlműveletekhez
use Illuminate\Support\Str;          // Hasznos lehet string manipulációkhoz

class MakeDomainRequestCommand extends Command
{
    protected $signature = 'make:domain:request {domain} {name}';
    protected $description = 'Create a new Form Request class within a specific domain';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        $domain = Str::studly($this->argument('domain'));
        $name = Str::studly($this->argument('name'));

        if (!Str::endsWith($name, 'Request')) {
            $name .= 'Request';
        }

        $path = $this->getPath($domain, $name);
        $namespace = $this->getNamespace($domain);

        if ($this->files->exists($path)) {
            $this->error("Form Request [{$name}] already exists in domain [{$domain}]!");
            return Command::FAILURE;
        }

        $this->makeDirectory($path);

        $stub = $this->getStub();
        $stub = str_replace('{{ namespace }}', $namespace, $stub);
        $stub = str_replace('{{ class }}', $name, $stub);

        $this->files->put($path, $stub);

        $this->info("Form Request [{$name}] created successfully in domain [{$domain}].");

        return Command::SUCCESS; // Vagy 0 régebbi Laravel verziókban
    }

    protected function getPath(string $domain, string $name): string
    {
        return app_path("Domains/{$domain}/Http/Requests/{$name}.php");
    }

    protected function getNamespace(string $domain): string
    {
        return "App\\Domains\\{$domain}\\Http\\Requests";
    }

    protected function makeDirectory(string $path): void
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }

    protected function getStub(): string
    {
        return <<<STUB
<?php

namespace {{ namespace }};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class {{ class }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Add validation rules here
        ];
    }
}
STUB;
    }
}
