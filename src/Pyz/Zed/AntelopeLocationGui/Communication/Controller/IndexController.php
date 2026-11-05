<?php

namespace Pyz\Zed\AntelopeLocationGui\Communication\Controller;

use Generated\Shared\Transfer\AntelopeLocationCriteriaTransfer;
use Generated\Shared\Transfer\AntelopeLocationTransfer;
use Pyz\Zed\AntelopeLocationGui\Communication\Form\AntelopeLocationCreateForm;
use Pyz\Zed\AntelopeLocationGui\Communication\Table\AntelopeLocationTable;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\AntelopeLocationGui\Communication\AntelopeLocationGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const URL_INDEX = '/antelope-location-gui/index';

    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'antelopeLocationTable' => $this->getFactory()->createAntelopeLocationTable()->render(),
        ]);
    }

    public function tableAction(): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createAntelopeLocationTable()->fetchData(),
        );
    }

    /**
     * @return array<string, mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): array|RedirectResponse
    {
        $form = $this->getFactory()
            ->createAntelopeLocationCreateForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isLocationNameUnique($form)) {
            $this->getFactory()->getAntelopeFacade()->createLocation($form->getData());
            $this->addSuccessMessage('Antelope location was created.');

            return $this->redirectResponse(static::URL_INDEX);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return array<string, mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request): array|RedirectResponse
    {
        $idAntelopeLocation = $this->castId(
            $request->query->get(AntelopeLocationTable::PARAM_ID_ANTELOPE_LOCATION),
        );

        $antelopeLocationTransfer = $this->getFactory()
            ->getAntelopeFacade()
            ->findLocationById($idAntelopeLocation);

        if ($antelopeLocationTransfer === null) {
            $this->addErrorMessage('Antelope location not found.');

            return $this->redirectResponse(static::URL_INDEX);
        }

        $form = $this->getFactory()
            ->createAntelopeLocationCreateForm($antelopeLocationTransfer)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isLocationNameUnique($form)) {
            $antelopeLocationResponseTransfer = $this->getFactory()
                ->getAntelopeFacade()
                ->updateLocation($form->getData());

            if ($antelopeLocationResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage('Antelope location was updated.');

                return $this->redirectResponse(static::URL_INDEX);
            }

            $this->addErrorMessage('Antelope location could not be updated.');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'antelopeLocation' => $antelopeLocationTransfer,
        ]);
    }

    /**
     * The location name has a unique DB constraint; report a duplicate as a form error instead of a DB exception.
     */
    protected function isLocationNameUnique(FormInterface $form): bool
    {
        /** @var \Generated\Shared\Transfer\AntelopeLocationTransfer $antelopeLocationTransfer */
        $antelopeLocationTransfer = $form->getData();

        $antelopeLocationResponseTransfer = $this->getFactory()
            ->getAntelopeFacade()
            ->getAntelopeLocation(
                (new AntelopeLocationCriteriaTransfer())->setLocationName($antelopeLocationTransfer->getLocationName()),
            );

        if (!$antelopeLocationResponseTransfer->getIsSuccessful()) {
            return true;
        }

        $existingId = $antelopeLocationResponseTransfer->getAntelopeLocation()->getIdAntelopeLocation();
        if ($existingId === $antelopeLocationTransfer->getIdAntelopeLocation()) {
            return true;
        }

        $form->get(AntelopeLocationCreateForm::FIELD_LOCATION_NAME)
            ->addError(new FormError('A location with this name already exists.'));

        return false;
    }
}
