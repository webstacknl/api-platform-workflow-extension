<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\EventListener;

use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\Registry;

final class WorkflowEnabledTransitionsListener
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Registry $workflows,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_GET)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !isset($attributes['item_operation_name'])
            || 'state_get' !== $attributes['item_operation_name']
        ) {
            return;
        }

        $class = $request->attributes->get('data');

        if ($request->attributes->has('previous_data')) {
            $class = $request->attributes->get('previous_data');
        }

        /** @var object $class */
        $workflows = $this->workflows->all($class);

        $output = [];

        foreach ($workflows as $workflow) {
            $workflowName = $workflow->getName();

            $output[$workflowName] = $this->workflows->get($class, $workflowName)->getEnabledTransitions($class);
        }

        $event->setResponse(new Response($this->serializer->serialize($output, 'json')));
    }
}
