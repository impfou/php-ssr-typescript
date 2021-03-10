<?php

declare(strict_types=1);

namespace App\Controller\Market\Categories;

use App\Controller\ErrorHandler;
use App\Model\Market\UseCase\Categories\Category\Create;
use App\ReadModel\Market\Categories\Category\CategoryFetcher;
use App\ReadModel\Market\Categories\Category\Filter;
use App\Service\Uploader\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/market/categories', name: 'market.categories')]
class CategoriesController extends AbstractController
{
    private const PER_PAGE = 50;

    public function __construct(
        private ErrorHandler $errors,
    ) {
    }

    #[Route('', name: '')]
    public function index(Request $request, CategoryFetcher $fetcher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MARKET_MANAGE_CATEGORIES');

        $filter = Filter\Filter::all();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'name'),
            $request->query->get('direction', 'asc')
        );

        return $this->render('app/market/categories/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: '.create')]
    public function create(Request $request, Create\Handler $handler, FileUploader $uploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MARKET_MANAGE_CATEGORIES');

        $command = new Create\Command($this->getUser()->getId());

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logo = $form->get('logo')->getData();
            if ($logo) {
                $uploaded = $uploader->upload($logo);
                $file = new Create\Logo(
                    $uploaded->getPath(),
                    $uploaded->getName(),
                    $uploaded->getSize()
                );
                $command->logo = $file;
            }

            try {
                $handler->handle($command);

                return $this->redirectToRoute('market.categories');
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/market/categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
