<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 */
class TaskController extends AbstractController
{

    /**
     * @Route("/", name="task_list", methods={"GET"})
     * @param TaskRepository $taskRepository
     * @return Response
     * @author Nicolas de Fontaine
     */
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="task_create", methods={"GET","POST"})
     * @param Request $request
     * @param TaskService $taskService
     * @return Response
     * @author Nicolas de Fontaine
     */
    public function new(Request $request, TaskService $taskService): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->createOrUpdate($task);
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     * @param Task $task
     * @return Response
     * @author Nicolas de Fontaine
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Task $task
     * @param TaskService $taskService
     * @return Response
     * @author Nicolas de Fontaine
     */
    public function edit(Request $request, Task $task, TaskService $taskService): Response
    {
        $this->denyAccessUnlessGranted('editTask', $task);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->createOrUpdate($task);
            $this->addFlash('success', 'La tâche a été bien été modifé.');
            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="task_delete", methods={"POST"})
     * @param Request $request
     * @param Task $task
     * @param TaskService $taskService
     * @return Response
     * @author Nicolas de Fontaine
     */
    public function delete(Request $request, Task $task, TaskService $taskService): Response
    {
        $this->denyAccessUnlessGranted('deleteTask', $task);
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskService->delete($task);
            $this->addFlash('success', 'La tâche a été bien été supprimé.');
        }

        return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @param Task $task
     * @param TaskService $taskService
     * @return RedirectResponse
     * @author Nicolas de Fontaine
     */
    public function toggle(Task $task, TaskService $taskService)
    {
        $taskService->toggle($task);
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }
}
