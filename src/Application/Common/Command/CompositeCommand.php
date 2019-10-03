<?php
/**
 * CompositeCommand
 *
 * @author    Stefan Gehrig <gehrig@teqneers.de>
 * @package   App\Application\Common\Command
 * @copyright Copyright (C) 2019 TEQneers GmbH & Co. KG. All rights reserved.
 */

namespace App\Application\Common\Command;

use IteratorAggregate;
use Symfony\Component\Validator\Constraints as Assert;
use Traversable;

/**
 * Class CompositeCommand
 *
 * @package App\Application\Common\Command
 */
class CompositeCommand implements IteratorAggregate
{
    /**
     * @Assert\Valid()
     *
     * @var object[]
     */
    private $commands;

    /**
     * @return self
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param object[] $commands
     * @return self
     */
    public static function from(object ...$commands): self
    {
        return new self($commands);
    }

    /**
     * @param object[] $commands
     * @return self
     */
    public static function fromArray(array $commands): self
    {
        return self::from(...$commands);
    }

    /**
     * @param object[] $commands
     */
    private function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param object[] $commands
     * @return $this
     */
    public function append(object ...$commands): self
    {
        $this->commands = array_merge($this->commands, $commands);
        return $this;
    }

    /**
     * @param object[] $commands
     * @return $this
     */
    public function prepend(object ...$commands): self
    {
        $this->commands = array_merge($commands, $this->commands);
        return $this;
    }

    /**
     * @param int      $index
     * @param object[] $commands
     * @return $this
     */
    public function insert(int $index, object ...$commands): self
    {
        array_splice($this->commands, $index, 0, $commands);
        return $this;
    }

    /**
     * @return object[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        foreach ($this->commands as $command) {
            if ($command instanceof self) {
                yield from $command;
            } else {
                yield $command;
            }
        }
    }
}
