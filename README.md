<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Step 1 - Installazione JWT

- Move User to Models
- Install JWT :
```
composer require tymon/jwt-auth
```
- Add Provider in config/app.php
  
```
'providers' => [

    ...

    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

```
php artisan jwt:secret
```



## Step 2 - Configurazione JWT

- User Model add Methods
  

```
class User extends Authenticatable implements JWTSubject
```

```

 /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

```


Config config/auth.php

```
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

...

'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
...
'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
```

## Step 3 - Creazione Controllers

Creazione del Controller

```
php artisan make:controller Api/Auth/LoginController

php artisan make:controller Api/Auth/RegisterController

php artisan make:controller Api/Auth/MeController

php artisan make:controller Api/Auth/LogoutController
```


## Step 4 - Register

- Creazione della Request 

``` php artisan make:request Auth/RegisterRequest ```

- Modifica della Request

```
public function authorize()
    {
        return true;
    }
....
 public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required'
        ];
    }
...
```

- Add API

```
Route::post('auth/register', 'Api\Auth\RegisterController@action')->name('register');
```

- Add Body in Post

```
{
  "name" : "Marco Rossi",
  "email" : "marco.rossi@gmail.com",
  "password" : "q1w2e3"
}
```

- Registrazione Utente

Creazione di un evento per registrazione utente

```
php artisan make:event UserRegisteredEvent
php artisan make:listener UserRegisteredListener
```

```
class UserRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
```

- Listener

```
 public function handle($event)
{
    logger('Listener SendEmailNotification');

     logger($event->user->email);
}
```

- Controller RegisterController

```
 $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        if ($user != null && array_key_exists('id', $user->toArray())) {
            event(new UserRegisteredEvent($user));
        }

        return $user;
```

- Add Listener e EventServiceProvider

```
'App\Events\UserRegisteredEvent' => [
            'App\Listeners\UserRegisteredListener'
        ]
```


## Step 5 - Login

- Create Route
  
```
Route::post('/auth/login', 'Api\Auth\LoginController')->name('login');
```
- Create LoginRequest

```
php artisan make:request Auth/LoginRequest
```
- LoginRequest
```
return true
....
return [
            'email' => 'required',
            'password' => 'required'
        ];
```
- LoginController 
```

class LoginController extends Controller
{

    public function __invoke(LoginRequest $request)
    {

        //$validated = $request->validated();

        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            $errorMsg = "error credenziali";
            return  $errorMsg;
        }

        return response()->json([
            'token' => $token
        ]);
    }
}

```


## Step 6 - Response e Language

- Create Custom response
- Edit Login Controller
  
```
if (!$token = auth()->attempt($request->only('email', 'password'))) {
    $errorMsg = "error credenziali";
    return CustomResponse::setFailResponse($errorMsg, Response::HTTP_NOT_ACCEPTABLE, []);
}
```

- Add String Language resources/lang/en or lang/it

```
    'credential_incorrect' => 'Credential Incorrect',
    'logout' => 'Successfully logged out',
```