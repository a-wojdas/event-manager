<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\JsonResponse;
use EventBundle\Entity\Event,
    EventBundle\Form\Type\EventType,
    EventBundle\Entity\EventComment,
    EventBundle\Form\Type\EventCommentType,
    EventBundle\Form\Type\EventSearchType;
use EventBundle\Traits\StandardController;

/**
 * Description of EventController
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventController extends Controller {

    use StandardController;

    /**
     * Główna akcja serwisu.
     * Wyświetla listę imprez.
     * 
     * @param \Integer POST[address]
     * 
     * Wyświetla listę imprez w określonej odległości od wskazanego adresu.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request) {
        $addressRepository = $this->getRepository('EventBundle:Address');
        $repository = $this->getRepository('EventBundle:Event');

        /*
         * Forma służąca do szukania imprez 
         * w określonej odległości od wskazanego adresu.
         */
        $form = $this->createForm(EventSearchType::class, null, ['action' => $this->generateUrl('event_search')]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $id = (int) $form->get('address')->getData();

                $addressEntity = $addressRepository->findOneById((int) $id);

                if (!$addressEntity) {
                    throw new NotFoundHttpException();
                }

                /*
                 * Wybierz wszystkie imrezy w odległości 2 km od wskazanego adresu.
                 */
                $events = $repository->findByDistance(2, $addressEntity->getLatitude(), $addressEntity->getLongitude());
            } else {
                /*
                 * Pokaż wszystkie imprezy.
                 */
                $events = $repository->findAll();
            }
        } else {
            /*
             * Pokaż wszystkie imprezy.
             */
            $events = $repository->findAll();
        }

        /*
         * Przekaż listę adresów do filtrowania.
         */
        $addresses = $repository->findAllAddresses();

        return $this->render('EventBundle:Event:index.html.twig', [
                    'events' => $events, 'search_form' => $form->createView(),
                    'addresses' => json_encode($addresses)]);
    }

    /**
     * Zwraca listę adresów w postaci JSON.
     *
     * @return JsonResponse
     */
    public function addressesAction() {
        $repository = $this->getRepository('EventBundle:Event');
        $addresses = $repository->findAllAddresses();

        return new JsonResponse($addresses);
    }

    /**
     * Akcja umożliwia utworzenie nowej imprezy.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request) {
        $entity = new Event();

        $form = $this->createForm(EventType::class, $entity);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $latitude = $form->get('latitude')->getData();
                $longitude = $form->get('longitude')->getData();

                /*
                 * Sprawdź czy lokalizacja już istnieje.
                 */
                $addressFactory = $this->get('event.addresses_factory');
                $location = $addressFactory->exists($latitude, $longitude);

                if (!$location) {
                    /*
                     * Jeśli nie istnieje utwórz ją.
                     */
                    $location = $addressFactory->create($latitude, $longitude);
                }

                $entity->setLocation($location);

                /*
                 * Utwórz imprezę.
                 */
                $placeFactory = $this->get('event.events_factory');
                $placeFactory->createFromEntity($entity);

                /*
                 * Wyślij e-mail z powiadomieniem o utworzeniu imprezy.
                 */
                $config = $this->container->getParameter('address_notify');

                $to = $config['to'];
                $from = $config['from'];
                $subject = $config['subject'];
                $body = 'Dodano nową lokalizację "' . $form->get('name')->getData() . '" '
                        . 'ze strony serwisu "' . $this->generateUrl('event_view', ['id' => $entity->getId()]) . '".'
                ;

                $mailer = $this->container->get('event.mailer');
                $mailer->send($from, $to, $subject, $body);

                /*
                 * Zwróć wynik działania akcji lub przekieruj.
                 */
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(array('result' => 'OK',
                        'msg' => $this->trans('event:create.ok'),
                        'id' => $entity->getId()));
                } else {
                    $this->flashSuccess('event:create.ok');
                    return $this->redirect($this->generateUrl('event_view', ['id' => $entity->getId()]));
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(array('result' => 'Error',
                        'msg' => $this->trans('event:create.error01'),
                        'code' => 1));
                }
            }
        }

        return $this->render('EventBundle:Event:edit.html.twig', ['action' => 'create',
                    'entity' => $entity, 'form' => $form->createView()]);
    }

    /**
     * Pokaż szczegóły imprezy
     *
     * @param Integer $id
     * id - identyfikator imprezy
     *
     * @return Response
     */
    public function viewAction($id) {
        $repository = $this->getRepository('EventBundle:Event');
        /*
         * Szukaj imprezy po ID.
         */
        $entity = $repository->findOneById((int) $id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        /*
         * Utwórz formę umożliwiającą komentowanie imprezy.
         */
        $eventComment = new EventComment();
        $form = $this->createForm(EventCommentType::class, $eventComment, ['action' => $this->generateUrl('event_comment_add', ['id' => $entity->getId()])]);

        return $this->render('EventBundle:Event:view.html.twig', ['entity' => $entity, 'form' => $form->createView()]);
    }

    /**
     * Dodaj komentarz do imprezy.
     *
     * @param Integer $id
     * id - Identyfikator imprezy
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function commentAction($id, Request $request) {
        /*
         * Wybierz imprezę.
         */
        $repository = $this->getRepository('EventBundle:Event');
        $eventEntity = $repository->findOneById((int) $id);

        if (!$eventEntity) {
            throw new NotFoundHttpException();
        }

        /*
         * Zweryfikuj poprawność przesłanego komentarza.
         */
        $entity = new EventComment();

        $form = $this->createForm(EventCommentType::class, $entity);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /*
                 * Przypisz komentarzowi komentowaną imprezę.
                 */
                $entity->setEvent($eventEntity);

                $this->em->persist($entity);
                $this->em->flush();

                /*
                 * Zwróć wynik działania akcji lub przekieruj.
                 */
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(array('result' => 'OK',
                        'msg' => $this->trans('event:create.comment.ok'),
                        'id' => $entity->getId()));
                } else {
                    $this->flashSuccess('event:create.comment.ok');
                    return $this->redirect($this->generateUrl('event_view', ['id' => $eventEntity->getId()]));
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(array('result' => 'Error',
                        'msg' => $this->trans('event:create.comment.error01'),
                        'code' => 1));
                }
            }
        }

        return $this->render('EventBundle:Event:view.html.twig', [
                    'entity' => $eventEntity, 'form' => $form->createView()]);
    }

    /**
     * Usuń komentarz do imprezy.
     *
     * @param Integer $id
     * id - Identyfikator imprezy
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function commentRemoveAction($id, Request $request) {
        /*
         * Wybierz komentarz imprezy.
         */
        $repository = $this->getRepository('EventBundle:EventComment');
        $entity = $repository->findOneById((int) $id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        /*
         * Wybierz imprezę.
         */
        $eventRepository = $this->getRepository('EventBundle:Event');
        $eventEntity = $eventRepository->findOneById((int) $entity->getEvent()->getId());

        /*
         * Usuń komentarz imprezy.
         */
        $this->em->remove($entity);
        $this->em->flush();

        /*
         * Zwróć wynik działania akcji lub przekieruj.
         */
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('result' => 'OK',
                'msg' => $this->trans('event:remove.comment.ok')));
        } else {
            $this->flashSuccess('event:remove.comment.ok');
            return $this->redirect($this->generateUrl('event_view', ['id' => $eventEntity->getId()]));
        }
    }

}
