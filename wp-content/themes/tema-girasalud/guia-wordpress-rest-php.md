# Guía: WordPress + REST API — Arquitectura y Buenas Prácticas PHP

> **Contexto de tu proyecto**: tu tema *Girasalud/MindBridge* ya tiene una intuición correcta en el README (la "Proyección a futuro"). Esta guía te explica *por qué* esa arquitectura funciona y *cómo* implementar cada capa con PHP moderno.

---

## Parte 1 — Estrategias de Arquitectura

### 1.1 El problema actual

Tu `functions.php` hace demasiadas cosas: registra scripts, incluye CPTs, incluye ACF, define hooks de header/footer, y contiene la lógica de loop. Esto se llama **God File**: un archivo que sabe demasiado. Cuando el proyecto crece, cada cambio ahí puede romper algo inesperado.

La raíz del problema es que tu código mezcla tres responsabilidades distintas:

```
functions.php  ←  configuración + lógica + presentación mezcladas
```

La meta es separar esas responsabilidades en capas con responsabilidades únicas.

---

### 1.2 La arquitectura recomendada: Layered Architecture

Tu README ya apunta a esto. Aquí está la explicación de cada capa:

```
theme/
│
├── functions.php          ← Solo bootstrap: carga el Application
├── style.css
├── index.php              ← Solo llama a wp_head() y wp_footer()
│
└── app/
    ├── Bootstrap/         ← Punto de arranque, registra todo
    ├── Container/         ← Inyección de dependencias
    ├── Contracts/         ← Interfaces (contratos)
    ├── Domain/            ← Entidades, DTOs, ValueObjects (lógica pura)
    ├── Api/               ← Controllers, Routes, Middleware REST
    ├── Services/          ← Lógica de negocio
    ├── Infrastructure/    ← WordPress, ACF, CPT, Base de datos
    ├── Responses/         ← Formatos de respuesta JSON
    └── Shared/            ← Config, helpers globales
```

**Regla de oro**: las capas internas no conocen a las externas.

```
Domain  ←  Services  ←  Api (Controllers)  ←  Infrastructure
  ↑            ↑               ↑
No sabe     No sabe       No sabe de
de WP       de HTTP       la base de datos
```

---

### 1.3 El flujo de una request REST

Entender este flujo es clave antes de escribir una sola línea:

```
HTTP GET /wp-json/girasalud/v1/servicios
         │
         ▼
   [WordPress REST Router]
         │
         ▼
   Api/Routes/ServiceRoutes.php  ← Registra la ruta
         │
         ▼
   Api/Controllers/ServiceController.php  ← Recibe la Request
         │
         ▼
   Services/ServiceService.php  ← Lógica de negocio
         │
         ▼
   Infrastructure/WordPress/ServiceRepository.php  ← WP_Query + ACF
         │
         ▼
   Domain/Entities/Service.php  ← Modelo puro de datos
         │
         ▼
   Responses/ServiceResponse.php  ← Formatea el JSON final
         │
         ▼
   JSON  →  tu frontend (React, Vue, JS vanilla)
```

Cada clase tiene **una sola razón para cambiar**. Si cambia el diseño del JSON, solo tocas `ServiceResponse`. Si cambia la query de WordPress, solo tocas el Repository.

---

### 1.4 Separación: WordPress Core vs. tu lógica

Este es el punto más crítico. Hoy tu lógica de negocio está atada a WordPress directamente (usas `get_posts()`, `get_field()` desde cualquier parte). Esto hace imposible testear o reutilizar ese código.

La solución es el **patrón Repository**: tu aplicación habla con una interfaz, y WordPress es solo una implementación de esa interfaz.

```
Tu código  →  Contrato (Interface)  ←  Implementación WordPress
```

Si mañana quieres cambiar a una API externa o una base de datos distinta, solo cambias la implementación, sin tocar ni Services ni Controllers.

---

## Parte 2 — Buenas Prácticas PHP Modernas

### 2.1 Interfaces: los contratos de tu arquitectura

Una interfaz define *qué puede hacer* una clase, sin decir *cómo lo hace*.

```php
<?php
// app/Contracts/Repository/ServiceRepositoryInterface.php

namespace App\Contracts\Repository;

use App\Domain\Entities\Service;

interface ServiceRepositoryInterface
{
    /**
     * @return Service[]
     */
    public function findAll(): array;

    public function findBySlug(string $slug): ?Service;

    /**
     * @return Service[]
     */
    public function findByCategory(string $category): array;
}
```

