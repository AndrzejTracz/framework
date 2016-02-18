<?php

namespace Kraken\Test\Unit\Ipc\Socket;

use Kraken\Exception\Runtime\InstantiationException;
use Kraken\Ipc\Socket\Socket;
use Kraken\Loop\LoopInterface;
use Kraken\Test\Unit\TestCase;

class SocketTest extends TestCase
{
    public function tearDown()
    {
        $path = str_replace('unix://', '', $this->tempSocketAddress());
        if (file_exists($path))
        {
            unlink($path);
        }
    }

    public function testConstructor()
    {
        $server = stream_socket_server($this->tempSocketAddress());
        $socket = $this->createSocketMock();
    }

    public function testConstructor_ThrowsException_WhenNoServerExists()
    {
        $this->setExpectedException(InstantiationException::class);
        $socket = $this->createSocketMock();
    }

    public function testConstructor_ThrowsException_OnInvalidResource()
    {
        $this->setExpectedException(InstantiationException::class);
        $socket = $this->createSocketMock('invalid');
    }

    public function testApiGetLocalEndpoint_ReturnsValidEndpoint()
    {
        $server = stream_socket_server($this->tempSocketRemoteAddress());
        $socket = $this->createSocketMock($this->tempSocketRemoteAddress());
        $this->assertRegExp('#^tcp://(([0-9]*?)\.){3}([0-9]*?):([0-9]*?)$#si', $socket->getLocalEndpoint());
    }

    public function testApiGetRemoteEndpoint_ReturnsValidEndpoint()
    {
        $remote = $this->tempSocketRemoteAddress();
        $server = stream_socket_server($remote);
        $socket = $this->createSocketMock($remote);
        $this->assertEquals($remote, $socket->getRemoteEndpoint());
    }

    /**
     * @param resource|null $resource
     * @param LoopInterface $loop
     * @return Socket
     */
    protected function createSocketMock($resource = null, LoopInterface $loop = null)
    {
        return $this->createSocketInjection(
            is_null($resource) ? $this->tempSocketAddress() : $resource,
            is_null($loop) ? $this->createLoopMock() : $loop
        );
    }

    /**
     * @param string|resource $endpointOrResource
     * @param LoopInterface $loop
     * @return Socket
     */
    protected function createSocketInjection($endpointOrResource, LoopInterface $loop)
    {
        return new Socket($endpointOrResource, $loop);
    }

    /**
     * @return string
     */
    private function tempSocketAddress()
    {
        return 'unix://' . $this->basePath() . '/temp.sock';
    }

    /**
     * @return string
     */
    private function tempSocketRemoteAddress()
    {
        return 'tcp://127.0.0.1:2080';
    }
}