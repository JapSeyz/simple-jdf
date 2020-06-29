<?php

use JapSeyz\SimpleJDF\Job;
use JapSeyz\SimpleJDF\Tests\TestCase;

class CanCreateJobTest extends TestCase
{
    /** @test */
    public function it_can_create_job()
    {
        $job = new Job();

        $job->setName('Testing');
        $job->setPrintFile('https://imusic.dk/test.pdf');

        $message = $job->asXML();

        $this->assertStringContainsString('DescriptiveName="Testing"', $message);
        $this->assertStringContainsString('<LayoutElement><FileSpec URL="https://imusic.dk/test.pdf"/></LayoutElement>', $message);
    }
}
