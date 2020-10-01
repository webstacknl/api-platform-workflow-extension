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
    protected $workflowName;

    /**
     * @var string
     */
    protected $transition;

    /**
     * @return string|null
     */
    public function getWorkflowName(): ?string
    {
        return $this->workflowName;
    }

    /**
     * @param string|null $workflowName
     */
    public function setWorkflowName(?string $workflowName): void
    {
        $this->workflowName = $workflowName;
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
