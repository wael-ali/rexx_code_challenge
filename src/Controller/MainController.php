<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Form\JsonFileType;
use App\Entity\Participation;
use App\Form\ImportedFileEntity;
use App\Form\ParticipationFilterType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipationRepository;
use Symfony\Component\Serializer\Serializer;
use App\Service\ImportedParticipationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ParticipationsFromJsonImporter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {

        $participation = new Participation();
        $form = $this->createForm(ParticipationFilterType::class, $participation);
        $form->handleRequest($request);
        $filteredData = [
            ParticipationRepository::EMPLOYEE =>  null,
            ParticipationRepository::EVENTNAME => null,
            ParticipationRepository::EVENTDATE => null
        ];

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->get('employee')->getData();
            $eventName = $form->get('eventName')->getData();
            $eventDate = $form->get('eventDate')->getData();
            $filteredData = [
                ParticipationRepository::EMPLOYEE =>  $employee,
             ParticipationRepository::EVENTNAME => $eventName,
             ParticipationRepository::EVENTDATE =>$eventDate
            ];
        }

        $participations = [];
        $participations = ($em->getRepository(Participation::class))->findFiltered($filteredData);


        return $this->renderForm('main/index.html.twig', [
            'participations' => $participations,
            'form' => $form,

        ]);
    }
    /**
     * @Route("/import", name="import")
     */
    public function import(ParticipationsFromJsonImporter $importer, Request $request): Response
    {

        $product = new ImportedFileEntity();
        $form = $this->createForm(JsonFileType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('jsonFile')->getData();

            if ($brochureFile) {
                $newFilename = $this->getParameter('imports_file_name').'.'.$brochureFile->guessExtension();

                // Move the file to the directory where imports are stored
                try {
                    // throw new FileException("Fake Error", 1);
                    
                    $brochureFile->move(
                        $this->getParameter('imports_directory'),
                        $this->getParameter('imports_file_name').'.'.$brochureFile->guessExtension(),
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $form->addError(new FormError('Failed to upload file'));
                    return $this->renderForm('main/import.html.twig', [
                        'form' => $form,
                    ]);
                }
            }

            $importer->import();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('main/import.html.twig', [
            'form' => $form,
        ]);
    }
}
