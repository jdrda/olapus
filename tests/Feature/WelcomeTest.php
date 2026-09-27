<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $this->withoutVite();
        $this->get('/')->assertOk();
    }
}
