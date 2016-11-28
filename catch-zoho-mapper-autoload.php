<?php

$mapping = array(
    'CatchZohoMapper\ZohoServiceProvider' => __DIR__ . '/src/ZohoServiceProvider.php',
    'CatchZohoMapper\ZohoMapper' => __DIR__ . '/src/ZohoMapper.php',
    'CatchZohoMapper\ZohoResponse' => __DIR__ . '/src/ZohoResponse.php',
    'CatchZohoMapper\ZohoErrors' => __DIR__ . '/src/ZohoErrors.php',
    'CatchZohoMapper\ZohoOperationParams' => __DIR__ . '/src/ZohoOperationParams.php',
    'CatchZohoMapper\ZohoField' => __DIR__ . '/src/ZohoField.php',
    'CatchZohoMapper\ZohoSection' => __DIR__ . '/src/ZohoSection.php',
    'CatchZohoMapper\Interfaces\ZohoModuleInterface' => __DIR__ . '/src/Interfaces/ZohoModuleInterface.php',
    'CatchZohoMapper\Traits\ZohoModule' => __DIR__ . '/src/Traits/ZohoModuleOperations.php',
    'CatchZohoMapper\Traits\Field' => __DIR__ . '/src/Traits/Field.php',
    'CatchZohoMapper\Traits\Section' => __DIR__ . '/src/Traits/Section.php',
    'GuzzleHttp\Client' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Client.php',
    'GuzzleHttp\ClientInterface' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/ClientInterface.php',
    'GuzzleHttp\HandlerStack' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/HandlerStack.php',
    'GuzzleHttp\Handler\Proxy' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/Proxy.php',
    'GuzzleHttp\Handler\CurlMultiHandler' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/CurlMultiHandler.php',
    'GuzzleHttp\Handler\CurlFactory' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/CurlFactory.php',
    'GuzzleHttp\Handler\CurlFactoryInterface' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/CurlFactoryInterface.php',
    'GuzzleHttp\Handler\CurlHandler' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/CurlHandler.php',
    'GuzzleHttp\Handler\StreamHandler' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/StreamHandler.php',
    'GuzzleHttp\Handler\EasyHandle' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Handler/EasyHandle.php',
    'GuzzleHttp\Middleware' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/Middleware.php',
    'GuzzleHttp\PrepareBodyMiddleware' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/PrepareBodyMiddleware.php',
    'GuzzleHttp\RedirectMiddleware' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php',
    'GuzzleHttp\RequestOptions' => __DIR__ . '/vendor/guzzlehttp/guzzle/src/RequestOptions.php',
    'GuzzleHttp\Psr7\Uri' => __DIR__ . '/vendor/guzzlehttp/psr7/src/Uri.php',
    'GuzzleHttp\Psr7\Request' => __DIR__ . '/vendor/guzzlehttp/psr7/src/Request.php',
    'GuzzleHttp\Psr7\MessageTrait' => __DIR__ . '/vendor/guzzlehttp/psr7/src/MessageTrait.php',
    'GuzzleHttp\Psr7\Stream' => __DIR__ . '/vendor/guzzlehttp/psr7/src/Stream.php',
    'GuzzleHttp\Psr7\Response' => __DIR__ . '/vendor/guzzlehttp/psr7/src/Response.php',
    'GuzzleHttp\Promise\FulfilledPromise' => __DIR__ . '/vendor/guzzlehttp/promises/src/FulfilledPromise.php',
    'GuzzleHttp\Promise\PromiseInterface' => __DIR__ . '/vendor/guzzlehttp/promises/src/PromiseInterface.php',
    'GuzzleHttp\Promise\TaskQueue' => __DIR__ . '/vendor/guzzlehttp/promises/src/TaskQueue.php',
    'GuzzleHttp\Promise\Promise' => __DIR__ . '/vendor/guzzlehttp/promises/src/Promise.php',
    'Psr\Http\Message\UriInterface' => __DIR__ . '/vendor/psr/http-message/src/UriInterface.php',
    'Psr\Http\Message\RequestInterface' => __DIR__ . '/vendor/psr/http-message/src/RequestInterface.php',
    'Psr\Http\Message\MessageInterface' => __DIR__ . '/vendor/psr/http-message/src/MessageInterface.php',
    'Psr\Http\Message\StreamInterface' => __DIR__ . '/vendor/psr/http-message/src/StreamInterface.php',
    'Psr\Http\Message\ResponseInterface' => __DIR__ . '/vendor/psr/http-message/src/ResponseInterface.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);

require __DIR__ . '/vendor/guzzlehttp/guzzle/src/functions.php';
require __DIR__ . '/vendor/guzzlehttp/psr7/src/functions.php';
require __DIR__ . '/vendor/guzzlehttp/promises/src/functions.php';
/*
require __DIR__ . '/Aws/functions.php';
require __DIR__ . '/GuzzleHttp/functions.php';
require __DIR__ . '/GuzzleHttp/Psr7/functions.php';
require __DIR__ . '/GuzzleHttp/Promise/functions.php';
require __DIR__ . '/JmesPath/JmesPath.php';
*/