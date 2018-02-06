<?php

namespace AppBundle\Controller;

use AppBundle\Entity\message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class sendMessageController extends Controller {

	/**
	 * @Route("/", name="main")
	 */
	public function indexAction(Request $request) {

		$message = new message();
		$form = $this->createFormBuilder($message)
			->add('message', TextareaType::class)
			->add('send', SubmitType::class)
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$msg = $form['message']->getData();

			$flag = '0';
			$message->setFlag($flag);
			$message->setMessage($msg);
			$em = $this->getDoctrine()->getManager();
			$em->persist($message);
			$em->flush();

			$this->addFlash('notice', 'message successfully sent!!!');
			return $this->redirectToRoute('main');
		}

		return $this->render('message/index.html.twig', [

			'message' => $form->createView(),
		]);
	}

	/**
	 * @Route("/viewmail", name="viewmail")
	 */
	public function viewAllMailAction(Request $request) {

		$em = $this->getDoctrine()->getManager();
		$dql = 'SELECT bp FROM AppBundle:message bp ORDER BY bp.id DESC';
		$query = $em->createQuery($dql);

		$email = $this->getDoctrine()
			->getRepository(message::class)
			->findAll();

		$flag = '1';
		$_em = $this->getDoctrine()->getManager();
		$update_flag = $_em->getRepository('AppBundle:message');

		$qb = $update_flag->createQueryBuilder('p');
		$qb->update()
			->set('p.flag', ':newFlag')
			->setParameter('newFlag', $flag);
		$qb->getQuery()->execute();

		return $this->render('message/message.html.twig', [
			'email' => $email,
		]);
	}
}
