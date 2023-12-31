<?php

namespace App\Controller\Back;

use App\Entity\Team;
use App\Form\TeamFormType;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/team')]
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_back_team')]
    public function index(TeamRepository $teamRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $teams = $teamRepository->findBy([],['id'=>'DESC']);
        $datas = [];

        for ($i=0;$i<count($teams);$i++){
            $array = [
                $teams[$i]->getId(),
                $teams[$i]->getTitle(),
                $teams[$i]->getCity(),
                $teams[$i]->getSport()->getTitle(),
                $teams[$i]->getLeague()->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('back/team/team.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_back_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository, LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $team = new Team();
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->add($team);

            $id = $team->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_team', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_team_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/team/new.html.twig', [
            'team' => $team,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeamRepository $teamRepository, LeagueRepository $leagueRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $team = $teamRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teamRepository->add($team);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_team', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_team_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/team/edit.html.twig', [
            'team' => $team,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
}