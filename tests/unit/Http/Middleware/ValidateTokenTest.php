<?php

namespace Tests\App;

use App\Http\Middleware\ValidateToken;
use Mockery;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTokenTest extends \TestCase
{

    /**
     * @test
     */
    public function rejectsInvalidTokens()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')->andReturn([
            2 => [
                'token' => 'something like a token'
            ]
        ]);
        $validateToken = new ValidateToken();

        $response = $validateToken->handle($request, function () {});

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}