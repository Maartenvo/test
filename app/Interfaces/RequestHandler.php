<?php

namespace App\Interfaces;

interface RequestHandler
{
    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function makeGet(string $url, array $options);

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function makePost(string $url, array $options);

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function makePut(string $url, array $options);

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function makeDelete(string $url, array $options);

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function makePatch(string $url, array $options);

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * @return mixed
     */
    public function getContents();

    /**
     * @return mixed
     */
    public function getJsonContents();

    /**
     * @return mixed
     */
    public function getStatusCode();
}
