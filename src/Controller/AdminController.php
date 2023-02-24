<?php

namespace App\Controller;


use App\Entity\Company;
use App\Form\HeroType;
use App\Entity\Hero;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use PhpParser\Error;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("ROLE_ADMIN")]
#[Route("/admin")]
class AdminController extends AbstractController
{
    #[Route("/", name: "admin")]
    public function user_admin(
        Request $request,
        EntityManagerInterface $entityManager,
        HeroRepository $heroRepository,
        ManagerRegistry $doctrine
    ): Response
    {
        $queryBuilderHeroes = $heroRepository->createPagerQueryBuilder($doctrine);

        //search
        $search = $request->get('search');
        if ($search) {
            $queryBuilderHeroes = $heroRepository->searchPagerQueryBuilder($doctrine, $search);
        }

        // pagination
        $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilderHeroes));
        $pagerfanta->setMaxPerPage(50);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));


        return $this->render('admin/admin.html.twig', [
            "pager" => $pagerfanta,
        ]);
    }

    #[Route("/add-hero", name: "add-hero")]
    public function test(
        Request $request,
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine
    ): Response
    {
        $entity = new Hero();

        $company = new Company();
        $entity->addCompany($company);

        $form = $this->createForm(HeroType::class, $entity);
//        $form = $this->createForm(HeroType::class, null); //delete it
        $form->add("submit", SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $heroPicture = $form['photo']->getData();
            if ($heroPicture) {
                $entity->uploadPhoto($heroPicture, "uploads/photos/");
            }
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash("notice", "Hero is added successfully!");
        }
        return $this->render("admin/add-hero.html.twig",
        [
            "form" => $form->createView()
        ]);
    }

    #[Route("/edit/{id<\d+>}", name: "edit-hero")]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine,
        $id
    ): Response
    {

        $hero = $doctrine
        ->getRepository(Hero::class)
        ->find($id);
//

        if (!$hero) {
            return $this->render("error-pages/error.html.twig",
            [
                "error_message" => "Bruh! There is no hero with such id. Be careful next time ;-)"
            ]);
        }
        $oldPhoto = $hero->getPhoto();

        $form = $this->createForm(HeroType::class, $hero);
        $form->add("submit", SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $formPicture = $form['photo']->getData();
            if ($formPicture) {
                $fs = new Filesystem();

                if ($oldPhoto) {
                    $fs->remove("uploads/photos/" . $oldPhoto);
                }
                $formData->uploadPhoto($formPicture, "uploads/photos/");
            } else {
                $formData->setPhoto($oldPhoto);
            }
            $doctrine->getManager();
            $entityManager->persist($formData);
            $entityManager->flush();
            $this->addFlash("notice", "Hero was edited successfully!");
        }

        return $this->render("admin/edit.html.twig",
            [
                "form" => $form->createView()
            ]);
    }


    #[Route("/delete/{id<\d+>}", name: "delete-hero")]
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine,
        $id
    ): Response
    {
        $hero = $doctrine
            ->getRepository(Hero::class)
            ->find($id);

        $companies = $hero->getCompanies();
        foreach ($companies as $company) {
            $entityManager->remove($company);
        }
        $entityManager->remove($hero);
        $entityManager->flush();

        $this->addFlash("notice", "Hero was deleted.");

        return $this->redirectToRoute("admin");
//        return new Response("deleted");

    }
}