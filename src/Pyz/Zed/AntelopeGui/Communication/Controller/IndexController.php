<?php

namespace Pyz\Zed\AntelopeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\AntelopeGui\Communication\AntelopeGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const URL_INDEX = '/antelope-gui/index';

    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'antelopeTable' => $this->getFactory()->createAntelopeTable()->render(),
        ]);
    }

    public function tableAction(): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createAntelopeTable()->fetchData(),
        );
    }

    /**
     * @return array<string, mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): array|RedirectResponse
    {
        $form = $this->getFactory()
            ->createAntelopeCreateForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->getAntelopeFacade()->createAntelope($form->getData());
            $this->addSuccessMessage('Antelope was created.');

            return $this->redirectResponse(static::URL_INDEX);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
