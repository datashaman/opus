<?php

use App\Mcp\Servers\CompositionServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::local('composition', CompositionServer::class);
