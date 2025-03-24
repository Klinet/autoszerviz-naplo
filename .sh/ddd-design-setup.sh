#!/bin/bash

# nem használom a Service elnevezést mert az OOP réteg is, tehát: app/Domains/Reparation
# Ez a szkript által generált alapstruktúra is megfelel a PSR-1 és PSR-4 szabványoknak.
# A szkript nem foglalkozik közvetlenül a naplózással (PSR-3), a HTTP üzenetekkel
# (PSR-7) vagy a middleware-kkel (PSR-15), mert ezek a Laravel keretrendszer részei,
# és a tényleges implementáció során kell velük foglalkozni.

create_class() {
    local folder="$1"
    local class_name="$2"
    local namespace="$3"
    local method_name="$4"
    local return_type="$5"

    mkdir -p "$folder"
    touch "$folder/.gitkeep"

    cat <<EOF > "$folder/$class_name.php"
<?php

namespace $namespace;

class $class_name
{

    public function $method_name()
    {
        $return_type
    }
}
EOF
}

create_interface() {
  local folder="$1"
  local class_name="$2"
  local namespace="$3"
  local method_name="$4"
  local return_type="$5"

  mkdir -p "$folder"
  touch "$folder/.gitkeep"

  cat <<EOF > "$folder/$class_name.php"
<?php

namespace $namespace;

interface $class_name
{
    public function $method_name()$return_type;
}
EOF
}

mkdir -p app/Core/Exceptions
touch app/Core/Exceptions/.gitkeep
mkdir -p app/Core/Providers
touch app/Core/Providers/.gitkeep

mkdir -p database/seeders/data

for domain in Car Owner ServiceLog; do
    mkdir -p "app/Domains/${domain}/Models"
    touch "app/Domains/${domain}/Models/.gitkeep"
    mkdir -p "app/Domains/${domain}/Repositories/Interfaces"
    touch "app/Domains/${domain}/Repositories/Interfaces/.gitkeep"
    mkdir -p "app/Domains/${domain}/Repositories"
    touch "app/Domains/${domain}/Repositories/.gitkeep"
    mkdir -p "app/Domains/${domain}/Services"
    touch "app/Domains/${domain}/Services/.gitkeep"
    mkdir -p "app/Domains/${domain}/Events"
    touch "app/Domains/${domain}/Events/.gitkeep"
    mkdir -p "app/Domains/${domain}/Listeners"
    touch "app/Domains/${domain}/Listeners/.gitkeep"
    mkdir -p "app/Domains/${domain}/Exceptions"
    touch "app/Domains/${domain}/Exceptions/.gitkeep"
    mkdir -p "app/Domains/${domain}/Actions"
    touch "app/Domains/${domain}/Actions/.gitkeep"

    create_class "app/Domains/${domain}/Models" "${domain}" "App\Domains\\${domain}\Models" "getDetails" "return '${domain} details';"
    create_class "app/Domains/${domain}/Repositories" "${domain}Repository" "App\Domains\\${domain}\Repositories" "getAll" "return collect([]);"
    create_interface "app/Domains/${domain}/Repositories/Interfaces" "${domain}RepositoryInterface" "App\Domains\\${domain}\Repositories\Interfaces" "getAll" ": \Illuminate\Database\Eloquent\Collection"
    create_interface "app/Domains/${domain}/Repositories/Interfaces" "${domain}RepositoryInterface" "App\Domains\\${domain}\Repositories\Interfaces" "findById" ": ?\App\Domains\\${domain}\Models\\${domain}"
    create_interface "app/Domains/${domain}/Repositories/Interfaces" "${domain}RepositoryInterface" "App\Domains\\${domain}\Repositories\Interfaces" "create" ": \App\Domains\\${domain}\Models\\${domain}"
    create_interface "app/Domains/${domain}/Repositories/Interfaces" "${domain}RepositoryInterface" "App\Domains\\${domain}\Repositories\Interfaces" "update" ": \App\Domains\\${domain}\Models\\${domain}"
    create_interface "app/Domains/${domain}/Repositories/Interfaces" "${domain}RepositoryInterface" "App\Domains\\${domain}\Repositories\Interfaces" "delete" ": bool"

    create_class "app/Domains/${domain}/Services" "${domain}Service" "App\Domains\\${domain}\Services" "getList" ""
    create_class "app/Domains/${domain}/Events" "${domain}Created" "App\Domains\\${domain}\Events" "handle" ""
    create_class "app/Domains/${domain}/Listeners" "Send${domain}CreatedNotification" "App\Domains\\${domain}\Listeners" "handle" ""
    create_class "app/Domains/${domain}/Exceptions" "${domain}NotFoundException" "App\Domains\\${domain}\Exceptions" "__construct" "parent::__construct('${domain} not found');"

    mkdir -p "tests/Feature/Domains/${domain}"
    touch "tests/Feature/Domains/${domain}/.gitkeep"
    mkdir -p "tests/Unit/Domains/${domain}"
    touch "tests/Unit/Domains/${domain}/.gitkeep"

    if [ "$domain" = "Car" ]; then
      cat <<EOF > "tests/Feature/Domains/${domain}/${domain}Test.php"
<?php

namespace Tests\Feature\Domains\Car;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        \$response = \$this->get('/');
        \$response->assertStatus(200);
    }
}
EOF
    fi

done

mkdir -p app/Interfaces/Http/Controllers
touch app/Interfaces/Http/Controllers/.gitkeep
mkdir -p app/Interfaces/Http/Requests
touch app/Interfaces/Http/Requests/.gitkeep
mkdir -p app/Interfaces/Http/Resources
touch app/Interfaces/Http/Resources/.gitkeep

if [ ! -d "tests/Feature" ]; then
  mkdir -p tests/Feature
  touch tests/Feature/.gitkeep
fi

if [ ! -d "tests/Unit" ]; then
  mkdir -p tests/Unit
  touch tests/Unit/.gitkeep
fi

touch database/database.sqlite

echo "A projekt struktúra és az alap osztályok sikeresen létrehozva."
