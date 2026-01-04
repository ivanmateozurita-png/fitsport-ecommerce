<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_has_security_headers(): void
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    public function test_catalog_has_security_headers(): void
    {
        $response = $this->get('/catalog');
        
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_cart_has_security_headers(): void
    {
        $response = $this->get('/cart');
        
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_login_has_security_headers(): void
    {
        $response = $this->get('/login');
        
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }
}
