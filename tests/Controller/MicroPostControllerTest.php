<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MicroPostControllerTest extends WebTestCase
{
    /**
     * Test index method returns status code 200.
     *
     * @return void
     */
    public function testLike(): void
    {
        $client = static::createClient();

        $client->request('GET', '/micro-post/');

        self::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
