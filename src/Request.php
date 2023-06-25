<?php

namespace BadRouter;

class Request {
  public ?string $route;
  public ?string $method;
  public ?string $address;
  public ?int $port;
  public ?string $platform;
  public ?string $user_agent;
  public ?string $accept;
  public ?string $encoding;
  public ?string $language;
  public ?string $referrer;
  public ?float $time;

  public function __construct(array $server) {
    $this->route = parse_url($server['REQUEST_URI'], PHP_URL_PATH);
    $this->method = $server['REQUEST_METHOD'] ?? null;
    $this->address = $server['REMOTE_ADDR'] ?? null;
    $this->port = $server['REMOTE_PORT'] ?? null;
    $this->platform = $server['HTTP_SEC_CH_UA_PLATFORM'] ?? null;
    $this->user_agent = $server['HTTP_USER_AGENT'] ?? null;
    $this->accept = $server['HTTP_ACCEPT'] ?? null;
    $this->encoding = $server['HTTP_ACCEPT_ENCODING'] ?? null;
    $this->language = $server['HTTP_ACCEPT_LANGUAGE'] ?? null;
    $this->referrer = $server['HTTP_REFERER'] ?? null;
    $this->time = $server['REQUEST_TIME_FLOAT'] ?? null;
  }
}

?>

