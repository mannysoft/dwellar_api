<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RateLimiter implements HttpKernelInterface {

    /**
     * The wrapped kernel implementation.
     *
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $app;

    /**
     * Create a new RateLimiter instance.
     *
     * @param  \Symfony\Component\HttpKernel\HttpKernelInterface  $app
     * @return void
     */
    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the given request and get the response.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  int   $type
     * @param  bool  $catch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(SymfonyRequest $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        // Handle on passed down request
        $response = $this->app->handle($request, $type, $catch);

        $requestsPerHour = 60;

        // Rate limit by IP address
        $key = sprintf('api:%s', $request->getClientIp());

        // Add if doesn't exist
        // Remember for 1 hour
        \Cache::add($key, 0, 60);

        // Add to count
        $count = \Cache::increment($key);

        if( $count > $requestsPerHour )
        {
            // Short-circuit response - we're ignoring
            $response->setContent('Rate limit exceeded');
            $response->setStatusCode(403);
        }

        $response->headers->set('X-Ratelimit-Limit', $requestsPerHour, false);
        $response->headers->set('X-Ratelimit-Remaining', $requestsPerHour-(int)$count, false);

        return $response;
    }

}