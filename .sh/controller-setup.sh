#!/bin/bash

# Függvény a controller létrehozásához
create_controller() {
    local domain="$1"
    local controller_name="$2"

    local folder="app/Http/Controllers"
    if [ "$domain" != "" ]; then
        folder="app/Domains/$domain/Http/Controllers"
    fi
    mkdir -p "$folder"

    local namespace="App\\Http\\Controllers"
    if [ "$domain" != "" ]; then
        namespace="App\\Domains\\$domain\\Http\\Controllers"
    fi

    if [ ! -f "$folder/$controller_name.php" ]; then
        cat <<EOF > "$folder/$controller_name.php"
<?php

namespace $namespace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class $controller_name extends Controller
{
    public function index()
    {

    }
}
EOF
    else
        echo "Controller '$controller_name' already exists, skipping creation."
    fi
}

create_resource() {
    local domain="$1"
    local resource_name="$2"

    local folder="app/Http/Resources"
    if [ "$domain" != "" ]; then
        folder="app/Domains/$domain/Http/Resources"
    fi
    mkdir -p "$folder" # A mappát akkor is létre kell hozni

    local namespace="App\\Http\\Resources"  # Alapértelmezett namespace
    if [ "$domain" != "" ]; then
        namespace="App\\Domains\\$domain\\Http\\Resources" # Domain-specifikus namespace
    fi
    cat <<EOF > "$folder/$resource_name.php"
<?php

namespace $namespace;

use Illuminate\Http\Resources\Json\JsonResource;

class $resource_name extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(\$request): array
    {
        return parent::toArray(\$request);
    }
}
EOF
}


# --- API Controllerek ---
create_controller "Car" "CarController" "API"
create_controller "Owner" "OwnerController" "API"
create_controller "ServiceLog" "ServiceLogController" "API"

# --- Web Controllerek (ha kellenek a jövőben) ---
# create_controller "Car" "CarController" "Web"
# create_controller "Owner" "OwnerController" "Web"
# create_controller "ServiceLog" "ServiceLogController" "Web"

# --- API Resources ---
create_resource "Car" "CarResource"
create_resource "Owner" "OwnerResource"
create_resource "ServiceLog" "ServiceLogResource"
#create_resource "" "ClientResource"

# --- Route-ok beírása (biztonságosabb módon) ---

# Függvény az use utasítások hozzáadásához (API)
add_api_use_statement() {
  local controller="$1"
  local domain="$2"

  if [ "$domain" != "" ]; then
    local full_controller="App\\\\Domains\\\\$domain\\\\Http\\\\Controllers\\\\$controller"
  else
    local full_controller="App\\\\Http\\\\Controllers\\\\$controller"
  fi

    php -r "\$routes = file_get_contents('routes/api.php');
            if (strpos(\$routes, 'use $full_controller;') === false) {
                \$routes = preg_replace('/<\?php/', \"<?php\n\nuse $full_controller;\", \$routes, 1);
                file_put_contents('routes/api.php', \$routes);
            }"
}

# Függvény az use utasítások hozzáadásához (WEB)
add_web_use_statement() {
    local controller="$1"
    local domain="$2"

    if [ "$domain" != "" ]; then
      local full_controller="App\\\\Domains\\\\$domain\\\\Http\\\\Controllers\\\\$controller"
    else
       local full_controller="App\\\\Http\\\\Controllers\\\\$controller"
    fi

    php -r "\$routes = file_get_contents('routes/web.php');
              if (strpos(\$routes, 'use $full_controller;') === false) {
                 \$routes = preg_replace('/<\?php/', \"<?php\n\nuse $full_controller;\", \$routes, 1);

                  file_put_contents('routes/web.php', \$routes);
              }"
}

# Függvény az apiResource útvonal hozzáadásához (API)
add_api_route() {
    local resource="$1"
    local controller="$2"
    local domain="$3"

     if [ "$domain" != "" ]; then
      local full_controller="App\\\\Domains\\\\$domain\\\\Http\\\\Controllers\\\\$controller"  #Nincs API mappa!
    else
      local full_controller="App\\\\Http\\\\Controllers\\\\$controller" #Nincs API mappa!
    fi

    php -r "\$routes = file_get_contents('routes/api.php');
            if (strpos(\$routes, 'Route::apiResource(\\'$resource\\',') === false) {
                \$routes .= \"\nRoute::apiResource('$resource', $controller::class);\";
                file_put_contents('routes/api.php', \$routes);
            }"
}

#Függvény a resource útvonal hozzáadásához (WEB)
add_web_route() {
    local resource="$1"
    local controller="$2"
    local domain="$3"

   if [ "$domain" != "" ]; then
      local full_controller="App\\\\Domains\\\\$domain\\\\Http\\\\Controllers\\\\$controller"  #Nincs API mappa!
    else
      local full_controller="App\\\\Http\\\\Controllers\\\\$controller" #Nincs API mappa!
    fi

    php -r "\$routes = file_get_contents('routes/web.php');
            if (strpos(\$routes, 'Route::resource(\\'$resource\\',') === false) {
                \$routes .= \"\nRoute::resource('$resource', $controller::class);\";
                file_put_contents('routes/web.php', \$routes);
            }"
}

# --- API útvonalak ---
add_api_use_statement "CarController" "Car"
add_api_use_statement "OwnerController" "Owner"
add_api_use_statement "ServiceLogController" "ServiceLog"

add_api_route "cars" "CarController" "Car"
add_api_route "owners" "OwnerController" "Owner"
add_api_route "servicelogs" "ServiceLogController" "ServiceLog"

# --- Web útvonalak (ha kellenek) ---
#add_web_use_statement "CarController" "Car" #Ha Web controller is van.
#add_web_use_statement "OwnerController" "Owner"
#add_web_use_statement "ServiceLogController" "ServiceLog"
#add_web_route "cars" "CarController" "Car"
#add_web_route "owners" "OwnerController" "Owner"
#add_web_route "servicelogs" "ServiceLogController" "ServiceLog"

echo "API Controllers, Resources, and basic routes created/appended. Please review and complete the route definitions in routes/api.php and routes/web.php (if applicable)."
