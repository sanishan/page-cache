<?php

namespace Silber\PageCache\Middleware;

use Closure;
use Silber\PageCache\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * The cache instance.
     *
     * @var \Silber\PageCache\Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @var \Silber\PageCache\Cache  $cache
     */
    public function __construct(Cache $cache)
    {
        // dd($cache);
        $this->cache = $cache;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $path=null)
    {
        $response = $next($request);
        
        /// START ///
        /**
         * 
         *  You can create your own middleware and use "START" to "END" code in your own middleware as per the "readme.md" file
         *  You can change "if/else" as per your own choice.
         * 
         */
        $url = $request->getUri();
        if (
            strpos($url, "analyze") !== false ||
            strpos($url, "watch") !== false ||
            strpos($url, "sitemap") !== false ||
            strpos($url, "convert") !== false ||
            strpos($url, "getLink") !== false ||
            strpos($url, "list") !== false ||
            strpos($url, "myadminsite") !== false ||
            strpos($url, "login") !== false ||
            strpos($url, "register") !== false ||
            strpos($url, "admin") !== false ||
            strpos($url, "link") !== false ||
            strpos($url, "delete") !== false ||
            strpos($url, "unique") !== false ||
            strpos($url, "change") !== false ||
            strpos($url, "change_pass") !== false
        ) {

            return $response;
        }

        /// END ///

        // Add $this->cache->setCachePath($path); below and $path=null parameter in "function" I found it in the original repository, but I am not sure where, if you can find
        // please give a credit to the original author.

        if ($this->shouldCache($request, $response)) {
            $this->cache->setCachePath($path);
            $this->cache->cache($request, $response);
        }

        return $response;
    }

    /**
     * Determines whether the given request/response pair should be cached.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    protected function shouldCache(Request $request, Response $response)
    {

        return $request->isMethod('GET') && $response->getStatusCode() == 200;
    }
}
