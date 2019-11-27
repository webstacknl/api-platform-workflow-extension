<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\EventListener;

use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Registry;

final class WorkflowTransitionListener
{
    /**
     * @var Registry
     */
    private $workflows;

    /**
     * WorkflowTransitionListener constructor.
     * @param Registry $workflows
     */
    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_PATCH)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !isset($attributes['item_operation_name'])
            || 'state_apply' !== $attributes['item_operation_name']
        ) {
            return;
        }

        $requestContent = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

        if (!isset($requestContent->transition) || !($transition = $requestContent->transition)) {
            throw new BadRequestHttpException('Transition is required.');
        }

        $class = $request->attributes->get('data');
        $workflow = $this->workflows->get($class);

        $workflow->apply($class, $transition);
    }
}
