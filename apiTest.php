<?php

/**
 * Example PHPUnit tests for FoxyCart's Hypermedia API.
 */
class ApiTest extends PHPUnit_Framework_TestCase {
    /**
     * Test the HTTP status code returned getting the root URL without the API
     * version header.
     * @test
     */
    public function testBareGet() {
        $ch = curl_init('https://api-sandbox.foxycart.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertEquals(400, $info);
    }

    /**
     * Test the content-type header requesting the root URL wtihout the
     * API version header.
     * @test
     */
    public function testContentTypeOnError() {
        $ch = curl_init('https://api-sandbox.foxycart.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $this->assertEquals('application/vnd.error+json', $info);
    }

    /**
     * Test that the cache-control HTTP header is set to no-cache on the root.
     * @test
     */
    public function testCacheControlHeader() {
        $ch = curl_init('https://api-sandbox.foxycart.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $output = curl_exec($ch);
        $this->assertContains('Cache-Control: no-cache', $output);
    }

    /**
     * Test that the content contains the expected error message if a request is
     * made without the required API version header.
     * @test
     */
    public function testOutputContainsErrorMessage() {
        $ch = curl_init('https://api-sandbox.foxycart.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $this->assertContains('The FOXYCART-API-VERSION request header',
            $output);
    }
}
