<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontControllerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getUriDataProvider
     */
    public function it_checks_any_uri_returns_app_view(string $uri): void
    {
        $response = $this->get("/{$uri}");

        $response->assertViewIs('app');
    }

    /** @return array */
    private function getUriDataProvider(): array
    {
        return [
            [''],
            ['test'],
            ['test 2'],
            ['test 3'],
        ];
    }
}