Ahora tu implementación real con WordPress:

```php
<?php
// app/Infrastructure/WordPress/ServiceRepository.php

namespace App\Infrastructure\WordPress;

use App\Contracts\Repository\ServiceRepositoryInterface;
use App\Domain\Entities\Service;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function findAll(): array
    {
        $posts = get_posts([
            'post_type'      => 'servicio',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        return array_map(
            fn(\WP_Post $post) => $this->hydrate($post),
            $posts
        );
    }

    public function findBySlug(string $slug): ?Service
    {
        $posts = get_posts([
            'post_type' => 'servicio',
            'name'      => $slug,
            'numberposts' => 1,
        ]);

        return !empty($posts) ? $this->hydrate($posts[0]) : null;
    }

    public function findByCategory(string $category): array
    {
        // implementación con tax_query...
        return [];
    }

    // Convierte un WP_Post crudo en tu Entidad limpia
    private function hydrate(\WP_Post $post): Service
    {
        return new Service(
            id:          $post->ID,
            title:       $post->post_title,
            slug:        $post->post_name,
            description: get_field('descripcion', $post->ID) ?? '',
            imageUrl:    get_the_post_thumbnail_url($post->ID, 'full') ?? '',
        );
    }
    
    public function findByCategory(string $category): array
    {
        return [];
    }
}
```

**¿Por qué importa?** Tu Service solo habla con la interfaz, nunca con el Repository directamente:

```php
// Services/ServiceService.php
class ServiceService
{
    // Recibe la INTERFAZ, no la implementación concreta
    public function __construct(
        private ServiceRepositoryInterface $repository
    ) {}

    public function getAllServices(): array
    {
        return $this->repository->findAll();
    }
}
```

---

### 2.2 Entidades y DTOs con tipos estrictos

**Entidad**: representa un objeto de tu dominio con identidad (tiene ID).

```php
<?php
// app/Domain/Entities/Service.php

namespace App\Domain\Entities;

// PHP 8.1+: readonly hace los atributos inmutables después de construirse
readonly class Service
{
    public function __construct(
        public int    $id,
        public string $title,
        public string $slug,
        public string $description,
        public string $imageUrl,
    ) {}
}
```

**DTO (Data Transfer Object)**: lleva datos entre capas, sin lógica. Úsalo para las requests de entrada o para formatear respuestas.

```php
<?php
// app/Domain/DTO/ServiceFilterDTO.php

namespace App\Domain\DTO;

// PHP 8.2+: readonly class directamente
readonly class ServiceFilterDTO
{
    public function __construct(
        public ?string $category = null,
        public int     $perPage  = 10,
        public int     $page     = 1,
    ) {}

    // Factory: construye el DTO desde los parámetros REST de WordPress
    public static function fromRequest(\WP_REST_Request $request): self
    {
        return new self(
            category: $request->get_param('category'),
            perPage:  (int) ($request->get_param('per_page') ?? 10),
            page:     (int) ($request->get_param('page') ?? 1),
        );
    }
}
```

---

### 2.3 Enums: reemplaza las "magic strings"

¿Tienes strings como `'principal'`, `'servicio'` regados por todo el código? Un Enum los centraliza y los hace seguros.

```php
<?php
// app/Domain/Enums/PostType.php

namespace App\Domain\Enums;

enum PostType: string
{
    case PRINCIPAL = 'principal';
    case SERVICE   = 'servicio';
    case DOCTOR    = 'doctor';
    case NEWS      = 'noticia';

    // Devuelve el label legible para humanos
    public function label(): string
    {
        return match($this) {
            self::PRINCIPAL => 'Principal',
            self::SERVICE   => 'Servicio',
            self::DOCTOR    => 'Doctor',
            self::NEWS      => 'Noticia',
        };
    }
}
```

Uso:

```php
// Antes — frágil, si escribes mal el string falla silenciosamente
get_posts(['post_type' => 'serivicio']);  // typo, nadie lo detecta

// Después — el IDE y PHP detectan el error antes de ejecutar
get_posts(['post_type' => PostType::SERVICE->value]);
```

---

