<?php

namespace Kraken\Stream;

use Kraken\Event\EventEmitterInterface;

/**
 * @override
 *
 * @event drain
 */
interface StreamWritableInterface extends EventEmitterInterface, StreamSeekableInterface
{
    /**
     * Check if stream is writable.
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Write text to stream.
     *
     * @param string $text
     * @return bool
     */
    public function write($text);

    /**
     * Set the size of stream buffer in bytes.
     *
     * @param int $bufferSize
     */
    public function setBufferSize($bufferSize);

    /**
     * Get the current size of stream buffer.
     *
     * @return int
     */
    public function getBufferSize();
}