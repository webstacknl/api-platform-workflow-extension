<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\Model;

class WorkflowDTO
{
    protected ?string $workflowName = null;

    protected string $transition;

    public function getWorkflowName(): ?string
    {
        return $this->workflowName;
    }

    public function setWorkflowName(?string $workflowName): void
    {
        $this->workflowName = $workflowName;
    }

    public function getTransition(): string
    {
        return $this->transition;
    }

    public function setTransition(string $transition): void
    {
        $this->transition = $transition;
    }
}