### 2.4 Value Objects: encapsula validación

Un Value Object es un tipo que valida sus propios datos. Ideal para slugs, emails, URLs, etc.

```php
<?php
// app/Domain/ValueObjects/Slug.php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final class Slug
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $sanitized = sanitize_title($value);

        if (empty($sanitized)) {
            throw new InvalidArgumentException(
                "El slug '$value' no es válido."
            );
        }

        $this->value = $sanitized;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

Uso:

```php
// Si el slug es inválido, lanza excepción inmediatamente
$slug = new Slug($request->get_param('slug'));
$service = $repository->findBySlug($slug->value);
```

---

### 2.5 Controllers REST: delgados y enfocados

Un Controller solo debe hacer tres cosas: recibir la request, delegar a un Service, y devolver la respuesta. Nada más.

```php
<?php
// app/Api/Controllers/ServiceController.php

namespace App\Api\Controllers;

use App\Domain\DTO\ServiceFilterDTO;
use App\Responses\ServiceResponse;
use App\Services\ServiceService;

class ServiceController
{
    public function __construct(
        private ServiceService  $service,
        private ServiceResponse $response,
    ) {}

    // Mapea a: GET /wp-json/girasalud/v1/servicios
    public function index(\WP_REST_Request $request): \WP_REST_Response
    {
        $filter   = ServiceFilterDTO::fromRequest($request);
        $services = $this->service->getAllServices($filter);

        return $this->response->collection($services);
    }

    // Mapea a: GET /wp-json/girasalud/v1/servicios/{slug}
    public function show(\WP_REST_Request $request): \WP_REST_Response
    {
        $slug    = $request->get_param('slug');
        $service = $this->service->getBySlug($slug);

        if ($service === null) {
            return new \WP_REST_Response(
                ['message' => 'Servicio no encontrado'],
                404
            );
        }

        return $this->response->single($service);
    }
}
```

---

### 2.6 Registro de rutas REST: separado del Controller

```php
<?php
// app/Api/Routes/ServiceRoutes.php

namespace App\Api\Routes;

use App\Api\Controllers\ServiceController;

class ServiceRoutes
{
    private const NAMESPACE = 'girasalud/v1';

    public function __construct(
        private ServiceController $controller
    ) {}

