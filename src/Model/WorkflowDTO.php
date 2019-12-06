<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\Model;

/**
 * Class WorkflowDTO.
 */
class WorkflowDTO
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string
     */
    protected $transition;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTransition(): string
    {
        return $this->transition;
    }

    /**
     * @param string $transition
     */
    public function setTransition(string $transition): void
    {
        $this->transition = $transition;
    }
}
