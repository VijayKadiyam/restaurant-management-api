<?php

namespace Tests;

use Exception;
use App\Exceptions\Handler;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
  
  protected $user, $headers, $company;

  /**
   * Creates the application.
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

    // Authorized user and headers
    $this->user = factory(\App\User::class)->create();
    $this->user->generateToken();

    $this->company = factory(\App\Company::class)->create();
    $this->company->assignUser($this->user);

    $this->headers = [
      'Authorization' =>  'Bearer ' . $this->user->api_token,
      'company_id'    =>  $this->company->id 
    ];

    return $app;
  }

  /*
   * To get the error details
   *
   *@
   */
  public function disableEH()
  {
    app()->instance(Handler::Class, new class extends Handler {
      public function __construct(){}
      public function report(Exception $exception) {}
      public function render($request, Exception $exception)
      {
        throw $exception;
      }
    });

  }
}