    public function register(): void
    {
        add_action('rest_api_init', function () {

            register_rest_route(self::NAMESPACE, '/servicios', [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this->controller, 'index'],
                'permission_callback' => '__return_true', // público
                'args'                => $this->collectionArgs(),
            ]);

            register_rest_route(self::NAMESPACE, '/servicios/(?P<slug>[a-z0-9\-]+)', [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this->controller, 'show'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    private function collectionArgs(): array
    {
        return [
            'category' => [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'per_page' => [
                'type'    => 'integer',
                'default' => 10,
                'minimum' => 1,
                'maximum' => 100,
            ],
        ];
    }
}
```

---

### 2.7 Respuestas consistentes

Centraliza el formato JSON para que tu frontend siempre reciba la misma estructura:

```php
<?php
// app/Responses/ServiceResponse.php

namespace App\Responses;

use App\Domain\Entities\Service;

class ServiceResponse
{
    public function single(Service $service): \WP_REST_Response
    {
        return new \WP_REST_Response(
            $this->transform($service),
            200
        );
    }

    /**
     * @param Service[] $services
     */
    public function collection(array $services): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'data'  => array_map([$this, 'transform'], $services),
            'total' => count($services),
        ], 200);
    }

    private function transform(Service $service): array
    {
        return [
            'id'          => $service->id,
            'title'       => $service->title,
            'slug'        => $service->slug,
            'description' => $service->description,
            'image_url'   => $service->imageUrl,
            '_links'      => [
                'self' => rest_url("girasalud/v1/servicios/{$service->slug}"),
            ],
        ];
    }
}
```

El JSON que recibe tu frontend siempre tiene esta forma predecible:

```json
{
  "data": [
    {
      "id": 42,
      "title": "Psicología Clínica",
      "slug": "psicologia-clinica",
      "description": "...",
      "image_url": "https://...",
      "_links": { "self": "https://.../wp-json/girasalud/v1/servicios/psicologia-clinica" }
    }
  ],
  "total": 1
}
```

---

### 2.8 Container / Inyección de Dependencias

El Container es el "pegamento" que construye todos los objetos correctamente. Evita el `new` regado por todo el código.

```php
<?php
// app/Container/Container.php

namespace App\Container;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    // Registra cómo construir algo
    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    // Registra un singleton (una sola instancia)
    public function singleton(string $abstract, callable $factory): void
    {
        $this->bind($abstract, function () use ($abstract, $factory) {
            if (!isset($this->instances[$abstract])) {
                $this->instances[$abstract] = $factory($this);
            }
            return $this->instances[$abstract];
        });
    }

    // Resuelve (construye) un objeto
    public function make(string $abstract): mixed
    {
        if (isset($this->bindings[$abstract])) {
            return ($this->bindings[$abstract])($this);
        }
        throw new \RuntimeException("No se puede resolver: $abstract");
    }
}
```

El Bootstrap lo usa así:

```php
<?php
// app/Bootstrap/Application.php

namespace App\Bootstrap;

use App\Container\Container;
use App\Contracts\Repository\ServiceRepositoryInterface;
use App\Infrastructure\WordPress\ServiceRepository;
use App\Services\ServiceService;
use App\Api\Controllers\ServiceController;
use App\Api\Routes\ServiceRoutes;
use App\Responses\ServiceResponse;

class Application
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->registerBindings();
    }

    private function registerBindings(): void
    {
        // La interfaz se resuelve con la implementación de WordPress
        $this->container->singleton(
            ServiceRepositoryInterface::class,
            fn() => new ServiceRepository()
        );

        $this->container->singleton(
            ServiceService::class,
            fn(Container $c) => new ServiceService(
                $c->make(ServiceRepositoryInterface::class)
            )
        );

        $this->container->singleton(
            ServiceController::class,
            fn(Container $c) => new ServiceController(
                $c->make(ServiceService::class),
                new ServiceResponse()
            )
        );

        $this->container->singleton(
            ServiceRoutes::class,
            fn(Container $c) => new ServiceRoutes(
                $c->make(ServiceController::class)
            )
        );
    }

    public function boot(): void
    {
        // Registra las rutas REST
        $this->container->make(ServiceRoutes::class)->register();
    }
}
```

Y tu `functions.php` queda así de limpio:

```php
<?php
// functions.php  ← Solo estas líneas

if (!defined('ABSPATH')) exit;

require_once get_template_directory() . '/vendor/autoload.php';
// o tu propio autoloader si no usas Composer

use App\Bootstrap\Application;

$app = new Application();
$app->boot();

// Solo los hooks de WordPress que no tienen lugar mejor
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('girasalud-style', get_stylesheet_uri());
});
```

---

## Parte 3 — Referencia rápida de tipos PHP modernos

| Característica | Cuándo usarla |
|---|---|
| `readonly` en propiedades | Entidades, DTOs — datos que no deben cambiar después de construirse |
| `enum` | Reemplazar magic strings: post types, estados, categorías |
| `?Type` (nullable) | Cuando un valor puede ser nulo (resultado de búsqueda no encontrado) |
| `array<Type>` (PHPDoc) | Documentar arrays tipados cuando no puedes usar typed arrays nativos |
| `match` en vez de `switch` | Cuando necesitas retornar un valor; es una expresión, no un statement |
| `fn() =>` (arrow functions) | Callbacks de una línea, especialmente en `array_map` y `array_filter` |
| `named arguments` | Constructores con muchos parámetros — hace el código autodocumentado |
| `throw` como expresión | Dentro de null coalescing: `$val ?? throw new Exception(...)` |

---

## Resumen: qué cambiar primero

El camino más directo desde tu código actual a esta arquitectura, en orden de impacto:

1. **Crea las Entidades y Enums** — son puro PHP, sin dependencia de WordPress. El lugar más fácil de empezar.
2. **Extrae el Repository** — mueve las `WP_Query` y `get_field()` a una clase dedicada con su interfaz.
3. **Crea los Services** — que usan el Repository via la interfaz.
4. **Registra las rutas REST** — en su propia clase, usando los Controllers.
5. **Crea el Container/Bootstrap** — y limpia `functions.php`.
6. **Mueve los templates** a `templates/` — solo PHP que hace echo, sin lógica.

Cada paso es independiente. Puedes hacer uno a la semana sin romper lo que ya funciona.
