<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SpeakerSubmission;
use AppBundle\Form\SpeakerSubmissionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;

/**
 * Class SpeakerSubmissionController
 *
 * @package AppBundle\Controller
 */
class SpeakerSubmissionController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     *
     * @Route("/submit/", name="speaker_submission_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        throw new GoneHttpException('The call for speakers has ended');
        $submission = new SpeakerSubmission();
        $form = $this->createCreateForm($submission);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($submission);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Thank you for your submission'
            );

            return $this->redirect($this->generateUrl('homepage'));
        }

        $this->get('session')->getFlashBag()->add(
            'error',
            'Please correct the noted errors and resubmit the form'
        );

        return $this->render("speaker_submission/new.html.twig", array(
                'submission' => $submission,
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/submit/", name="speaker_submission_new")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        throw new GoneHttpException('The call for speakers has ended');
        $submission = new SpeakerSubmission();
        $form = $this->createCreateForm($submission);
        return $this->render("speaker_submission/new.html.twig", array(
                'submission' => $submission,
                'form' => $form->createView(),
            ));
    }

    /**
     * @param SpeakerSubmission $submission
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(SpeakerSubmission $submission)
    {
        $form = $this->createForm('AppBundle\Form\SpeakerSubmissionType', $submission, array(
                'action' => $this->generateUrl("speaker_submission_create"),
                'method' => 'POST',
            ));

        $form->add('submit', SubmitType::class, array('label' => 'Submit'));
        return $form;
    }
}
